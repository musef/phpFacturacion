<?php
/**

    
    * * * * * WorksDAO es el DAO del model WorkClass.php *** *
    * * @Author musef v.1.0 2015-08-01
    

*/

include_once 'controllers/ConnectionClass.php';
include_once 'models/WorkClass.php';


/**
    Este método realiza la grabación de un trabajo en la base de datos.
    Devuelve TRUE o FALSE con el resultado de la operacion.
*/
function recordWork($workclass) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $sql="INSERT INTO works (company, fecha, numero, cliente, texto, cantidad, importe, base, iva, total, factura) VALUES (".$workclass->getCompany().",'".$workclass->getFecha()."','".$workclass->getNumero()."',".$workclass->getCliente().",'".$workclass->getTexto()."',".$workclass->getCantidad().",".$workclass->getImporte().",".$workclass->getBase().",".$workclass->getIva().",".$workclass->getTotal().",'".$workclass->getFactura()."')";
    if ($conn->query($sql) === TRUE) {
    } else {
        $conn->close();
        return false;
    }

    $conn->close();
    return true;
}


/**
    Este método borra un objeto work de la BBDD, segun el id del objeto suministrado
    Devuelve TRUE o FALSE con el resultado de la operacion.
*/
function deleteWork($id,$company) {

    if ($id<1) {
    	return false;
    }

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $search="id=".$id.' AND company='.$company;
    $sql='DELETE FROM works WHERE '.$search.' LIMIT 1';

    if ($conn->query($sql) === TRUE) {

    } else {
        $conn->close();
        return false;
    }
    $conn->close();
    return true;
}


/**
    Este metodo modifica un objeto work de la ddbb, segun los datos suministrados.
    Devuelve TRUE o FALSE con el resultado de la operación.
*/
function modifyWork($work,$company){

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $search="id=".$work->getId().' AND company='.$company;
    $sql='UPDATE works SET fecha="'.$work->getFecha().'", cliente='.$work->getCliente().', texto="'.$work->getTexto().'", cantidad='.$work->getCantidad().', importe='.$work->getImporte().', base='.$work->getBase().', iva='.$work->getIva().', total='.$work->getTotal().' WHERE '.$search;

    if ($conn->query($sql) === TRUE) {

    } else {
        //echo "Error modifying record: " . $conn->error;
        $conn->close();
        return false;
    }
    $conn->close();
    return true;
}


/**
    Este método graba en un conjunto de trabajos (worksArray) el numero de factura del argumento,
    al cual han sido facturados.
    Devuelve TRUE o FALSE con el resultado de la operación.
*/
function recordInvoiceNumberInWorks($worksArray,$numeroFactura,$company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    foreach ($worksArray as $key => $value) {
        $idToModify=$value[0];

        $search="id=".$idToModify.' AND company='.$company;
        $sql='UPDATE works SET factura="'.$numeroFactura.'" WHERE '.$search;

        if ($conn->query($sql) === TRUE) {

        } else {
            $conn->close();
            return false;
        }
    }
    $conn->close();
    return true;
}


/**
    Este método borra en un conjunto de trabajos (worksArray) el numero de factura del argumento,
    la cual ha sido eliminada.
    Devuelve TRUE o FALSE con el resultado de la operación.
*/
function deleteInvoiceNumberInWorks($numeroFactura,$company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

        $search=' WHERE company='.$company.' AND factura="'.$numeroFactura.'"';
        $sql='UPDATE works SET factura=""'.$search;

        if ($conn->query($sql) === TRUE) {

        } else {
            $conn->close();
            return false;
        }

    $conn->close();
    return true;
}


/**
    Este método devuelve un objeto work, según el id suministrado.
    En caso de no encontrar o error, devuelve false.
*/
function readWork($id,$company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company.' AND id='.$id;
    $order='';
    $sql='SELECT * FROM works'.$filter.$order;

    $result=$conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='fecha') $fec=$value;
                if ($key=='numero') $num=$value;
                if ($key=='cliente') $cli=$value;
                if ($key=='texto') $tex=$value;
                if ($key=='cantidad') $can=$value;
                if ($key=='importe') $imp=$value;
                if ($key=='base') $bas=$value;
                if ($key=='iva') $iva=$value;
                if ($key=='total') $tot=$value;
                if ($key=='factura') $fac=$value;
            }
            $custom=new WorkClass($comp,$fec,$num,$cli,$tex,$can,$imp,$bas,$iva,$tot,$fac);
            $custom->setId($id);
        }        
    } else {
        $conn->close();
        return false;
    }
    $conn->close();
    return $custom;
}


/**
    Este método devuelve un array con todos los trabajos de la compañia del argumento
    Devuelve false si hay algún error o no hay trabajos.
*/
function getListWorks($company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company;
    $order=' ORDER BY fecha,numero ASC';
    $sql='SELECT * FROM works'.$filter.$order;

    $result=$conn->query($sql);

    $arrayToReturn=array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='fecha') $fec=$value;
                if ($key=='numero') $num=$value;
                if ($key=='cliente') $cli=$value;
                if ($key=='texto') $tex=$value;
                if ($key=='cantidad') $can=$value;
                if ($key=='importe') $imp=$value;
                if ($key=='base') $bas=$value;
                if ($key=='iva') $iva=$value;
                if ($key=='total') $tot=$value;
                if ($key=='factura') $fac=$value;
            }
            $custom=new WorkClass($comp,$fec,$num,$cli,$tex,$can,$imp,$bas,$iva,$tot,$fac);
            $custom->setId($id);
            $arrayToReturn[]=$custom;
        }        
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
        return false;
    }

    $conn->close();
    return $arrayToReturn;
}


/**
    Este método devuelve un array con la suma de todos los trabajos de la compañia del argumento,
    para el cliente con el id, y entre las fechas suministradas como parametros.
    Devuelve false si no hay resultados.
*/
function getWorksByCustomerAndDates($company,$idCustomer,$fech1,$fech2) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company.' AND cliente='.$idCustomer.' AND fecha>="'.$fech1.'" AND fecha<"'.$fech2.'"';
    $order=' ORDER BY fecha,numero ASC';
    $sql='SELECT * FROM works'.$filter.$order;

    $result=$conn->query($sql);

    $arrayToReturn=array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $aux=array();
            foreach ($row as $key => $value) {
                if ($key=='base') $aux[0]=$value;
                if ($key=='total') $aux[1]=$value;
            }
            $arrayToReturn[]=$aux;
        }        
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
        return false;
    }

    $conn->close();
    return $arrayToReturn;
}


/**
    Este método devuelve un array con la lista de los trabajos de la compañia del argumento,
    para el cliente con el id, y entre las fechas suministradas como parametros.
    Devuelve false si no hay resultados.
*/
function getListWorksByCustomer($company,$idCustomer,$fech1,$fech2) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company.' AND cliente='.$idCustomer.' AND fecha>="'.$fech1.'" AND fecha<"'.$fech2.'"';
    $order=' ORDER BY fecha,numero ASC';
    $sql='SELECT * FROM works'.$filter.$order;

    $result=$conn->query($sql);

    $arrayToReturn=array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='fecha') $fec=$value;
                if ($key=='numero') $num=$value;
                if ($key=='cliente') $cli=$value;
                if ($key=='texto') $tex=$value;
                if ($key=='cantidad') $can=$value;
                if ($key=='importe') $imp=$value;
                if ($key=='base') $bas=$value;
                if ($key=='iva') $iva=$value;
                if ($key=='total') $tot=$value;
                if ($key=='factura') $fac=$value;  
            }
            $custom=new WorkClass($comp,$fec,$num,$cli,$tex,$can,$imp,$bas,$iva,$tot,$fac);
            $custom->setId($id);
            $arrayToReturn[]=$custom;
        }        
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
        return false;
    }

    $conn->close();
    return $arrayToReturn;
}


/**
    Este método devuelve el objeto work según la factura suministrada.
    Si hay varias facturas con el mismo numero, devuelve la última. SI no encuentra
    devuelve false
*/
function getWorkByNumber($workNumber,$company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company.' AND numero="'.$workNumber.'"';
    $order='';
    $sql='SELECT * FROM works'.$filter.$order;

    $result=$conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='fecha') $fec=$value;
                if ($key=='numero') $num=$value;
                if ($key=='cliente') $cli=$value;
                if ($key=='texto') $tex=$value;
                if ($key=='cantidad') $can=$value;
                if ($key=='importe') $imp=$value;
                if ($key=='base') $bas=$value;
                if ($key=='iva') $iva=$value;
                if ($key=='total') $tot=$value;
                if ($key=='factura') $fac=$value;
            }
            $custom=new WorkClass($comp,$fec,$num,$cli,$tex,$can,$imp,$bas,$iva,$tot,$fac);
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
    Esta función devuelve un array con la lista de trabajos ordenada por fecha y numero, correspondientes
    a los clientes y fechas seleccionadas.
    SI hay error devuelve false.
*/
function getWorksToListWorks($cli1,$cli2,$fec1,$fec2,$company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $sql1='SELECT works.numero,works.fecha,customer.nombre,works.factura, ';
    $sql2="works.base,works.total ";
    $sql3="FROM works INNER JOIN customer ";
    $filter1='WHERE works.cliente=customer.id AND customer.nombre>="'.$cli1.'" AND customer.nombre<="'.$cli2.'"';
    $filter2='AND works.company='.$company.' AND works.fecha>="'.$fec1.'" AND works.fecha<="'.$fec2.'"';
    $order=' ORDER BY fecha, numero ASC';
    $sql=$sql1.$sql2.$sql3.$filter1.$filter2.$order;
    $result=$conn->query($sql);
    $arrayToReturn=array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $aux=array();
            foreach ($row as $key => $value) {
                if ($key=='numero') $aux[0]=$value;                
                if ($key=='fecha') $aux[1]=$value;
                if ($key=='nombre') $aux[2]=$value;
                if ($key=='factura') $aux[3]=$value;
                if ($key=='base') $aux[4]=$value;                
                if ($key=='total') $aux[5]=$value;
            }            
            $arrayToReturn[]=$aux;
        }        
    } else {
        //echo $conn->error;
        $conn->close();
        return false;
    }
    
    $conn->close();
    return $arrayToReturn;
}


/**
    Este método devuelve el siguiente numero de trabajo, buscando en la DDBB.
    El numero de trabajo es una combinación de numero y cabecera. La cabecera
    responde al parametro AA/.
    ADVERTENCIA: ESTE METODO SOLO SIRVE PARA 2015
    El metodo retorna un numero de construcción 15/xxxxx siendo xxxxx un numero
    compuesto por ceros y numeros
*/
function getNextWorkNumber() {

    $lista=getListWorks($_SESSION['workingCompany']);
	$num=0;
    // buscamos el ultimo numero facturado
	if ($lista!=false) {
		foreach ($lista as $key => $value) {
			$numList=$value->getNumero();
			if (substr($numList,0,3)=='15/'){
				$numList=substr($numList,3);
				if ($numList>$num) {
				// cambiar el id
				$num=$numList;
				}
			}
		}	
	}

    // incrementamos el numero
    $num++;
	$string="0000".(String)$num;
    $string=substr($string, -5);
	$string="15/".$string;
	return $string;
}


/**
    Este metodo comprueba si el cliente del idCustomer tiene algun trabajo grabado en DDBB,
    sea facturado o pendiente.
    Retorna true (tiene trabajos) o false (no tiene trabajos)
*/
function hasWorks($idCustomer,$company) {

    // utilizamos el metodo, facilitándole fechas que abarquen todo el periodo de uso
    $result=getWorksByCustomerAndDates($company,$idCustomer,"2014-01-01","2099-12-31");

    if ($result==false) return false;

    return true;
}


