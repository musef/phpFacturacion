<?php
/**

    
    * * * * * CustomersComponent es una biblioteca de funciones para CustomerController *** *
    * * @Author musef v.1.0 2015-08-01
    

*/


/**
    Este metodo elimina de la lista de clientes en DDBB al cliente con el id suministrado por parametro.
    Devuelve TRUE o FALSE con el resultado de la operación.
*/
function recordThisCustomer($customer) {

    // procesa la grabación y devuelve la lista
    return recordCustomer($customer);
}


/**
    Este metodo elimina de la lista de clientes en DDBB al cliente con el id suministrado por parametro.
    Devuelve TRUE o FALSE con el resultado de la operación.
*/
function deleteThisCustomer($idCust) {
    // primero comprobamos si el cliente tiene algun trabajo pendiente
    if (hasWorks($idCust,$_SESSION['workingCompany'])==true) {
        // en caso de tenerlo, no puede ser borrado
        return false;
    }

    // procesa el borrado y devuelve la lista
    return deleteCustomer($idCust,$_SESSION['workingCompany']);
}


/**
    Este metodo elimina de la lista de clientes en DDBB al cliente con el id suministrado por parametro.
    Devuelve TRUE o FALSE con el resultado de la operación.
*/
function modifyThisCustomer($customer) {

    // procesa la grabación y devuelve la lista
    return modifyCustomer($customer,$_SESSION['workingCompany']);
}


/**
    Este método retorna la lista de los nombres de los clientes en formato select de html.
    Devuelve empty en caso de error o de no haber datos.
*/
function getListCustomersName($option) {
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
    
    return readCustomerByName($customerName,$_SESSION['workingCompany']);
}


/**
    Este método retorna la lista de las formas de pago en formato select de html
*/
function getListFormasPago($formapago) {

    $formasPagoLs=getListFormasPagos($_SESSION['workingCompany']);
    $data="";
    foreach ($formasPagoLs as $key => $value) {
        // añadimos uno a uno
        if ($value->getId()==$formapago) {
            $data=$data.'<option selected="selected">'.$value->getNombrePago().'</option>';
        } else {
            $data=$data.'<option>'.$value->getNombrePago().'</option>';
        }               
    }
    echo $data;
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

?>