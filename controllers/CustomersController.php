<?php
/**

    
    * * * * * CustomersController es el controlador de la vista mainCustomers.php *** *
    * * @Author musef v.1.0 2015-08-01
    

*/

session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
    include_once 'controllers/WorksDAO.php';
    include_once 'controllers/FormasPagoDAO.php';
    include_once 'controllers/CustomersDAO.php';
    include_once 'controllers/CustomersComponent.php';

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
       

    // variable de busqueda por cliente
    $customerSearched="";


    // variables del formulario
    $idCust=0;
    $name=""; 
    $address="";
    $cpost="";
    $city="";
    $nif="";
    $formaPago=0;


    // lectura de los valores REQUEST
    foreach ($_REQUEST as $key => $value) {

        // ***** ZONA DE LECTURA DE BOTONES DEL FORMULARIO
        if ($key=='borrar') {
            // borramos los datos del formulario, y se habilitan
            // todas las casillas y botones
		    $idCust=0;
		    $name=""; 
		    $address="";
		    $cpost="";
		    $city="";
		    $nif="";
		    $formaPago=0;
            $_SESSION['mensajeCust']="";
        }

        if ($key=='eliminar') {
            // borramos los datos del formulario, y se habilitan
            // todas las casillas y botones
            $idCust=depura_input($_REQUEST['idcust']);
            if ($idCust>0) {
                // si hay un id, hay que borrar el objeto
                $response=deleteThisCustomer($idCust);

                if ($response!=false) {
                    // si retorna la lista, cambiamos la lista anterior
                    // por la nueva lista sin el objeto borrado
				    $idCust=0;
                    $_REQUEST['idcust']='0';
				    $name=""; 
				    $address="";
				    $cpost="";
				    $city="";
				    $nif="";
				    $formaPago=0;
                    $_SESSION['mensajeCust']="Cliente eliminado";
                } else {
                    $_SESSION['mensajeCust']="No es posible eliminar este cliente: tiene trabajos";
                } 
            }
        }

        if ($key=='grabar') {

            // recuperamos los datos de formulario y los grabamos
            $idCust=depura_input($_REQUEST['idcust']);
            $name=depura_input($_REQUEST['namecust']);
            $address=depura_input($_REQUEST['addresscust']);
            $cpost=depura_input($_REQUEST['cpostcust']);
            $city=depura_input($_REQUEST['citycust']);
            $nif=depura_input($_REQUEST['nifcust']);
            $form=depura_input($_REQUEST['formaPago']);
            $formaPago=getIdFormaPago($form,$_SESSION['workingCompany']);
            //$formaPago=0;
            if ($idCust>0) {
                // es una modificacion
                $changedCustomer=new CustomerClass($idCust,$name,$address,$cpost,$city,$nif,$formaPago);
                $changedCustomer->setId($idCust);
                if (modifyThisCustomer($changedCustomer)==true) {
                    // modificacion correcta, borramos formulario                   
                    $idCust=0;
                    $_REQUEST['idcust']='0';
                    $name=""; 
                    $address="";
                    $cpost="";
                    $city="";
                    $nif="";
                    $formaPago=0;
                    $_SESSION['mensajeCust']="modificación efectuada";                    
                } else {
                    // modificacion no efectuada
                    $_SESSION['mensajeCust']="No ha sido posible efectuar la modificación";
                }
            } else {
                // es una nuevo trabajo
                // creamos el objeto work y lo grabamos
                $newCustomer=new CustomerClass($_SESSION['workingCompany'],$name,$address,$cpost,$city,$nif,$formaPago);
                if (recordThisCustomer($newCustomer)) {
                    // grabación efectuada, borramos formulario
                    $idCust=0;
                    $_REQUEST['idcust']='0';
                    $name=""; 
                    $address="";
                    $cpost="";
                    $city="";
                    $nif="";
                    $formaPago=0;
                    $_SESSION['mensajeCust']="grabación efectuada";
                    $_REQUEST['namecust']="";                  
                } else {
                    $_SESSION['mensajeCust']="No ha sido posible grabar el cliente";
                }

            }           
        }       


        // tomamos el valor del selector de clientes
        if ($key=='customer' && $value!='Seleccione cliente') { 
            // obtenemos el nombre del cliente
            $customerSearched=$value;
            // obtenemos el objeto cliente
            $customer=searchCustomerclassByName($customerSearched);
            // datos de formulario segun cliente seleccionado
            if ($customer!=false) {
                $idCust=$customer->getId();
                $name=$customer->getNombre(); 
                $address=$customer->getDireccion();
                $cpost=$customer->getCodPostal();
                $city=$customer->getLocalidad();
                $nif=$customer->getNif();
                $formaPago=$customer->getFormapago();                
            }
        } 
        // boton seleccion de cliente
        if ($key=='Sel') {       
            // borramos mensajeCustCust
            $_SESSION['mensajeCust']="";
        }

    }

    $usable="";
    // deshabilitamos el boton de eliminar
    if (!strlen($name)>0) {
        $usable='disabled';
    }


?>
