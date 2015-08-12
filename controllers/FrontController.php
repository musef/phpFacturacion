<?php
/**

    
    * * * * * FrontController es el controlador central de la aplicación *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
session_start();
    function redirect($url, $statusCode = 303) {
        header('Location: ' . $url, true, $statusCode);
        die();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // borramos los mensajes inter-paginas
        $_SESSION['mensajeCust']="";
        $_SESSION['mensajeWork']="";
        $_SESSION['mensajeInvoice']="";

        // borrado de SESSION en cambios de menu
        if (isset($_SESSION['dataInvoice'])) unset($_SESSION['dataInvoice']);
        if (isset($_SESSION['dataWorksInvoice'])) unset($_SESSION['dataWorksInvoice']);
        if (isset($_SESSION['dataInfo'])) unset($_SESSION['dataInfo']);
        if (isset($_SESSION['dataAmounts'])) unset($_SESSION['dataAmounts']);
        if (isset($_SESSION['dataCustomer'])) unset($_SESSION['dataCustomer']);
        if (isset($_SESSION['nameCustomer'])) unset($_SESSION['nameCustomer']);
        if (isset($_SESSION['formaDpago'])) unset($_SESSION['formaDpago']);
        if (isset($_SESSION['grab'])) unset($_SESSION['grab']);
        if (isset($_SESSION['dataWork'])) unset($_SESSION['dataWork']);
        if (isset($_SESSION['grabW'])) unset($_SESSION['grabW']);
        if (isset($_SESSION['tituloListado'])) unset($_SESSION['tituloListado']);
        if (isset($_SESSION['subtituloListado'])) unset($_SESSION['subtituloListado']);
        if (isset($_SESSION['cuerpoListado'])) unset($_SESSION['cuerpoListado']);
        if (isset($_SESSION['cabeceraListado'])) unset($_SESSION['cabeceraListado']);
        if (isset($_SESSION['opcionFiltro'])) unset($_SESSION['opcionFiltro']);
        
        
        $dir="../error404.php";
        if(isset($_POST['trabajos'])) {
            $dir="../mainWorks.php";
        } else if(isset($_POST['facturas'])) {
            $dir="../mainInvoices.php";
        } else if(isset($_POST['clientes'])) {
            $dir="../mainCustomers.php";
        } else if(isset($_POST['listados'])) {
            $dir="../mainList.php";
        } else if(isset($_POST['admon'])) {
            $dir="../mainAdministracion.php";
        } else if(isset($_POST['exit'])) {
            $dir="../index.php";
        }
        
        redirect($dir);
    } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // control de peticiones, get se redirigen hacia error
        redirect("../error404.php");
    }