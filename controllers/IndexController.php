<?php
/**

    
    * * * * * IndexController es el controlador de la vista index.php *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
    include_once 'Init.php';
    session_start();
    error_reporting(E_ALL & E_WARNING);
    ini_set('display_errors', '1');

    // lectura de inicio de sesion y variables
    generateEnvironment();

    $dir="../index.php";
    
    // las entradas son redirigidas por el frontcontroller
    // solo se admiten las peticiones POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['login'])) {
            $login=depura_input($_POST['login']);
        }
        if (isset($_POST['pass'])) {
            $pass=depura_input($_POST['pass']);
        }
        if ($login=='anonimo' && $pass=='anonimo') {
            $_SESSION['OK']="OK";
            $dir="../mainWorks.php";
        }
        redirect($dir);
        
    } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // control de peticiones, get se redirigen hacia error
        redirect("../error404.php");
    }


    
    /*
    Método depurador de las entradas por formulario para evitar inyeccion de codigo o XSS
    */
    function depura_input($data) {
        
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      
      return $data;
     }

     /*
        Método redireccionador
    */ 

    function redirect($url, $statusCode = 303) {
        header('Location: ' . $url, true, $statusCode);
        die();
    }
    


