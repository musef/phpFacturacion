<?php
/**

    
    * * * * * WorksComponent es una biblioteca de funciones para WorksController *** *
    * * @Author musef v.1.0 2015-08-01
    

*/

include_once 'controllers/WorksDAO.php';
include_once 'models/WorkClass.php';
include_once 'CustomersDAO.php';


/**
Este metodo crea un listado de botones html con los trabajos seleccionados 
según los parametros seleccionados por fecha, cliente.
*/
function getWorksSelected($year1,$year2,$month1,$month2,$customer,$opcion){
    
    // obtención de la lista de trabajos
    $work=getListWorks($_SESSION['workingCompany']);
    $result="";

    if ($work!=false) {

     // hay dos casos diferentes de busqueda, en función de si se busca por mes o no
        // si NO SE BUSCA POR MESES, entonces la busqueda es de fecha a fecha
        // si SE BUSCA POR MESES, entonces la busqueda es el mes de cada año seleccionado

        if ($month1!=$month2) {
            // CASO 1: NO SE BUSCA POR MESES
            //preparacion de filtros de busqueda
            $fecha1=$year1.'-'.$month1.'-01';
            $fecha2=$year2.'-'.$month2.'-31';

            foreach ($work as $value) {
                $fechaW=$value->getFecha();
                if ($fechaW>=$fecha1 && $fechaW<=$fecha2) {
                    $numCustomer=$value->getCliente();
                    $prov=searchCustomerclass($numCustomer);
                    if ($prov!=false) {
                        $customerData=$prov->getNombre();
                        $fact=$value->getFactura();
                        if (($opcion==0) || (strlen($fact)==0 && $opcion==1) || (strlen($fact)>0 && $opcion==2)) {
                            if ($customer=="" || $customerData==$customer) {            
                                $numeroW=$value->getNumero();
                                $clientW=substr($customerData,0,25);
                                $result=$result."<li class='buttonWork'><button name='".$numeroW."' type='submit'>".$fechaW." ".$numeroW." ".$clientW."</button></li>";
                            } 
                        }
                    }
                }
            }
        } else {
            // CASO 2: SI SE BUSCA POR MESES

            //preparacion de filtros de busqueda
            $fecha1=$year1.'-'.$month1.'-01';
            $fecha2=$year1.'-'.$month2.'-31';
            foreach ($work as $value) {
                $fechaW=$value->getFecha();
                if ($fechaW>=$fecha1 && $fechaW<=$fecha2) {
                    $numCustomer=$value->getCliente();
                    $prov=searchCustomerclass($numCustomer);
                    if ($prov!=false) {
                        $customerData=$prov->getNombre();
                        $fact=$value->getFactura();
                        if (($opcion==0) || (strlen($fact)==0 && $opcion==1) || (strlen($fact)>0 && $opcion==2)) {
                            if ($customer=="" || $customerData==$customer) {            
                                $numeroW=$value->getNumero();
                                $clientW=substr($customerData,0,25);
                                $result=$result."<li class='buttonWork'><button name='".$numeroW."' type='submit'>".$fechaW." ".$numeroW." ".$clientW."</button></li>";
                            } 
                        } 
                    }
                }
            }
        }
    }

    if ($result!='') {
        $result='<button id="closeWindow" class="closeWindow" type="button">X</button><h2>Lista seleccionada</h2><ul>'.$result.'</ul>';
    }
    return $result;
}


/**
    Este método retorna el objeto factura que corresponde con el numero de factura suministrado como argumento.
    Retorna false si hay algún error.
*/
function searchWorkclass($work) {

    if (strlen($work)==0) return false;

    return getWorkByNumber($work,$_SESSION['workingCompany']);
}


/*
    Este método retorna el objeto cliente que corresponde con el id de cliente suministrado como argumento.
    Retorna el objeto si lo encuentra, o false en caso contrario.
*/
function searchCustomerclass($idcustomer) {

    return readCustomer($idcustomer,$_SESSION['workingCompany']);

}


/**
    Este método retorna el objeto cliente que corresponde con el nombre de cliente suministrado 
    como argumento. Si no lo encuentra, devuelve false.
*/
function searchCustomerclassByName($customerName) {

    $customerNamesLs=getListCustomers($_SESSION['workingCompany']);
    foreach ($customerNamesLs as $key => $value) {
        // buscamos la clave hasta encontrar el cliente y devolvemos la clase modelo
        $dato=$value->getNombre();
        if ($dato==$customerName) {
            return $value;
        }
    }
    
    return false;
}

/**
    Este método retorna la lista de los nombres de los clientes en formato select de html
*/
function getListCustomersName($option) {
    $data="";
    $listaclientes=getListCustomers($_SESSION['workingCompany']) ;
    foreach ($listaclientes as $key => $value) {
        // añadimos uno a uno
        if ($value->getId()==$option) {
            $data=$data.'<option selected>'.$value->getNombre().'</option>';
        } else {
            $data=$data.'<option>'.$value->getNombre().'</option>';
        }      
    }
    echo $data;
}


/**
    Este método retorna la lista de los tipos de iva en formato select de html
*/
function getListCurrentIva($tipoIva) {
    
    $data="";

    if ($_SESSION['ivaTipo3']==$tipoIva) {
        $data=$data.'<option value="'.$_SESSION['ivaTipo3'].'" selected>'.$_SESSION['ivaTipo3'].'%</option>';
    } else {    
        $data=$data.'<option value="'.$_SESSION['ivaTipo3'].'">'.$_SESSION['ivaTipo3'].'%</option>';
    }
    if ($_SESSION['ivaTipo2']==$tipoIva) {
        $data=$data.'<option value="'.$_SESSION['ivaTipo2'].'" selected>'.$_SESSION['ivaTipo2'].'%</option>';
    } else {    
        $data=$data.'<option value="'.$_SESSION['ivaTipo2'].'">'.$_SESSION['ivaTipo2'].'%</option>';
    }
    if ($_SESSION['ivaTipo1']==$tipoIva) {
        $data=$data.'<option value="'.$_SESSION['ivaTipo1'].'" selected>'.$_SESSION['ivaTipo1'].'%</option>';
    } else {    
        $data=$data.'<option value="'.$_SESSION['ivaTipo1'].'">'.$_SESSION['ivaTipo1'].'%</option>';
    }

    echo $data;
}


/**
Este método graba un objeto work, y retorna la lista con la nueva lista
*/
function recordThisWork($workclass) {
    // instancia el DAO
    return recordWork($workclass);
}


/**
    Este método modifica un objeto work, y retorna TRUE o FALSE con el resultado.
*/
function modifyThisWork($workclass) {
     
    // instancia el DAO
    return modifyWork($workclass,$_SESSION['workingCompany']);
}


/**
Este método borra un objeto work, y retorna la lista con la nueva lista
*/
function deleteThisWork($id) {
  
    if ($id<1) {
        return false;
    }
    
    // instancia el DAO
    return deleteWork($id,$_SESSION['workingCompany']);
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
    Este método transforma al formato date yyyy-mm-dd una fecha en formato spanish
*/
function convertDateToInternational($spanishdate) {

    $y=substr($spanishdate,6);
    $m=substr($spanishdate,3,2);
    $d=substr($spanishdate,0,2);

    return $y.'-'.$m.'-'.$d;
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