<?php
/**

    
    * * * * * WorksController es el controlador de la vista mainWorks.php *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
    include_once 'controllers/WorksDAO.php';
    include_once 'controllers/WorksComponent.php';
    include_once 'controllers/CustomersDAO.php';
    include_once 'models/CustomerClass.php';
    include_once 'models/WorkClass.php';
    include_once 'models/InvoiceClass.php';

    /**
        NOTA IMPORTANTE DEL FUNCIONAMIENTO DE ESTE CONTROLADOR:
        El controlador no redirecciona hacia ninguna página. Solamente recibe los datos del
        formulario, realiza las acciones de mostrar en pantalla, CRUD sobre DDBB, etc.

        En cuanto a impresión, construye una serie de arrays $_SESSION con la información a 
        mostrar en el listado.
        Esta información será mostrada en una pestaña APARTE, que será instanciada mediante
        JQuery. Para que JQuery abra la ventana en el momento adecuado, se utiliza un input
        type="hidden" con value 'OK' cuando los datos ya se han generado; entonces JQuery
        abre la ventana y se muestra la información.

    */

    // control de peticiones get de la página
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if ($_SESSION['OK']=="OK") {

        } else {
            // se desvian hacia la pagina de error
            $_SESSION['OK']="";
            header('Location: ' . "error404.php", true, $statusCode);
            die();
        }

    } 

    // variables de busqueda por mes
    $yearStart="";
    $yearFinal="";
    $monthStart="";
    $monthFinal="";
    $yearSel=array(0=>'selected="selected"',1=>"",2=>"");
    

    // variable de busqueda por cliente
    $customerSearched="";

    //variables de busqueda por opciones
    $opcion=0;
    $chequed1='checked="checked"';
    $chequed2="";
    $chequed3="";

    // variables del formulario
    $idWork=0;
    $fecha=""; 
    $codigo="";
    $clienteId=0;
    $texto="";
    $cantidad="";
    $importe="";
    $base="";
    // asignamos el tipo de iva general, si no existe tipo
    if (!isset($iva)) $iva=$_SESSION['ivaTipo3'];
    $total="";
    $factura="";

    // lectura de los valores REQUEST
    foreach ($_REQUEST as $key => $value) {

        //echo $key.'**>'.$value.' /';
        // ***** ZONA DE LECTURA DE BOTONES DEL FORMULARIO
        if ($key=='borrar') {
            // borramos los datos del formulario, y se habilitan
            // todas las casillas y botones
            $codigo="";
            $clienteId=0;
            $fecha="";            
            $factura="";
            $texto="";
            $cantidad="";
            $importe="";
            $base="";
            $iva=$_SESSION['ivaTipo3'];
            $total="";
            // deshabilitamos la confirmación de imprimir
            $_SESSION['grabW']="";
            // deshabilitamos el mensaje informativo
            $_SESSION['mensajeWork']="";
        }

        if ($key=='eliminar') {
            // borramos los datos del formulario, y se habilitan
            // todas las casillas y botones
            $idWork=depura_input($_REQUEST['idwork']);

            $factura=depura_input($_REQUEST['invoicenumber']);
            
            if ($idWork>0 && strlen($factura)==0) {
                // si hay un id, hay que borrar el objeto
                $response=deleteThisWork($idWork);
                if ($response!=false) {
                    // si retorna la lista, cambiamos la lista anterior
                    // por la nueva lista sin el objeto borrado
                    $idWork=0;
                    $_REQUEST['idwork']='0';
                    $codigo="";
                    $clienteId=0;
                    $fecha="";            
                    $factura="";
                    $texto="";
                    $cantidad="";
                    $importe="";
                    $base="";
                    $iva=$_SESSION['ivaTipo3'];
                    $total="";
                    // deshabilitamos la confirmación de imprimir
                    $_SESSION['grabW']="";                    
                    $_SESSION['mensajeWork']="Trabajo eliminado";
                } else {
                    $_SESSION['mensajeWork']="ERROR eliminando el trabajo";
                }
            }
        }

        if ($key=='grabar') {
            // recuperamos los datos de formulario y los grabamos
            $factura=depura_input($_REQUEST['invoicenumber']);
            if (strlen($factura)==0) {
                // SOLO ES POSIBLE GRABAR O MODIFICAR TRABAJOS QUE NO ESTEN FACTURADOS
                $idWork=depura_input($_REQUEST['idwork']);
                $clienteObj=searchCustomerclassByName(depura_input($_REQUEST['clientework']));
                if ($clienteObj!=false) {

                    $clienteId=$clienteObj->getId();
                    $codigo=depura_input($_REQUEST['codework']);
                    // hay que cambiar la fecha a formato date
                    $fecha=convertDateToInternational(depura_input($_REQUEST['datework']));             
                    $texto=depura_input($_REQUEST['textwork']);
                    $cantidad=depura_input($_REQUEST['udswork']);
                    $importe=depura_input($_REQUEST['pricework']);
                    $base=depura_input($_REQUEST['basework']);
                    $iva=depura_input($_REQUEST['ivawork']);
                    $total=depura_input($_REQUEST['amountwork']);
                    // datos de información del trabajo para impresión
                    $_SESSION['dataInfo']=array("","");
                    $_SESSION['dataInfo'][0]=convertDateToSpanish($fecha);
                    $_SESSION['dataInfo'][1]=$codigo;   // ojo, si es un trabajo nuevo esta en blanco
                     // datos de información del trabajo para impresión
                    $_SESSION['dataWork']=array("",0,0,0);
                    $_SESSION['dataWork'][0]=$texto;
                    $_SESSION['dataWork'][1]=$cantidad;
                    $_SESSION['dataWork'][2]=$importe;
                    $_SESSION['dataWork'][3]=$iva; 
                     // datos de información del trabajo para impresión
                    $_SESSION['dataAmounts']=array($base,($base*$iva/100),$total);
                    // datos de información del trabajo para impresión
                    //$custom=readCustomer($clienteId,$_SESSION['workingCompany']);
                    //if ($custom!=false) {
                        // variables para impresión
                        $_SESSION['dataCustomer']=array("","","","");
                        $_SESSION['dataCustomer'][0]=$clienteObj->getNombre();
                        $_SESSION['dataCustomer'][1]=$clienteObj->getDireccion();
                        $_SESSION['dataCustomer'][2]=$clienteObj->getCodpostal().' '.$clienteObj->getLocalidad();
                        $_SESSION['dataCustomer'][3]=$clienteObj->getNif();
                   // }
                    if ($idWork>0) {
                        // es una modificacion
                        $changedWork=new WorkClass($_SESSION['workingCompany'],$fecha,$codigo,$clienteId,$texto,$cantidad,$importe,$base,$iva,$total,"");
                        $changedWork->setId($idWork);
                        if (modifyThisWork($changedWork)!=false) {
                            // limpia el formulario                  
                            $idWork=0;
                            $_REQUEST['idwork']='0';
                            $codigo="";
                            $clienteId=0;
                            $fecha="";            
                            $factura="";
                            $texto="";
                            $cantidad="";
                            $importe="";
                            $base="";
                            $iva=$_SESSION['ivaTipo3'];
                            $total="";
                            // habilitamos la confirmación de imprimir
                            $_SESSION['grabW']="OK";
                            // mensaje informativo
                            $_SESSION['mensajeWork']="modificación efectuada";                        
                        } else {
                            // mensaje informativo
                            $fecha=depura_input($_REQUEST['datework']); 
                            $_SESSION['mensajeWork']="ERROR modificando el trabajo";
                        }
                             
                    } else {
                        // es una nuevo trabajo
                        // creamos el objeto work y lo grabamos
                        $numwork=getNextWorkNumber();
                        $newWork=new WorkClass($_SESSION['workingCompany'],$fecha,$numwork,$clienteId,$texto,$cantidad,$importe,$base,$iva,$total,"");
                        if (recordThisWork($newWork)!=false) {
                            // obtenemos el numero del trabajo para la impresion
                            $_SESSION['dataInfo'][1]=$numwork;
                            // limpia el formulario
                            $idWork=0;
                            $_REQUEST['idwork']='0';
                            $codigo="";
                            $clienteId=0;
                            $fecha="";            
                            $factura="";
                            $texto="";
                            $cantidad="";
                            $importe="";
                            $base="";
                            $iva=$_SESSION['ivaTipo3'];
                            $total="";
                            // habilitamos la confirmación de imprimir
                            $_SESSION['grabW']="OK";                        
                            // mensaje informativo
                            $_SESSION['mensajeWork']="grabación efectuada";
                        } else {
                            $fecha=depura_input($_REQUEST['datework']); 
                            // mensaje informativo
                            $_SESSION['mensajeWork']="ERROR grabando el trabajo";
                        }
                    }
                } else {
                    $fecha=depura_input($_REQUEST['datework']); 
                    // mensaje informativo
                    $_SESSION['mensajeWork']="ERROR grabando el trabajo";
                }
            }
        }       

        // tomamos el valor del selector de año
        if ($key=='year' && $value!='Todos los años') {
            $yearStart=$value;
            $yearFinal=$value;
            if ($yearStart=='2014') {
                $yearSel=array(0=>"",1=>'selected="selected"',2=>"");
            } elseif ($yearStart=='2015') {
                $yearSel=array(0=>'',1=>"",2=>'selected="selected"');
            }
        } else if($key=='year' && $value=='Todos los años') {
            $yearSel=array(0=>'selected="selected"',1=>"",2=>"");
            $monthAvailable='disabled="disabled"';
        }

        // tomamos el valor del selector de mes
        if ($key=='month' && $value!='Todos los meses') {
            switch ($value) {
                case 'Enero':
                    $m="01";
                    break;
                case 'Febrero':
                    $m="02";
                    break;
                case 'Marzo':
                    $m="03";
                    break;
                case 'Abril':
                    $m="04";
                    break;
                case 'Mayo':
                    $m="05";
                    break;
                case 'Junio':
                    $m="06";
                    break;                                        
                case 'Julio':
                    $m="07";
                    break;
                case 'Agosto':
                    $m="08";
                    break;
                case 'Septiembre':
                    $m="09";
                    break;
                case 'Octubre':
                    $m="10";
                    break;
                case 'Noviembre':
                    $m="11";
                    break;
                case 'Diciembre':
                    $m="12";
                    break;                     
                default:
                    $m="0";
                    break;
            }
            $monthStart=$m;
            $monthFinal=$m;
            $monthSel=array(0=>"",1=>"",2=>"");
            $monthSel[intval($m)]='selected="selected"';
        }        

        // tomamos el valor del selector de clientes
        if ($key=='customer' && $value!='Todos los clientes') { 
            $customerSearched=$value;         
        } 

        // tomamos el valor de selector opciones trabajo
        if ($key=='situacion') {
            if ($value=='todos') {
                $opcion=0;
                $chequed1='checked="checked"';
                $chequed2="";
                $chequed3="";
            } elseif ($value=='sin') {
                $opcion=1;
                $chequed2='checked="checked"';
                $chequed1="";
                $chequed3="";
            } elseif ($value=='con') {
                $opcion=2;
                $chequed3='checked="checked"';
                $chequed1="";
                $chequed2="";
            }           
        } 

        if ($key=='Sel') {       
            // variables de busqueda por mes
            if ($yearStart=="") {
                $yearStart="2014";
            }
            
            if ($yearFinal=="") {
                $yearFinal="2099";
            }

            if ($monthStart=="") {
                $monthStart="01";
                $monthFinal="12";
            }
            // deshabilitamos la confirmación de imprimir
            $_SESSION['grabW']="";
            // borramos mensaje
            $_SESSION['mensajeWork']="";
        }

        // esto obtiene el trabajo para mostrarlo en el formulario
        if (substr($key,0,3)=='14/' || substr($key,0,3)=='15/') {

            // deshabilitamos el mensaje informativo
            $_SESSION['mensajeWork']="";

            $workclass=searchWorkclass($key);
            if ($workclass!=false) {
                $idWork=$workclass->getId();
                $company=$workclass->getCompany();
                $codigo=$workclass->getNumero();
                // buscamos el dato del cliente
                $clienteId=$workclass->getCliente();              
                $fecha=convertDateToSpanish($workclass->getFecha());
                $texto=$workclass->getTexto();
                $cantidad=$workclass->getCantidad();
                $importe=$workclass->getImporte();
                $base=$workclass->getBase();
                $iva=$workclass->getIva();
                $total=$workclass->getTotal();
                $factura=$workclass->getFactura();
                // datos de información del trabajo para impresión
                $_SESSION['dataInfo']=array("","");
                $_SESSION['dataInfo'][0]=$fecha;
                $_SESSION['dataInfo'][1]=$codigo;
                 // datos de información del trabajo para impresión
                $_SESSION['dataWork']=array("",0,0,0);
                $_SESSION['dataWork'][0]=$texto;
                $_SESSION['dataWork'][1]=$cantidad;
                $_SESSION['dataWork'][2]=$importe;
                $_SESSION['dataWork'][3]=$iva; 
                 // datos de información del trabajo para impresión
                $_SESSION['dataAmounts']=array($base,($base*$iva/100),$total);
                // datos de información del trabajo para impresión
                $custom=readCustomer($clienteId,$_SESSION['workingCompany']);
                if ($custom!=false) {
                    // variables para impresión
                    $_SESSION['dataCustomer']=array("","","","");
                    $_SESSION['dataCustomer'][0]=$custom->getNombre();
                    $_SESSION['dataCustomer'][1]=$custom->getDireccion();
                    $_SESSION['dataCustomer'][2]=$custom->getCodpostal().' '.$custom->getLocalidad();
                    $_SESSION['dataCustomer'][3]=$custom->getNif();
                }   
            }

        }

    }

    // variables de editable el formulario
    // las casillas del formulario seran editables si los trabajos no estan facturados
    $editable='';
    $writable='';
    $usable='';
    if (strlen($factura)>0) {
        // tiene numero de factura se deshabilita
        // grabar, eliminar y cambio de usuario
        $editable='disabled="disabled"';
        $writable="readonly";
        $usable='disabled';
    } 

    if (!strlen($codigo)>0) {
        // boton eliminar deshabilitado si no tiene numero de trabajo
        $usable='disabled';
    }

?>