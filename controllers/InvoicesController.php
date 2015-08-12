<?php
/**

    
    * * * * * InvoicesController es el controlador de la vista mainInvoices.php *** *
    * * @Author musef v.1.0 2015-08-01
    

*/

session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
    include_once 'controllers/CustomersDAO.php';
    include_once 'controllers/FormasPagoDAO.php';
    include_once 'controllers/InvoicesDAO.php';
    include_once 'controllers/InvoicesComponent.php';

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


    if (isset($_SESSION['dataInfo'])) {
        unset($_SESSION['dataInfo']);
    }

    if (isset($_SESSION['dataAmounts'])) {
        unset($_SESSION['dataAmounts']);
    }

    // control de peticiones get de la página
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if ($_SESSION['OK']=="OK") {
            // por aqui pasa la redireccion normal de la pagina

        } else {
            // se desvian hacia la pagina de error
            $_SESSION['OK']="";
            header('Location: ' . "error404.php", true, 303);
            die();
        }
    } 

    // variable de busqueda por cliente
    $customerSearched=0;

    // variables de busqueda por opcion
    $opcion=1;
    $ch0="";
    $ch1="checked";
    $ch2="";
    $opcionF=1;
    $chF0="";
    $chF1="checked";
    $chF2="";    

    // variables de formulario
    $clienteId=0;           // cliente seleccionado
    if (!isset($_SESSION['nameCustomer'])) {
        $_SESSION['nameCustomer']="";   // nombre del cliente
    }
    $numberInvoice="";      // numero de factura
    if (!isset($_REQUEST['dateinvoice'])) {
        $dateInvoice=date("d-m-Y");        // fecha de factura
    }
    if (!isset($_SESSION['formaDpago'])) {
        $_SESSION['formaDpago']=0;        // forma de pago
    }
    if (!isset($_SESSION['dataInvoice'])) {
        // datos de los trabajos de la factura
        $_SESSION['dataInvoice']=array(array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0));
    }

    $vencimiento="";
    
    // variables de acumulados en formulario factura
    $bs1=0;
    $bs2=0;
    $bs3=0;
    $cu1=0;
    $cu2=0;
    $cu3=0;
    $ttl=0;

    // deshabilita botones
    $controlButton1="";
    $controlButton2="";
    $controlButton3="";

    // habilita el div con los datos
    if (!isset($divToShow)) {
        $divToShow=0;
    }


    // lectura de los valores REQUEST
    foreach ($_REQUEST as $key => $value) {
       //echo $key.'-'.$value.'//';
            // tomamos el valor de selector opciones trabajo
        if ($key=='seleccion') {
            if ($value=='0') {
                $opcion=0;
                $ch0="checked";
                $ch1="";
                $ch2="";
            } elseif ($value=='1') {
                $opcion=1;
                $ch0="";
                $ch1="checked";
                $ch2="";
            } elseif ($value=='2') {
                $opcion=2;
                $ch0="";
                $ch1="";
                $ch2="checked";
            }           
        }

            // tomamos el valor de selector opciones facturas
        if ($key=='seleccionF') {
            if ($value=='0') {
                $opcionF=0;
                $chF0="checked";
                $chF1="";
                $chF2="";
            } elseif ($value=='1') {
                $opcionF=1;
                $chF0="";
                $chF1="checked";
                $chF2="";
            } elseif ($value=='2') {
                $opcionF=2;
                $chF0="";
                $chF1="";
                $chF2="checked";
            }           
        }        

       // ***** ZONA BOTONES
        // ********************

        // variable que controla el div listado con datos

        // si pulsamos seleccionar
        if ($key=='Sel') {

            // construimos el div de eleccion de trabajos
            $customerSearched=$clienteId;
            $custom=readCustomer($clienteId,$_SESSION['workingCompany']);
            if ($custom!=false) {
                $_SESSION['nameCustomer']=$custom->getNombre();
                $_SESSION['formaDpago']=$custom->getFormapago();
                // variables para facturacion
                $_SESSION['dataCustomer']=array("","","","");
                $_SESSION['dataCustomer'][0]=$custom->getNombre();
                $_SESSION['dataCustomer'][1]=$custom->getDireccion();
                $_SESSION['dataCustomer'][2]=$custom->getCodpostal().' '.$custom->getLocalidad();
                $_SESSION['dataCustomer'][3]=$custom->getNif();
            } 
            // deshabilitamos botones
            $controlButton1="";
            $controlButton2="disabled";
            $controlButton3="disabled";
            // no mostrar impresion
            $_SESSION['grab']="";            
            // div a mostrar
            $divToShow=1;
            // borramos mensaje
            $_SESSION['mensajeInvoice']="";

        } else if ($key=='SelF') {
            // construimos el div de eleccion de facturas
            $customerSearched=$clienteId;
            $custom=readCustomer($clienteId,$_SESSION['workingCompany']);
            if ($custom!=false) {
                $_SESSION['nameCustomer']=$custom->getNombre();
                // variables para facturacion
                $_SESSION['dataCustomer']=array("","","","");
                $_SESSION['dataCustomer'][0]=$custom->getNombre();
                $_SESSION['dataCustomer'][1]=$custom->getDireccion();
                $_SESSION['dataCustomer'][2]=$custom->getCodpostal().' '.$custom->getLocalidad();
                $_SESSION['dataCustomer'][3]=$custom->getNif();                
            } 
            // deshabilitamos botones
            $controlButton1="disabled";
            $controlButton2="";
            $controlButton3="";
            // div a mostrar
            $divToShow=2;
            // no mostrar impresion
            $_SESSION['grab']="";
            // borramos mensaje
            $_SESSION['mensajeInvoice']="";
        }

        if ($key=='del1' || $key=='del2' || $key=='del3' || $key=='del4' || $key=='del5') {
            // si pulsamos en uno de los delX, borraremos la linea correspondiente y
            // reordenaremos el array con los datos
            reorderDataInvoice($key);
            // calculamos totales de factura
            $totales=calculaFactura($_SESSION['dataInvoice']);
            $bs1=formato($totales[1]);
            $bs2=formato($totales[2]);
            $bs3=formato($totales[3]);
            $cu1=formato($totales[4]);
            $cu2=formato($totales[5]);
            $cu3=formato($totales[6]);
            $ttl=formato($totales[0]);            

            $controlButton3="disabled";
        }

        if ($key=='borrar') {
            // reseteamos botones laterales
            $clienteId=0;
            $opcion=1;
            // borramos opciones de formulario
            $_SESSION['nameCustomer']="";
            $dateInvoice=date("d-m-Y");
            $numberInvoice="";      
            if (isset($_SESSION['dataInvoice'])) unset($_SESSION['dataInvoice']);
            if (isset($_SESSION['dataWorksInvoice'])) unset($_SESSION['dataWorksInvoice']);    
            $_SESSION['dataInvoice']=array(array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0));
            $bs1=0;
            $bs2=0;
            $bs3=0;
            $cu1=0;
            $cu2=0;
            $cu3=0;
            $ttl=0; 
            $controlButton1="";            
            $controlButton2="";
            $controlButton3="disabled";
            $_SESSION['grab']="";
            $_SESSION['mensajeInvoice']="";
        }

        if ($key=='eliminar') {
            // pulsado eliminar, borramos la factura que tenemos en pantalla
            if (deleteThisInvoice($_REQUEST['codeinvoice'],$_SESSION['dataInvoice'])) {
                // si todo ha ido bien, hay que borrar las variables de formulario
                // reseteamos botones laterales
                $clienteId=0;
                $opcion=1;
                // borramos opciones de formulario
                $_SESSION['nameCustomer']="";
                $dateInvoice=date("d-m-Y");
                $numberInvoice="";      
                if (isset($_SESSION['dataInvoice'])) unset($_SESSION['dataInvoice']);
                if (isset($_SESSION['dataWorksInvoice'])) unset($_SESSION['dataWorksInvoice']);      
                $_SESSION['dataInvoice']=array(array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0));
                $bs1=0;
                $bs2=0;
                $bs3=0;
                $cu1=0;
                $cu2=0;
                $cu3=0;
                $ttl=0; 
                $controlButton1="";            
                $controlButton2="";
                $controlButton3="disabled";
                $_SESSION['grab']="";
                $_SESSION['mensajeInvoice']="factura eliminada correctamente";
            } else {
                $_SESSION['mensajeInvoice']="Error eliminando factura";
            }
        }

        if ($key=='grabar') {
            $_SESSION['grab']='OK';
            $operacion=($_REQUEST['codeinvoice']=='') ? 0 : 1;
            $fe=depura_input($_REQUEST['dateinvoice']);
            $fe=convertDateToInternational($fe);
            $de=$clienteId;         
            // hay que transformar formatos string spanish en formatos number english
            $b1=formato_db(depura_input($_REQUEST['baseinv1']));
            $b2=formato_db(depura_input($_REQUEST['baseinv2']));
            $b3=formato_db(depura_input($_REQUEST['baseinv3']));
            $c1=formato_db(depura_input($_REQUEST['cuoinv1']));
            $c2=formato_db(depura_input($_REQUEST['cuoinv2']));
            $c3=formato_db(depura_input($_REQUEST['cuoinv3']));
            $tt=formato_db(depura_input($_REQUEST['amountinv']));
            $fp=$_REQUEST['formaPago'];
            $vt=getVencimiento($fp,$fe,$_SESSION['workingCompany']);
            // creamos un array con el contenido de dataInvoice.
            // dataInvoice mide 5, pero esta rellenado con ceros, por lo que
            // hay que extraer los datos
            $dataArray=array();
            for ($i=0; $i <5 ; $i++) { 
                if ($_SESSION['dataInvoice'][$i][0]!=0) {
                    $dataArray[]=$_SESSION['dataInvoice'][$i];
                } else {
                    // al encontrar el primer id=0 deja de contar
                    break;
                }
            }
           // INFORMACION PARA LA IMPRESION DE LA FACTURA, YA QUE HA SIDO HABILITADA
                $_SESSION['dataInfo']=array("","","","");
                $_SESSION['dataInfo'][0]=depura_input($_REQUEST['dateinvoice']);
                $_SESSION['dataInfo'][1]=$_REQUEST['codeinvoice']; // SOLO FUNCIONA EN MODIFICACION
                $_SESSION['dataInfo'][2]=getNameFormaPago($fp,$_SESSION['workingCompany']);
                $_SESSION['dataInfo'][3]=$vt;
                $_SESSION['dataAmounts']=array(0,0,0,0,0,0,0);    
                $_SESSION['dataAmounts'][0]=formato($b1);
                $_SESSION['dataAmounts'][1]=formato($c1);
                $_SESSION['dataAmounts'][2]=formato($b2);
                $_SESSION['dataAmounts'][3]=formato($c2);
                $_SESSION['dataAmounts'][4]=formato($b3);
                $_SESSION['dataAmounts'][5]=formato($c3);
                $_SESSION['dataAmounts'][6]=formato($tt); 

            // verificamos si contiene albaranes
            if ($i>0) {
                $al=$i;  // este parametro no tiene utilidad en la aplicación, es informativo
                // creamos la factura a grabar
                // notese que no le asignamos numero, porque lo hará la aplicación
                $newInvoice=new InvoiceClass($_SESSION['workingCompany'],'',$fe,$de,$al,$b1,$c1,$b2,$c2,$b3,$c3,$tt,$fp,$vt);

                // MOMENTO IMPORTANTE: hemos de distinguir nueva grabación de modificación
                // $operacion=0 es grabar; =1 es modificar
                if ($operacion==0) {
                    // grabamos la factura y recibimos el numero
                    $fact=recordThisInvoice($newInvoice,$dataArray);
                    if ($fact!=false) {
                        // guardamos el numero para impresion de factura
                        $_SESSION['dataInfo'][1]=$fact;
                        // reseteamos botones laterales
                        $clienteId=0;
                        $opcion=1;
                        // borramos opciones de formulario
                        $_SESSION['nameCustomer']="";
                        $dateInvoice=date("d-m-Y");
                        $numberInvoice="";
                        $_SESSION['dataWorksInvoice']=array();
                        $_SESSION['dataWorksInvoice']=$_SESSION['dataInvoice'];
                        unset($_SESSION['dataInvoice']);
                        $_SESSION['dataInvoice']=array(array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0));
                        $bs1=0;
                        $bs2=0;
                        $bs3=0;
                        $cu1=0;
                        $cu2=0;
                        $cu3=0;
                        $ttl=0; 
                        $controlButton1="";            
                        $controlButton2="";
                        $controlButton3="";                    
                        // mensaje informativo
                        $_SESSION['mensajeInvoice']="factura grabada correctamente";
                    } else {
                        $_SESSION['mensajeInvoice']="Error grabando factura";
                    }                      
                } else {
                    // estamos en la modificación de una factura
                    // hay que incorporar al objeto invoice el numero de factura
                    $newInvoice->setNumero($_REQUEST['codeinvoice']);
                    if (modifyThisInvoice($newInvoice,$dataArray)!=false) {
                        // modificada la factura con exito, procedemos al borrado de datos
                        // reseteamos botones laterales
                        $clienteId=0;
                        $opcion=1;
                        // borramos opciones de formulario
                        $_SESSION['nameCustomer']="";
                        $dateInvoice=date("d-m-Y");
                        $numberInvoice="";
                        $_SESSION['dataWorksInvoice']=array();
                        $_SESSION['dataWorksInvoice']=$_SESSION['dataInvoice'];                        
                        unset($_SESSION['dataInvoice']);
                        $_SESSION['dataInvoice']=array(array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0));
                        $bs1=0;
                        $bs2=0;
                        $bs3=0;
                        $cu1=0;
                        $cu2=0;
                        $cu3=0;
                        $ttl=0; 
                        $controlButton1="";            
                        $controlButton2="";
                        $controlButton3="";                    
                        // mensaje informativo
                        $_SESSION['mensajeInvoice']="factura modificada correctamente";                                             
                    } else {
                        $_SESSION['mensajeInvoice']="Error modificando factura";
                    }
                }            
            } else {
                $_SESSION['mensajeInvoice']="La factura no contiene albaranes";
            }          
        }




        // tomamos el valor del idcliente del selector de clientes
        if ($key=='customerInvoices' && $value!=0) { 
            $clienteId=$value;
        }         

        // tomamos el numero de la factura
        if ($key=="codeinvoice" && $value!="") {
            $numberInvoice=$value;
            // habilitamos el boton eliminar
            $controlButton3="";
            // desabilitamos el boton seleccionar facturas
            $controlButton2="disabled";
        }

        // tomamos el valor de la fecha introducida en formulario
        if ($key=='dateinvoice' && $value!="") { 
            $dateInvoice=$value;
        }



        // EN EL CASO DE TRABAJOS PARA FACTURAR
        // esto obtiene el trabajo para mostrarlo en el formulario
        if (substr($key,0,3)=='14/' || substr($key,0,3)=='15/') {

            // deshabilitamos el mensaje informativo
            $_SESSION['mensajeInvoice']="";

            // obtenemos el objeto trabajo
            $workclass=searchWorkclassToInvoice($key);
            if ($workclass!=false) {
                $idWork=$workclass->getId();
                $codigo=$workclass->getNumero();
                $texto=$workclass->getTexto();
                $cantidad=$workclass->getCantidad();
                $importe=$workclass->getImporte();
                $iva=$workclass->getIva();
                // creamos la matriz con los datos del formulario
                $dataWork=array($idWork,$codigo,$cantidad,$texto,$importe,$iva);
                // la añadimos a la matriz en memoria de la factura
                addTrabajo($dataWork);
                // calculamos totales de factura
                $totales=calculaFactura($_SESSION['dataInvoice']);
                $bs1=formato($totales[1]);
                $bs2=formato($totales[2]);
                $bs3=formato($totales[3]);
                $cu1=formato($totales[4]);
                $cu2=formato($totales[5]);
                $cu3=formato($totales[6]);
                $ttl=formato($totales[0]);                                 
            }
        }

        // EN EL CASO DE FACTURAS PARA MOSTRAR
        // esto obtiene la factura para mostrarla en el formulario
        if (substr($key,0,5)=='2014/' || substr($key,0,5)=='2015/') {

            // deshabilitamos el mensaje informativo
            $_SESSION['mensajeInvoice']="";
            // obtenemos el objeto factura
            $invoice=readInvoice($key, $_SESSION['workingCompany']);
            if ($invoice!=false) {
                $idInv=$invoice->getId();
                $numberInvoice=$invoice->getNumero();
                $dateInvoice=convertDateToSpanish($invoice->getFecha());
                $bs1=formato($invoice->getBaseimponible1());
                $bs2=formato($invoice->getBaseimponible2());
                $bs3=formato($invoice->getBaseimponible3());
                $cu1=formato($invoice->getCuotaiva1());
                $cu2=formato($invoice->getCuotaiva2());
                $cu3=formato($invoice->getCuotaiva3());
                $ttl=formato($invoice->getTotal()); 
                $_SESSION['formaDpago']=$invoice->getFormapago();
                $vencimiento=' Vto: '.$invoice->getVencimiento();
            }

            // obtenemos la lista de los trabajos de la factura
            $arrayOfWorks=readRelationships($numberInvoice, $_SESSION['workingCompany']);
            if ($arrayOfWorks!=false) {
                // obtenemos el detalle de los trabajos de la factura
                unset($_SESSION['dataInvoice']);
                $_SESSION['dataInvoice']=array(array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0));
                foreach ($arrayOfWorks as $value) {
                    // leemos el trabajo
                    $workR=readWork($value,$_SESSION['workingCompany']);
                    if ($workR!=false) {
                        // obtenemos los datos necesarios
                        $idWork=$workR->getId();
                        $codigo=$workR->getNumero();
                        $texto=$workR->getTexto();
                        $cantidad=$workR->getCantidad();
                        $importe=$workR->getImporte();
                        $iva=$workR->getIva();
                        // creamos la matriz con los datos del formulario
                        $dataWork=array($idWork,$codigo,$cantidad,$texto,$importe,$iva);
                        // la añadimos a la matriz en memoria de la factura
                        addTrabajo($dataWork);
                    }
                }
            }

            // INFORMACION PARA LA IMPRESION DE LA FACTURA, YA QUE HA SIDO HABILITADA
                $_SESSION['dataWorksInvoice']=array();
                $_SESSION['dataWorksInvoice']=$_SESSION['dataInvoice'];            
                $_SESSION['dataInfo']=array("","","","");
                $_SESSION['dataInfo'][0]=$dateInvoice;
                $_SESSION['dataInfo'][1]=$numberInvoice;
                $_SESSION['dataInfo'][2]=getNameFormaPago($_SESSION['formaDpago'],$_SESSION['workingCompany']);
                $_SESSION['dataInfo'][3]=$vencimiento;
                $_SESSION['dataAmounts']=array(0,0,0,0,0,0,0);    
                $_SESSION['dataAmounts'][0]=$bs1;
                $_SESSION['dataAmounts'][1]=$cu1;
                $_SESSION['dataAmounts'][2]=$bs2;
                $_SESSION['dataAmounts'][3]=$cu2;
                $_SESSION['dataAmounts'][4]=$bs3;
                $_SESSION['dataAmounts'][5]=$cu3;
                $_SESSION['dataAmounts'][6]=$ttl;
        }
 
    }

    // deshabilitamos el boton de eliminar si no tiene numero de factura
    if (!strlen($numberInvoice)>0) {
        $controlButton3="disabled";
    }
