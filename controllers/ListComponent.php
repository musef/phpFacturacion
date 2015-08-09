<?php
/**

    
    * * * * * ListComponent es una biblioteca de funciones para ListController *** *
    * * @Author musef v.1.0 2015-08-01


*/


/**
    Este método retorna la lista de los nombres de los clientes en formato select de html
*/
function getListCustomersToList($option) {
    $data="";
    $listaclientes=getListCustomers($_SESSION['workingCompany']) ;
    if ($listaclientes!=false) {
        foreach ($listaclientes as $key => $value) {
            // añadimos uno a uno
            if ($value->getId()==$option) {
                $data=$data.'<option selected>'.$value->getNombre().'</option>';
            } else {
                $data=$data.'<option>'.$value->getNombre().'</option>';
            }      
        }        
    }
    echo $data;
}


/**
    Este metodo devuelve el listado de las fechas en formato mm-yyyy, desde 01-2014 hasta la fecha
    actual del sistema. Este listado es para construir las options de un select en html
*/
function getListFechas() {
	$mesactual=date("m");
	$anoactual=date("Y");
	$year=2014;
    $data="";
	for ($year=2014;$year<$anoactual;$year++) {
		for ($month=1; $month < 13 ; $month++) {
			if ($month<10) {
				$month='0'.$month;
			}
			$data=$data.'<option>'.$month.'-'.$year.'</option>';
		}		
	}
	for ($month=1; $month <= $mesactual ; $month++) {
		if ($month<10) {
			$month='0'.$month;
		}
		$data=$data.'<option>'.$month.'-'.$anoactual.'</option>';
	}	
	echo $data;	
}


/**
    Este metodo devuelve un array con la cantidad facturada, con iva y sin iva, de un cliente determinado.
    Si no hay datos, devuelve un array vacio
*/
function getAllInvoicesByCustomer($idCustomer,$fech1,$fech2) {

    // transformamos las fechas recibidas
    $f1=substr($fech1, 3).'-'.substr($fech1,0,2).'-01';
    $f2=substr($fech2, 3).'-'.substr($fech2,0,2).'-31';

    $base=0;
    $total=0;
    $data=array(0,0);

    // leemos en la base de datos
    $invoicesList=getListInvoicesByCustomer($idCustomer,$f1,$f2,$_SESSION['workingCompany']);
    if ($invoicesList==false) {
        // no hay datos, devuelve un array vacio
        return $data;
    }

    foreach ($invoicesList as $key => $value) {
        $base=$base+$value->getBaseImponible1()+$value->getBaseimponible2()+$value->getBaseimponible3();
        $total=$total+$value->getTotal();
    }
     // añadiendo los datos
    $data[0]=$base;
    $data[1]=$total;

    return $data;
}


/**
    Este metodo devuelve un array con los importes de los trabajos, con iva y sin iva, 
    de un cliente determinado. Devuelve array con valor cero si no hay nada .
*/
function getAllWorksByCustomer($idCustomer,$fech1,$fech2) {
    
    // transformamos las fechas recibidas
    $f1=substr($fech1, 3).'-'.substr($fech1,0,2).'-01';
    $f2=substr($fech2, 3).'-'.substr($fech2,0,2).'-31';

    $base=0;
    $total=0;
    $data=array();
    
    // hace la busqueda de los datos en sql    
    $worksList=getWorksByCustomerAndDates($_SESSION['workingCompany'],$idCustomer,$f1,$f2);

    if ($worksList==false) {
        // no ha encontrado datos
        $data[0]=0;
        $data[1]=0;
        return $data;
    }

    // recorremos todas las facturas una a una
    foreach ($worksList as $key => $value) {
        
        //if ($key=='base') $base=$base+$value;
        //if ($key=='total') $total=$total+$value;    
        $base=$base+$value[0];
        $total=$total+$value[1];
    }
    // añadiendo los datos
    $data[0]=$base;
    $data[1]=$total;

    return $data;
}


/**
    Este metodo realizar una ordenacion de la lista recibida $list por importe total
*/
function orderByAmount($list) {
     // Sort the multidimensional array
     usort($list, "amount_sort");
     // Define the custom sort function

    return $list;
}


 function amount_sort($a,$b) {
      return $a[0]<$b[0];
 }


/**
    Esta funcion devuelve el numero formateado en formato texto español
*/
function formato($num) {
    return number_format($num, 2, ',', '.');
}

/* ************** ZONA DE FUNCIONES DE RETORNOS DE TABLAS HTML DE VISUALIZACION DE RESULTADOS DE BUSQUEDA */

/**
    Listado clientes
*/
function listOnlyCustomersData($custIni,$custFin) {

    $_SESSION['cabeceraListado']=array("NOMBRE","DIRECCIÓN","COD.POSTAL","LOCALIDAD","N.I.F.");
    $_SESSION['cuerpoListado']=array();
    // obtenemos toda la lista de clientes
    $listaclientes=getListCustomers($_SESSION['workingCompany']);
    if ($listaclientes!=false) {
        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // añadimos uno a uno
                $arrayData=array($value->getNombre(),$value->getDireccion(),'- '.$value->getCodPostal(),$value->getLocalidad(),$value->getNif());
                $_SESSION['cuerpoListado'][]=$arrayData; 
            }
        }
    } 
    return $_SESSION['cuerpoListado'];
}


/**
    Listado clientes
*/
function listOnlyCustomersNames($custIni,$custFin) {

    $_SESSION['cabeceraListado']=array("NOMBRE","N.I.F.");
    $_SESSION['cuerpoListado']=array();
    // obtenemos toda la lista de clientes
    $listaclientes=getListCustomers($_SESSION['workingCompany']);
    if ($listaclientes!=false) {
        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // añadimos uno a uno     
                $arrayData=array($value->getNombre(),$value->getNif());
                $_SESSION['cuerpoListado'][]=$arrayData;  
            }
        }
    }
    return $_SESSION['cuerpoListado'];
}


/**
    Listado clientes con facturación
*/
function listCustomersDataAndInvoices($custIni,$custFin,$fech1,$fech2) {

    $_SESSION['cabeceraListado']=array("NOMBRE","DIRECCIÓN","COD.POSTAL","LOCALIDAD","N.I.F.","TOTAL BASES","TOTAL FACTURAS");    
    $_SESSION['cuerpoListado']=array();
    // obtenemos toda la lista de clientes
    $listaclientes=getListCustomers($_SESSION['workingCompany']);
    $invoicesList=getListInvoices($_SESSION['workingCompany']);

    $acumulBase=0;
    $acumulTotal=0;
    if ($listaclientes!=false) {
        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // primero tomaremos los importes de cada cliente
                $arrayImportes=getAllInvoicesByCustomer($value->getId(),$fech1,$fech2);
                if ($arrayImportes[0]>0) {
                    // solo incluimos en el listado los clientes que tienen bases o sea, que tienen
                    // trabajos realizados en ese intervalo de fechas
                    $aux=array();
                    // primero colocamos en el array total facturas porque es la columna a ordenar
                    $aux[6]=$arrayImportes[1];
                    $acumulTotal=$acumulTotal+$arrayImportes[1];           
                    // bases
                    $aux[5]=$arrayImportes[0];
                    $acumulBase=$acumulBase+$arrayImportes[0];
                    //datos
                    $aux[0]=$value->getNombre();
                    $aux[1]=$value->getDireccion();
                    $aux[2]='- '.$value->getCodPostal();
                    $aux[3]=$value->getLocalidad();
                    $aux[4]=$value->getNif();
                    
                    $_SESSION['cuerpoListado'][]=$aux;
                }
            }
        }
    }

    $dt0=array('','','','','','________________','________________');
    $dt1=array('','','','Sumas.....','',$acumulBase,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;

    return $_SESSION['cuerpoListado'];
}


/**
    Listado clientes con facturación
*/
function listCustomersNamesAndInvoices($custIni,$custFin,$fech1,$fech2) {

    $_SESSION['cabeceraListado']=array("NOMBRE","N.I.F.","TOTAL BASES","TOTAL FACTURAS");    
    $_SESSION['cuerpoListado']=array();
    // obtenemos toda la lista de clientes
    $listaclientes=getListCustomers($_SESSION['workingCompany']);
    $invoicesList=getListInvoices($_SESSION['workingCompany']);

    $acumulBase=0;
    $acumulTotal=0;
    if ($listaclientes!=false) {

        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // primero tomaremos los importes de cada cliente
                $arrayImportes=getAllInvoicesByCustomer($value->getId(),$fech1,$fech2);
                if ($arrayImportes!=false) {
                    if ($arrayImportes[0]>0) {
                        // solo incluimos en el listado los clientes que tienen bases o sea, que tienen
                        // trabajos realizados en ese intervalo de fechas
                        $aux=array();
                        // primero colocamos en el array total facturas porque es la columna a ordenar
                        $aux[3]=$arrayImportes[1];
                        $acumulTotal=$acumulTotal+$arrayImportes[1];           
                        // bases
                        $aux[2]=$arrayImportes[0];
                        $acumulBase=$acumulBase+$arrayImportes[0];
                        //datos
                        $aux[0]=$value->getNombre();
                        $aux[1]=$value->getNif();
                        
                        $_SESSION['cuerpoListado'][]=$aux;
                    }                    
                }
            }
        }

    }

    $dt0=array('','','________________','________________');
    $dt1=array('Sumas.....','',$acumulBase,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;

    return $_SESSION['cuerpoListado'];
}


/**
    Listado clientes con facturación
*/
function listCustomersDataAndInvoicesOrderAmounts($custIni,$custFin,$fech1,$fech2) {

    $_SESSION['cabeceraListado']=array("NOMBRE","DIRECCIÓN","COD.POSTAL","LOCALIDAD","N.I.F.","TOTAL BASES","TOTAL FACTURAS"); 
    $_SESSION['cuerpoListado']=array();  
    // obtenemos toda la lista de clientes
    $listaclientes=getListCustomers($_SESSION['workingCompany']);
    $invoicesList=getListInvoices($_SESSION['workingCompany']);

    $listToReturn=array();
    $acumulBase=0;
    $acumulTotal=0;
    if ($listaclientes!=false) {
        
        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // primero tomaremos los importes de cada cliente

                $arrayImportes=getAllInvoicesByCustomer($value->getId(),$fech1,$fech2);
                if ($arrayImportes[0]>0) {
                    // solo incluimos en el listado los clientes que tienen bases o sea, que tienen
                    // trabajos realizados en ese intervalo de fechas
                    $aux=array();
                    // primero colocamos en el array total facturas porque es la columna a ordenar
                    $aux[0]=$arrayImportes[1];
                    $acumulTotal=$acumulTotal+$arrayImportes[1];           
                    // bases
                    $aux[1]=$arrayImportes[0];
                    $acumulBase=$acumulBase+$arrayImportes[0];

                    $aux[2]=$value->getNombre();
                    $aux[3]=$value->getDireccion();
                    $aux[4]='- '.$value->getCodPostal();
                    $aux[5]=$value->getLocalidad();
                    $aux[6]=$value->getNif();
                    $listToReturn[]=$aux;
                }
            }
        }
        // ordenamos por importes totales decrecientes

        $listToReturn=orderByAmount($listToReturn);
       // construimos el return
        for ($i=0; $i < count($listToReturn) ; $i++) { 
            $_SESSION['cuerpoListado'][$i][0]=$listToReturn[$i][2];         
            $_SESSION['cuerpoListado'][$i][1]=$listToReturn[$i][3]; 
            $_SESSION['cuerpoListado'][$i][2]=$listToReturn[$i][4]; 
            $_SESSION['cuerpoListado'][$i][3]=$listToReturn[$i][5]; 
            $_SESSION['cuerpoListado'][$i][4]=$listToReturn[$i][6]; 
            $_SESSION['cuerpoListado'][$i][5]=$listToReturn[$i][1];  
            $_SESSION['cuerpoListado'][$i][6]=$listToReturn[$i][0]; 
        }

    }    

    $dt0=array('','','','','','________________','________________');
    $dt1=array('','','','Sumas.....','',$acumulBase,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;

    return $_SESSION['cuerpoListado']; 
}


/**
    Listado clientes con facturación
*/
function listCustomersNamesAndInvoicesOrderAmounts($custIni,$custFin,$fech1,$fech2) {

    $_SESSION['cabeceraListado']=array("NOMBRE","N.I.F.","TOTAL BASES","TOTAL FACTURAS");   
    $_SESSION['cuerpoListado']=array();
    $listaclientes=getListCustomers($_SESSION['workingCompany']);
    $invoicesList=getListInvoices($_SESSION['workingCompany']);    
    $listToReturn=array();
    $acumulBase=0;
    $acumulTotal=0;    

    if ($listaclientes!=false) {
     
        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // primero tomaremos los importes de cada cliente
                $arrayImportes=getAllInvoicesByCustomer($value->getId(),$fech1,$fech2);
                if ($arrayImportes[0]>0) {
                        // solo incluimos en el listado los clientes que tienen bases o sea, que tienen
                        // trabajos realizados en ese intervalo de fechas
                    $aux=array();
                    // primero colocamos en el array total facturas porque es la columna a ordenar
                    $aux[0]=$arrayImportes[1];
                    $acumulTotal=$acumulTotal+$arrayImportes[1];           
                    // bases
                    $aux[1]=$arrayImportes[0];
                    $acumulBase=$acumulBase+$arrayImportes[0];

                    $aux[2]=$value->getNombre();
                    $aux[3]=$value->getNif();
                    $listToReturn[]=$aux;
                }
            }
        }
        // ordenamos por importes totales decrecientes
        $listToReturn=orderByAmount($listToReturn);
       // construimos el return
        for ($i=0; $i < count($listToReturn) ; $i++) { 
            $_SESSION['cuerpoListado'][$i][0]=$listToReturn[$i][2];         
            $_SESSION['cuerpoListado'][$i][1]=$listToReturn[$i][3]; 
            $_SESSION['cuerpoListado'][$i][2]=$listToReturn[$i][1];  
            $_SESSION['cuerpoListado'][$i][3]=$listToReturn[$i][0 ]; 
        }

    }

    $dt0=array('','','________________','________________');
    $dt1=array('Sumas.....','',$acumulBase,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;

    return $_SESSION['cuerpoListado'];
}


/**
    Listado clientes con trabajos
*/
function listCustomersDataAndWorks($custIni,$custFin,$fech1,$fech2) {

   
    $_SESSION['cabeceraListado']=array("NOMBRE","DIRECCIÓN","COD.POSTAL","LOCALIDAD","N.I.F.","TOTAL BASES","TOTAL TRABAJOS");    
    $_SESSION['cuerpoListado']=array();  
   
    $listaclientes=getListCustomers($_SESSION['workingCompany']);

    $acumulBase=0;
    $acumulTotal=0; 

    if ($listaclientes!=false) {
        
        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // primero buscaremos los importes de cada cliente
                $arrayImportes=getAllWorksByCustomer($value->getId(),$fech1,$fech2);
                if ($arrayImportes[0]>0) {
                    // solo incluimos en el listado los clientes que tienen bases o sea, que tienen
                    // trabajos realizados en ese intervalo de fechas

                    $aux=array();
                    // primero colocamos en el array total trabajos porque es la columna a ordenar
                    $aux[6]=$arrayImportes[1];
                    $acumulTotal=$acumulTotal+$arrayImportes[1];           
                    // bases
                    $aux[5]=$arrayImportes[0];
                    $acumulBase=$acumulBase+$arrayImportes[0];

                    $aux[0]=$value->getNombre();
                    $aux[1]=$value->getDireccion();
                    $aux[2]='- '.$value->getCodPostal();
                    $aux[3]=$value->getLocalidad();
                    $aux[4]=$value->getNif();

                    $_SESSION['cuerpoListado'][]=$aux;
                }
            }
        }

    }    

    $dt0=array('','','','','','________________','________________');
    $dt1=array('','','','Sumas.....','',$acumulBase,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;

    return $_SESSION['cuerpoListado'];
}


/**
    Listado clientes con trabajos
*/
function listCustomersNamesAndWorks($custIni,$custFin,$fech1,$fech2) {

    $_SESSION['cabeceraListado']=array("NOMBRE","N.I.F.","TOTAL BASES","TOTAL TRABAJOS");  
    $_SESSION['cuerpoListado']=array(); 

    $listaclientes=getListCustomers($_SESSION['workingCompany']) ;

    $acumulBase=0;
    $acumulTotal=0;     
    
    if ($listaclientes!=false) {
        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // primero tomaremos los importes de cada cliente
                $arrayImportes=getAllWorksByCustomer($value->getId(),$fech1,$fech2);
                if ($arrayImportes[0]>0) {
                    // solo incluimos en el listado los clientes que tienen bases o sea, que tienen
                    // trabajos realizados en ese intervalo de fechas

                    $aux=array();
                    // primero colocamos en el array total trabajos porque es la columna a ordenar
                    $aux[3]=$arrayImportes[1];
                    $acumulTotal=$acumulTotal+$arrayImportes[1];           
                    // bases
                    $aux[2]=$arrayImportes[0];
                    $acumulBase=$acumulBase+$arrayImportes[0];

                    $aux[0]=$value->getNombre();
                    $aux[1]=$value->getNif();

                    $_SESSION['cuerpoListado'][]=$aux;
                }
            }
        }        

    }    

    $dt0=array('','','________________','________________');
    $dt1=array('Sumas.....','',$acumulBase,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;

    return $_SESSION['cuerpoListado'];
}


/**
    Listado clientes con trabajos
*/
function listCustomersDataAndWorksOrderAmounts($custIni,$custFin,$fech1,$fech2) {

    $_SESSION['cabeceraListado']=array("NOMBRE","DIRECCIÓN","COD.POSTAL","LOCALIDAD","N.I.F.","TOTAL BASES","TOTAL TRABAJOS");  
    $_SESSION['cuerpoListado']=array();

    $listaclientes=getListCustomers($_SESSION['workingCompany']) ;
    $listToReturn=array();
    $acumulBase=0;
    $acumulTotal=0;     
    
    if ($listaclientes!=false) {
        
        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // primero tomaremos los importes de cada cliente
                $arrayImportes=getAllWorksByCustomer($value->getId(),$fech1,$fech2);
                if ($arrayImportes[0]>0) {
                    // solo incluimos en el listado los clientes que tienen bases o sea, que tienen
                    // trabajos realizados en ese intervalo de fechas
                    $aux=array();
                    // primero colocamos en el array total trabajos porque es la columna a ordenar
                    $aux[0]=$arrayImportes[1];
                    $acumulTotal=$acumulTotal+$arrayImportes[1];           
                    // bases
                    $aux[1]=$arrayImportes[0];
                    $acumulBase=$acumulBase+$arrayImportes[0];

                    $aux[2]=$value->getNombre();
                    $aux[3]=$value->getDireccion();
                    $aux[4]='- '.$value->getCodPostal();
                    $aux[5]=$value->getLocalidad();
                    $aux[6]=$value->getNif();
                    $listToReturn[]=$aux;
                    //array_push($listToReturn, $aux);
                }
            }
        }
        // ordenamos por importes totales decrecientes
        $listToReturn=orderByAmount($listToReturn);

       // construimos el return
       // construimos el return
        for ($i=0; $i < count($listToReturn) ; $i++) { 
            $_SESSION['cuerpoListado'][$i][0]=$listToReturn[$i][2];         
            $_SESSION['cuerpoListado'][$i][1]=$listToReturn[$i][3]; 
            $_SESSION['cuerpoListado'][$i][2]=$listToReturn[$i][4]; 
            $_SESSION['cuerpoListado'][$i][3]=$listToReturn[$i][5]; 
            $_SESSION['cuerpoListado'][$i][4]=$listToReturn[$i][6]; 
            $_SESSION['cuerpoListado'][$i][5]=$listToReturn[$i][1];  
            $_SESSION['cuerpoListado'][$i][6]=$listToReturn[$i][0]; 
        }

    }    

    $dt0=array('','','','','','________________','________________');
    $dt1=array('','','','Sumas.....','',$acumulBase,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;

    return $_SESSION['cuerpoListado'];
}


/**
    Listado clientes con trabajos
*/
function listCustomersNamesAndWorksOrderAmounts($custIni,$custFin,$fech1,$fech2) {

    $_SESSION['cabeceraListado']=array("NOMBRE","N.I.F.","TOTAL BASES","TOTAL TRABAJOS");  
    $_SESSION['cuerpoListado']=array();
    
    $listaclientes=getListCustomers($_SESSION['workingCompany']) ;
    $listToReturn=array();
    $acumulBase=0;
    $acumulTotal=0;     
    if ($listaclientes!=false) {
        
        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // primero tomaremos los importes de cada cliente
                $arrayImportes=getAllWorksByCustomer($value->getId(),$fech1,$fech2);
                if ($arrayImportes[0]>0) {
                    // solo incluimos en el listado los clientes que tienen bases o sea, que tienen
                    // trabajos realizados en ese intervalo de fechas
                    $aux=array();
                    // primero colocamos en el array total trabajos porque es la columna a ordenar
                    $aux[0]=$arrayImportes[1];
                    $acumulTotal=$acumulTotal+$arrayImportes[1];           
                    // bases
                    $aux[1]=$arrayImportes[0];
                    $acumulBase=$acumulBase+$arrayImportes[0];

                    $aux[2]=$value->getNombre();
                    $aux[3]=$value->getNif();
                    $listToReturn[]=$aux;
                }
            }
        }
        // ordenamos por importes totales decrecientes
        $listToReturn=orderByAmount($listToReturn);

       // construimos el return
        for ($i=0; $i < count($listToReturn) ; $i++) { 
            $_SESSION['cuerpoListado'][$i][0]=$listToReturn[$i][2];         
            $_SESSION['cuerpoListado'][$i][1]=$listToReturn[$i][3]; 
            $_SESSION['cuerpoListado'][$i][2]=$listToReturn[$i][1];  
            $_SESSION['cuerpoListado'][$i][3]=$listToReturn[$i][0 ]; 
        }

    }    

    $dt0=array('','','________________','________________');
    $dt1=array('Sumas.....','',$acumulBase,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;

    return $_SESSION['cuerpoListado'];
}


/**
    Esta función confecciona el listado de la facturas por orden de fecha y número, con los parámetros suministrados.
    El listado incluye los datos de los clientes.
*/
function listInvoices($custIni,$custFin,$fech1,$fech2) {

    // transformamos las fechas recibidas
    $f1=substr($fech1, 3).'-'.substr($fech1,0,2).'-01';
    $f2=substr($fech2, 3).'-'.substr($fech2,0,2).'-31';

    $_SESSION['cabeceraListado']=array("NUMERO","FECHA","NOMBRE","N.I.F.","COD.POST","TOTAL BASES","TOTAL CUOTAS","TOTAL TRABAJOS");  
    $_SESSION['cuerpoListado']=array();

    $listToReturn=getInvoicesToListInvoices($custIni,$custFin,$f1,$f2,$_SESSION['workingCompany']);
    $acumulBase=0;
    $acumulTotal=0;
    $acumulCuota=0;
    if ($listToReturn!=false) {
       // construimos el return
        foreach ($listToReturn as $key => $value) {

            $aux=array();
            // primero colocamos en el array total trabajos porque es la columna a ordenar
            $aux[0]=$value[0];
            $aux[1]=convertDateToSpanish($value[1]);
            $aux[2]=$value[2];
            $aux[3]=$value[3];
            $aux[4]='- '.$value[4];
            $aux[5]=$value[5]+$value[7]+$value[9];
            $aux[6]=$value[6]+$value[8]+$value[10];
            $aux[7]=$value[11];
            // bases
            $acumulBase=$acumulBase+$aux[5];
            // cuota
            $acumulCuota=$acumulCuota+$aux[6];
            // total
            $acumulTotal=$acumulTotal+$aux[7];           

            $_SESSION['cuerpoListado'][]=$aux;
        }
    }    

    $dt0=array('','','','','','________________','________________','________________');
    $dt1=array('','','Sumas.....','','',$acumulBase,$acumulCuota,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;

    return $_SESSION['cuerpoListado'];
}


/**
    Esta función confecciona el listado de la trabajos por orden de fecha y número, con los parámetros suministrados.
    El listado incluye si esta facturado o no.
*/
function listWorks($custIni,$custFin,$fech1,$fech2) {

    // transformamos las fechas recibidas
    $f1=substr($fech1, 3).'-'.substr($fech1,0,2).'-01';
    $f2=substr($fech2, 3).'-'.substr($fech2,0,2).'-31';

    $_SESSION['cabeceraListado']=array("NUMERO","FECHA","NOMBRE","FACTURA","TOTAL BASES","TOTAL IMPORTES");  
    $_SESSION['cuerpoListado']=array();

    $listToReturn=getWorksToListWorks($custIni,$custFin,$f1,$f2,$_SESSION['workingCompany']);
    $acumulBase=0;
    $acumulTotal=0;

    if ($listToReturn!=false) {

       // construimos el return
        foreach ($listToReturn as $key => $value) {

            $aux=array();
            // primero colocamos en el array total trabajos porque es la columna a ordenar
            $aux[0]=$value[0];
            $aux[1]=convertDateToSpanish($value[1]);
            $aux[2]=$value[2];
            if ($value[3]=='') {
                $aux[3]='NO FACTURADO';
            } else {
                $aux[3]=$value[3];
            }
            $aux[4]=$value[4];
            $aux[5]=$value[5];

            // bases
            $acumulBase=$acumulBase+$aux[4];
            // total
            $acumulTotal=$acumulTotal+$aux[5];           

            $_SESSION['cuerpoListado'][]=$aux;
        }

    }

    $dt0=array('','','','','________________','________________');
    $dt1=array('','Sumas.....','','',$acumulBase,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;

    return $_SESSION['cuerpoListado'];
}


/**
    Este método transforma al formato español dd-mm-aaaa una fecha en formato date
*/
function convertDateToSpanish($date) {
 
    $y=substr($date,0,4);
    $m=substr($date,5,2);
    $d=substr($date,8);

    return $d.'/'.$m.'/'.$y;
}


/**
    Este método devuelve un listado de trabajos agrupados por clientes y fechas.
*/
function listWorksNameCustomers($custIni,$custFin,$fech1,$fech2) {

       // transformamos las fechas recibidas
    $f1=substr($fech1, 3).'-'.substr($fech1,0,2).'-01';
    $f2=substr($fech2, 3).'-'.substr($fech2,0,2).'-31';

    $_SESSION['cabeceraListado']=array("NOMBRE","NIF","COD.POSTAL","TOTAL BASES","TOTAL IMPORTES");  
    $_SESSION['cuerpoListado']=array();

    $listaclientes=getListCustomers($_SESSION['workingCompany']) ;
    if ($listaclientes!=false) {
        // si hay clientes en la lista
        $acumulBase=0;
        $acumulTotal=0;      
        foreach ($listaclientes as $key => $value) {
            if ($value->getNombre()>=$custIni && $value->getNombre()<=$custFin) {
                // primero tomaremos los importes de cada cliente
                $arrayImportes=getWorksByCustomerAndDates($_SESSION['workingCompany'],$value->getId(),$f1,$f2);
                if ($arrayImportes!=false) {
                    $bs=0;
                    $tt=0;
                    $aux=array();
                    
                    // recorremos todas las facturas una a una
                    foreach ($arrayImportes as $key2 => $value2) {
                        $bs=$bs+$value2[0];
                        $tt=$tt+$value2[1];             
                    }                

                    $aux[0]=$value->getNombre();
                    $aux[1]=$value->getNif();
                    $aux[2]='- '.$value->getCodpostal();                    

                    // bases
                    $aux[3]=$bs;
                    $acumulBase=$acumulBase+$bs;                      
                    // TOTAL                    
                    $aux[4]=$tt;
                    $acumulTotal=$acumulTotal+$tt; 
                    
                    $_SESSION['cuerpoListado'][]=$aux;
                }
            }
        }
    }
       // construimos el return
    $dt0=array('','','','________________','________________');
    $dt1=array('','Sumas.....','',$acumulBase,$acumulTotal);
    $_SESSION['cuerpoListado'][]=$dt0;
    $_SESSION['cuerpoListado'][]=$dt1;    

    return $_SESSION['cuerpoListado'];
}

