<?php
/**

    
    * * * * * CustomersDAO es el DAO del model CustomerClass.php *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
include_once 'controllers/ConnectionClass.php';
include_once 'models/CustomerClass.php';
include_once 'controllers/WorksDAO.php';
include_once 'models/WorkClass.php';

/**
    Este método graba en la DDBB el cliente suministrado, retornando
    TRUE o FALSE según el resultado de la operación
*/
function recordCustomer($customer) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $sql="INSERT INTO customer (company, nombre, direccion, codpostal, localidad, nif, formapago) VALUES (".$customer->getCompany().",'".$customer->getNombre()."','".$customer->getDireccion()."','".$customer->getCodpostal()."','".$customer->getLocalidad()."','".$customer->getNif()."',".$customer->getFormapago().")";
    if ($conn->query($sql) === TRUE) {
    } else {
        $conn->close();
        return false;
    }
    $conn->close();
    return true;
}


/**
    Este método elimina de la DDBB al cliente con el id suministrado, retornando
    TRUE o FALSE según el resultado de la operación
*/
function deleteCustomer($id,$company) {

    if ($id<1) {
        return false;
    }

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    // sql to delete a record
    $sql = "DELETE FROM customer WHERE id=".$id.' AND company='.$company;

    if ($conn->query($sql) === TRUE) {
        
    } else {
        $conn->close();
        return false;
    }

    $conn->close();

    return true;

}


/**
    Este metodo modifica el cliente en la DDBB, retornando TRUE o FALSE
    con el resultado dela operación.
*/

function modifyCustomer($customer,$company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $search="id=".$customer->getId().' AND company='.$company;
    $sql='UPDATE customer SET nombre="'.$customer->getNombre().'", direccion="'.$customer->getDireccion().'", codpostal="'.$customer->getCodpostal().'", localidad="'.$customer->getLocalidad().'", nif="'.$customer->getNif().'", formapago='.$customer->getFormapago().' WHERE '.$search;

    if ($conn->query($sql) === TRUE) {

    } else {
       $conn->close();
        return false;
    }
    $conn->close();
    return true;
}


/**
    Este método devuelve un array con todos los clientes de la compañia del argumento.
    En caso de error devuelve false.
*/
function getListCustomers($company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company;
    $order=' ORDER BY nombre ASC';
    $sql='SELECT * FROM customer'.$filter.$order;
    $result=$conn->query($sql);
  
    $arrayToReturn=array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='nombre') $nom=$value;
                if ($key=='direccion') $dir=$value;
                if ($key=='codpostal') $cp=$value;
                if ($key=='localidad') $loc=$value;
                if ($key=='nif') $nif=$value;
                if ($key=='formapago') $fp=$value;
            }
            $custom=new CustomerClass($comp,$nom,$dir,$cp,$loc,$nif,$fp);
            $custom->setId($id);
            $arrayToReturn[]=$custom;
        }        
    } else {
       // echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
        return false;
    }

    $conn->close();    

    return $arrayToReturn;
}


/**
    Este metodo devuelve un objeto customer a partir del id suministrado.
    En caso de error devuelve false.
*/
function readCustomer($id,$company) {

    if ($id==0) return false;

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company. ' AND id='.$id;
    $order='';
    $sql='SELECT * FROM customer'.$filter.$order;
    $result=$conn->query($sql);
  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='nombre') $nom=$value;
                if ($key=='direccion') $dir=$value;
                if ($key=='codpostal') $cp=$value;
                if ($key=='localidad') $loc=$value;
                if ($key=='nif') $nif=$value;
                if ($key=='formapago') $fp=$value;
            }
            $custom=new CustomerClass($comp,$nom,$dir,$cp,$loc,$nif,$fp);
            $custom->setId($id);
        }        
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
        return false;
    }
    $conn->close();    
    return $custom;
}


/**
    Este metodo devuelve un objeto customer a partir del nombre suministrado.
    En caso de error devuelve false.
*/
function readCustomerByName($nameCustomer,$company) {

    if ($nameCustomer=='') return false;

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company. ' AND nombre="'.$nameCustomer.'"';
    $order='';
    $sql='SELECT * FROM customer'.$filter.$order;
    $result=$conn->query($sql);
  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='nombre') $nom=$value;
                if ($key=='direccion') $dir=$value;
                if ($key=='codpostal') $cp=$value;
                if ($key=='localidad') $loc=$value;
                if ($key=='nif') $nif=$value;
                if ($key=='formapago') $fp=$value;
            }
            $custom=new CustomerClass($comp,$nom,$dir,$cp,$loc,$nif,$fp);
            $custom->setId($id);
        }        
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
        return false;
    }
    $conn->close();    
    return $custom;
}