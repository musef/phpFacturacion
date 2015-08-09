<?php
/**

    
    * * * * * ListController es el controlador de la vista mainList.php *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

    include_once 'controllers/WorksDAO.php';
    include_once 'controllers/FormasPagoDAO.php';
    include_once 'controllers/CustomersDAO.php';
    include_once 'controllers/InvoicesDAO.php';    
    include_once 'controllers/CustomersComponent.php';
    include_once 'controllers/ListComponent.php';
    include_once 'models/InvoiceClass.php';

    /**
        NOTA IMPORTANTE DEL FUNCIONAMIENTO DE ESTE CONTROLADOR:
        El controlador no redirecciona hacia ninguna página. Solamente recibe los datos del
        formulario y construye una serie de arrays $_SESSION con la información a mostrar en
        el listado.
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


    $infoClientes="";
    $_SESSION['tituloListado']="";
    $_SESSION['subtituloListado']="";
    $_SESSION['cuerpoListado']=array(array());
    $cli1="";
    $cli2="";
    $fech1="";
    $fech2="";
    $direc="";
    $amounts="";
    $order="";
    if (isset($_REQUEST['tab'])) {
        $newTab=$_REQUEST['tab'];
    } else {
        $newTab="";
    }
    if (!isset($_SESSION['opcionFiltro'])) {
        $_SESSION['opcionFiltro']=0;
    }
    

        // lectura de los valores REQUEST
    foreach ($_REQUEST as $key => $value) {
        //echo $key.' - '.$value.' // ';
        // Lectura de los filtros de formulario
        if ($key=='cliIniC') {
            $cli1=$value;
        }
        if ($key=='cliFinC') {
            $cli2=$value;
        }        
        if ($key=='fechIniC') {
            $fech1=$value;
        }
        if ($key=='fechFinC') {
            $fech2=$value;
        }        
        if ($key=='direcC') {
            $direc=$value;
        }        
        if ($key=='amountsC') {
            $amounts=$value;
        } 
        if ($key=='orderC') {
            $order=$value;
        }             
        if ($key=='cliIniF') {
            $cli1=$value;
        }
        if ($key=='cliFinF') {
            $cli2=$value;
        }        
        if ($key=='fechIniF') {
            $fech1=$value;
        }
        if ($key=='fechFinF') {
            $fech2=$value;
        }        
        if ($key=='orderF') {
            $order=$value;
        }        

        if ($key=='cliIniW') {
            $cli1=$value;
        }
        if ($key=='cliFinW') {
            $cli2=$value;
        }        
        if ($key=='fechIniW') {
            $fech1=$value;
        }
        if ($key=='fechFinW') {
            $fech2=$value;
        }        
        if ($key=='orderW') {
            $order=$value;
        }

        // ***** ZONA DE LECTURA DE BOTONES DEL FORMULARIO
        if ($key=='listarC') {
            $_SESSION['tituloListado']="Listado de datos de Clientes";
            $_SESSION['subtituloListado']='Desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;

            if ($cli1=='Cliente inicial') {
                $cl1="000000AA";
            } else {
                $cl1=$cli1;
            }
            if ($cli2=='Cliente final') {
                $cl2="zzzzzzzzz";
            } else {
                $cl2=$cli2;
            }
            if ($fech1=='Fecha inicial') {
                $f1="01-2014";
            } else {
                $f1=$fech1;
            }
            if ($fech2=='Fecha actual') {
                $f2="12-2999";
            } else {
                $f2=$fech2;
            }
            if ($direc=='Con direcciones') {
                $d=1;
            } else {
                $d=2;
            } 
            if ($amounts=='Sin importes') {
                $a=1;
            } elseif ($amounts=='Con trabajos') {
                $a=3;
            } else {
                $a=2; 
            }
            if ($order=='Orden alfabético') {
                $o=1;
            } else {
                $o=2;
            }

            if ($a==1) {
                // listado de clientes sin importes y por orden alfabetico
                if ($d==1) {
                    // con datos direccion
                    $infoClientes=listOnlyCustomersData($cl1,$cl2);
                    $_SESSION['subtituloListado']='Desde '.$cli1.' hasta '.$cli2;
                } else {
                    // solo nombres y nif
                    $infoClientes=listOnlyCustomersNames($cl1,$cl2);
                    $_SESSION['subtituloListado']='Desde '.$cli1.' hasta '.$cli2;
                }
            }

            if ($a==2 && $o==1) {
                // listado de clientes con facturacion y por orden alfabetico
                if ($d==1) {
                    // con datos direccion
                    $infoClientes=listCustomersDataAndInvoices($cl1,$cl2,$f1,$f2);
                    $_SESSION['subtituloListado']='Facturación desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;
                } else {
                    // solo nombres y nif
                    $_SESSION['subtituloListado']='Facturación desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;
                    $infoClientes=listCustomersNamesAndInvoices($cl1,$cl2,$f1,$f2);
                }
            } elseif ($a==2 && $o==2) {
                // listado de clientes con facturacion y por orden de importes
                if ($d==1) {
                    // con datos direccion
                    $_SESSION['subtituloListado']='Facturación desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;
                    $infoClientes=listCustomersDataAndInvoicesOrderAmounts($cl1,$cl2,$f1,$f2);
                } else {
                    // solo nombres y nif
                    $_SESSION['subtituloListado']='Facturación desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;                    
                    $infoClientes=listCustomersNamesAndInvoicesOrderAmounts($cl1,$cl2,$f1,$f2);
                }                
            }

            if ($a==3 && $o==1) {
                // listado de clientes con trabajos y por orden alfabetico
                if ($d==1) {
                    // con datos direccion 
                    $_SESSION['subtituloListado']='Trabajos desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;                                     
                    $infoClientes=listCustomersDataAndWorks($cl1,$cl2,$f1,$f2);
                } else {
                    // solo nombres y nif
                    $_SESSION['subtituloListado']='Trabajos desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;                    
                    $infoClientes=listCustomersNamesAndWorks($cl1,$cl2,$f1,$f2);
                }
            }  elseif ($a==3 && $o==2) {
                // listado de clientes con trabajos y por orden de importes
                if ($d==1) {
                    // con datos direccion
                    $_SESSION['subtituloListado']='Trabajos desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;
                    $infoClientes=listCustomersDataAndWorksOrderAmounts($cl1,$cl2,$f1,$f2);
                } else {
                    // con datos direccion 
                    $_SESSION['subtituloListado']='Trabajos desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;
                    $infoClientes=listCustomersNamesAndWorksOrderAmounts($cl1,$cl2,$f1,$f2);
                   
                }                
            }                   
            $_SESSION['opcionFiltro']=0;
            $_SESSION['cuerpoListado']=$infoClientes;
            $newTab="OK";
            //redirectList();
        }

        if ($key=='listarF') {

            $_SESSION['tituloListado']="Listado de datos de Facturas";
            $_SESSION['subtituloListado']='Desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;

            if ($cli1=='Cliente inicial') {
                $cl1="000000AA";
            } else {
                $cl1=$cli1;
            }
            if ($cli2=='Cliente final') {
                $cl2="zzzzzzzzz";
            } else {
                $cl2=$cli2;
            }
            if ($fech1=='Fecha inicial') {
                $f1="01-2014";
            } else {
                $f1=$fech1;
            }
            if ($fech2=='Fecha actual') {
                $f2="12-2999";
            } else {
                $f2=$fech2;
            }
            if ($order=='Agrupado') {
                $_SESSION['subtituloListado']='Facturación desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;
                $infoClientes=listCustomersNamesAndInvoices($cl1,$cl2,$f1,$f2);
            } else {
                $_SESSION['subtituloListado']='Facturación desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;
                $infoClientes=listInvoices($cl1,$cl2,$f1,$f2);
            }

            $_SESSION['opcionFiltro']=1;
            $_SESSION['cuerpoListado']=$infoClientes;
            $newTab="OK";
            //redirectList();
        }


        if ($key=='listarW') {

            $_SESSION['tituloListado']="Listado de datos de Trabajos";
            $_SESSION['subtituloListado']='Desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;

            if ($cli1=='Cliente inicial') {
                $cl1="000000AA";
            } else {
                $cl1=$cli1;
            }
            if ($cli2=='Cliente final') {
                $cl2="zzzzzzzzz";
            } else {
                $cl2=$cli2;
            }
            if ($fech1=='Fecha inicial') {
                $f1="01-2014";
            } else {
                $f1=$fech1;
            }
            if ($fech2=='Fecha actual') {
                $f2="12-2999";
            } else {
                $f2=$fech2;
            }
            if ($order=='Agrupado') {
                $_SESSION['subtituloListado']='Trabajos desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;
                $infoClientes=listWorksNameCustomers($cl1,$cl2,$f1,$f2);
            } else {
                $o=2;
                $_SESSION['subtituloListado']='Trabajos desde '.$cli1.' hasta '.$cli2.' - De '.$fech1.' al '.$fech2;
                $infoClientes=listWorks($cl1,$cl2,$f1,$f2);                
            }
            $_SESSION['opcionFiltro']=2;
            $_SESSION['cuerpoListado']=$infoClientes;
            $newTab="OK";
            //redirectList();
        }        
    }