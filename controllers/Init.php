<?php
/**

    
    * * * * * Init es el un fichero biblioteca inicializador de la aplicación *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
session_start();

function generateEnvironment() {

    // empresa en la que estamos trabajando
    $_SESSION['workingCompany']=1;
    
    // mensajes en las pantallas
    $_SESSION['mensajeCust']="";
    $_SESSION['mensajeWork']="";
    $_SESSION['mensajeInvoice']="";
    
    // control de usuario conectado mediante user y password
    $_SESSION['OK']="";     // valor "OK" cuando se conecte

    // tipos de iva vigentes
    $_SESSION['ivaTipo1']=7.00;
    $_SESSION['ivaTipo2']=10.00;
    $_SESSION['ivaTipo3']=21.00;

    // datos internos de la factura 
    // es un array de 5 trabajos x 6 datos
    $_SESSION['dataInvoice']=array(array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0),array(0,'',0,'',0,0));
}

