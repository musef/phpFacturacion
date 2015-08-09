<?php
/**

    
    * * * * * InvoicesComponent es una biblioteca de funciones para InvoicesController *** *
    * * @Author musef v.1.0 2015-08-01
    

*/


/**
    Este método realiza la grabación de una factura en la DDBB.
    Retorna EL NUMERO DE FACTURA o FALSE con el resultado de la operación.
    La grabación de una factura exige la grabación en 3 tablas diferentes.
    Hay que grabar en la tabla de facturas, hay que grabar en la auxiliar la
    relación entre factura y trabajos, y hay que modificar los trabajos, añadiendo
    el numero de factura para que no queden pendientes.
*/
function recordThisInvoice($invoice,$worksArray) {
    // primero obtenemos el numero de factura
    $numeroFactura=getNextInvoiceNumber();
    $invoice->setNumero($numeroFactura);

    if (recordInvoice($invoice, $worksArray)!=false) {
        if (recordInvoiceNumberInWorks($worksArray,(string)$numeroFactura, $_SESSION['workingCompany'])!=false) {
            return $numeroFactura;
        }
    }
    return false;
}


/**
    Este método realiza el borrado de una factura en la DDBB.
    Retorna TRUE o FALSE con el resultado de la operación.
    La eliminación de una factura exige la eliminación en 3 tablas diferentes.
    Hay que eliminar en la tabla de facturas, hay que eliminar en la auxiliar la
    relación entre factura y trabajos, y hay que modificar los trabajos, borrando
    el numero de factura y dejandolo en blanco para que queden pendientes.
*/
function deleteThisInvoice($numFact,$worksArray) {
   
    // primero borramos la factura y luego borramos de los trabajos el numero de factura anotado.
    if (deleteInvoice($_SESSION['workingCompany'], $numFact, $worksArray)!=false) {
        if (deleteInvoiceNumberInWorks($numFact,$_SESSION['workingCompany'])!=false) {
            return true;
        }
    }
    return false;
}


/**
    Este método realiza el borrado de una factura en la DDBB.
    Retorna TRUE o FALSE con el resultado de la operación.
    La eliminación de una factura exige la eliminación en 3 tablas diferentes.
    Hay que eliminar en la tabla de facturas, hay que eliminar en la auxiliar la
    relación entre factura y trabajos, y hay que modificar los trabajos, borrando
    el numero de factura y dejandolo en blanco para que queden pendientes.
*/
function modifyThisInvoice($invoice,$worksArray) {
   
    $numFact=$invoice->getNumero();
    if ($numFact=='') return false;

    // primero borramos la factura y luego borramos de los trabajos el numero de factura anotado.
    if (modifyInvoice($invoice, $worksArray,$_SESSION['workingCompany'])!=false) {
        // segundo, borramos de todos los trabajos el numero de factura anotado, correspondiente a la 
        // factura antigua, y los dejamos pendientes.
        if (deleteInvoiceNumberInWorks($numFact,$_SESSION['workingCompany'])!=false) {
            // tercero, grabamos en los trabajos de la factura actual el numero de factura,
            // y los dejamos facturados
            if (recordInvoiceNumberInWorks($worksArray,$numFact,$_SESSION['workingCompany'])!=false) {
                return true;
            }           
        }
    }
    return false;
}


/**
    Este método retorna la lista de los nombres de los clientes en formato select de html
*/
function getListCustomersNameToInvoices($option) {
    
    // una vez elegido un cliente se habilita la opción disabled
    $dis="";
    if ($option!=0) {
        $dis="disabled";
    }

    $data='<select id="customerInvoices" name="customerInvoices" ><option value="0">Seleccione cliente</option>';
    $listaclientes=getListCustomers($_SESSION['workingCompany']) ;
    foreach ($listaclientes as $key => $value) {
        // añadimos uno a uno
        if ($value->getId()==$option) {
            $data=$data.'<option value="'.$value->getId().'" selected>'.$value->getNombre().'</option>';
        } else {
            $data=$data.'<option value="'.$value->getId().'" >'.$value->getNombre().'</option>';
        }      
    }
    $data=$data.'</select>'; 
    echo $data;
}


/**
    Este método retorna la lista de las formas de pago en formato select de html
*/
function getListFormasPagoToInvoice($formapago) {

    $formasPagoLs=getListFormasPagos($_SESSION['workingCompany']);
    $data="";
    if ($formasPagoLs!=false) {
        foreach ($formasPagoLs as $key => $value) {
            // añadimos uno a uno
            if ($value->getId()==$formapago) {
                $data=$data.'<option selected value="'.$value->getId().'">'.$value->getNombrePago().'</option>';
            } else {
                $data=$data.'<option value="'.$value->getId().'">'.$value->getNombrePago().'</option>';
            }               
        }
    }
    echo $data;
}


/**
    Este metodo crea en html un listado de botones con los parametros seleccionados por el selector lateral
*/
function getWorksSelectedToInvoice($idCustomer,$opcion){

    if ($idCustomer==0) return false;

    $thisDate=getDate();
    $y=$thisDate['year'];
    $m2=$thisDate['mon'];
    if (strlen($m2)==1) $m2='0'.$m2;

    // asignamos inicialmente como todos los trabajos (opcion=0)
    $fech1='2014-01-01';
    $fech2=$y.'-'.$m2.'-31';

    if ($opcion==1) {
        // solo los del mes
        $fech1=$y.'-'.$m2.'-01';
    } elseif ($opcion==2) {
        // solo los del trimestre
        if ($m2<4) {
            $fech1=$y.'-01-01';
        } elseif ($m2<7) {
            $fech1=$y.'-04-01';
        } elseif ($m2<10) {
            $fech1=$y.'-07-01';
        } else {
            $fech1=$y.'-10-01';
        }
    }

    // creacion de la lista
    $work=getListWorksByCustomer($_SESSION['workingCompany'],$idCustomer,$fech1,$fech2);

    $result="";
    if ($work!=false) {
        foreach ($work as $value) {
            $fechaW=$value->getFecha();
            //$numCustomer=$value->getCliente();
            $prov=searchCustomerclassToInvoice($idCustomer);
            if ($prov!=false) {
                $customerData=$prov->getNombre();
                $fact=$value->getFactura();
                if (strlen($fact)<1) {
                    // si no esta facturado se muestra
                    $numeroW=$value->getNumero();
                    $clientW=substr($customerData,0,25);
                    $result=$result."<li class='buttonWork'><button name='".$numeroW."' type='submit'>".convertDateToSpanish($fechaW)." ".$numeroW." ".$clientW."</button></li>";
                }
            }
        }
        if ($result!='') {
            $result='<button id="closeWindow" class="closeWindow">X</button><h2>Trabajos pendientes</h2><ul>'.$result.'</ul>';
        } else {
            $result='<button id="closeWindow" class="closeWindow">X</button><h2>Trabajos pendientes</h2><ul><li class="buttonWork">Ningún trabajo pendiente de facturar</li></ul>';
        }
    } 
    return $result;
}


/**
    Este metodo crea en html un listado de botones con los parametros seleccionados por el selector lateral
*/
function getInvoicesSelectedToEdit($idCustomer,$opcion){

    if ($idCustomer==0) return false;

    $thisDate=getDate();
    $y=$thisDate['year'];
    $m2=$thisDate['mon'];
    if (strlen($m2)==1) $m2='0'.$m2;

    // asignamos inicialmente como todos los trabajos (opcion=0)
    $fech1='2014-01-01';
    $fech2=$y.'-'.$m2.'-31';

    if ($opcion==1) {
        // solo los del mes
        $fech1=$y.'-'.$m2.'-01';
    } elseif ($opcion==2) {
        // solo los del trimestre
        if ($m2<4) {
            $fech1=$y.'-01-01';
        } elseif ($m2<7) {
            $fech1=$y.'-04-01';
        } elseif ($m2<10) {
            $fech1=$y.'-07-01';
        } else {
            $fech1=$y.'-10-01';
        }
    }

    // creacion de la lista
    $listInv=getListInvoicesByCustomer($idCustomer,$fech1,$fech2,$_SESSION['workingCompany']);

    $result="";
    if ($listInv!=false) {
        foreach ($listInv as $value) {
            $fechaW=$value->getFecha();
            // consiguiendo el nombre del objeto customer
            $prov=searchCustomerclassToInvoice($idCustomer);
            if ($prov!=false) {
               $customerData=$prov->getNombre();
                // si no esta facturado se muestra
                $numeroW=$value->getNumero();
                $clientW=substr($customerData,0,25);
                $result=$result."<li class='buttonInv'><button name='".$numeroW."' type='submit'>".convertDateToSpanish($fechaW)." ".$numeroW." ".$clientW."</button></li>";                
            }
         }
        if ($result!='') {
            $result='<button id="closeWindowF" class="closeWindowF">X</button><h2>Facturas seleccionadas</h2><ul>'.$result.'</ul>';
        } else {
            $result='<button id="closeWindowF" class="closeWindowF">X</button><h2>Facturas pendientes</h2><ul><li class="buttonInv">Este cliente no tiene facturas</li></ul>';
        }
    } else {
            $result='<button id="closeWindowF" class="closeWindowF">X</button><h2>Facturas pendientes</h2><ul><li class="buttonInv">Este cliente no tiene facturas</li></ul>';
    }
    return $result;
}


/**
    Este método retorna el objeto cliente que corresponde con el id de cliente suministrado como argumento.
    Retorna el objeto si lo encuentra, o false en caso contrario.
*/
function searchCustomerclassToInvoice($idcustomer) {

    return readCustomer($idcustomer,$_SESSION['workingCompany']);

}


/**
    Este método retorna el objeto factura que corresponde con el numero de factura suministrado como argumento.
    Si no lo encuentra, devuelve false
*/
function searchWorkclassToInvoice($work) {

    if (strlen($work)==0) return false;

    return getWorkByNumber($work,$_SESSION['workingCompany']);
}


/**
    Este método recibe un id de un trabajo y devuelve un array con los datos del trabajo.
    En caso de no existir el trabajo, devuelve un array en blanco.
*/
function readWorkForInvoice($wk) {

    $workclass=readWork($wk,$_SESSION['workingCompany']);

    if ($workclass==false) {
        $dataWork=array(0,"",0,"",0,0);
        return $dataWork;        
    }

    $idWork=$workclass->getId();
    $codigo=$workclass->getNumero();
    $texto=$workclass->getTexto();
    $cantidad=$workclass->getCantidad();
    $importe=$workclass->getImporte();
    $iva=$workclass->getIva();
    $dataWork=array($idWork,$codigo,$cantidad,$texto,$importe,$iva);
    return $dataWork;
}


/**
    Este método añade a la matrizPrincipal($_SESSION) un array con los datos (matrizAuxiliar). Para ello
    busca el primer valor 0 dentro del primer elemento del array, empezando por el principio.
    EL array matrizPrincipal deberá contener 0 en el primer elemento, y estar previamente creada.
    Función void.
*/

function addTrabajo($matrizAuxiliar) {
    // la matriz permite solamente 5 trabajos
    for ($i=0; $i < 5; $i++) { 
        if ($_SESSION['dataInvoice'][$i][0]==0) {
            $_SESSION['dataInvoice'][$i]=$matrizAuxiliar;
            break;
        }        
    }

}


/**
    Este método realiza un calculo de la factura, con los datos contenidos en el array recibido.
    Devuelve un array con el resultado.
*/
function calculaFactura($dataInvoice) {

    $result=array(0,0,0,0,0,0,0);

    for ($i=0 ; $i < 5 ; $i++ ) { 
        $cant=$dataInvoice[$i][2];
        $imp=$dataInvoice[$i][4];
        $iva=$dataInvoice[$i][5];        

        // coge los tipos de iva definidos en Init, para aplicarlos a la factura
        if ($_SESSION['ivaTipo1']==$iva) {
            $result[1]=$result[1]+($cant*$imp);
            $result[4]=$result[4]+($cant*$imp)*($iva/100);
        } elseif ($_SESSION['ivaTipo2']==$iva) {
            $result[2]=$result[2]+($cant*$imp);
            $result[5]=$result[5]+($cant*$imp)*($iva/100);         
        } else {
            $result[3]=$result[3]+($cant*$imp);
            $result[6]=$result[6]+($cant*$imp)*($iva/100);
        }
    }

    $result[0]=$result[1]+$result[2]+$result[3]+$result[4]+$result[5]+$result[6];

    return $result;
}


/**
    Este método elimina un registro y reordena el array dataInvoice.
    Recibe el nombre del boton de eliminar. De ahí extrae el valor y elimina
    el row mediante la copia de los valores posteriores. EL último valor va en blanco.
*/
function reorderDataInvoice($data) {
    // $data contiene el nombre del boton pulsado con el formato delX
    // hay que obtener el X
    $valor=substr($data, 3,1);
    $valor--;
    // ahora copiaremos la matriz desde el final (5) hasta el valor a eliminar
    for ($i=$valor;$i<4;$i++) {
        $_SESSION['dataInvoice'][$i]=$_SESSION['dataInvoice'][$i+1];       
    }
    // el ultimo valor queda ahora en blanco
    $_SESSION['dataInvoice'][4]=array(0,'',0,'',0,0);
}


/**
    Este método transforma al formato date yyyy-mm-dd una fecha en formato spanish
*/
function convertDateToInternational($spanishdate) {

    $y=substr($spanishdate,6);
    $m=substr($spanishdate,3,2);
    $d=substr($spanishdate,0,2);

    return $y.'-'.$m.'-'.$d;
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
    Esta funcion devuelve el numero formateado en formato texto español
*/
function formato($num) {
    return number_format($num, 2, ',', '.');
}


/**
    Esta funcion devuelve el numero formateado en formato para database (english)
*/
function formato_db($num) {
    $num=str_replace('.', '', $num);
    $num=str_replace(',', '.', $num);
    return $num;
}


/**
    Método depurador de las entradas por formulario para evitar inyeccion de codigo o XSS
*/
function depura_input($data) {
    // eliminamos espacios
    $data = trim($data);
    // quitamos las barras invertidas
    $data = stripslashes($data);
    // limpiamos caracteres especiales
    $data = htmlspecialchars($data);
    
  return $data;
 }