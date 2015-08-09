<?php
/**

    
    * * * * * InvoicesDAO es el DAO del model InvoiceClass.php *** *
    * * @Author musef v.1.0 2015-08-01
    

*/

include_once 'controllers/ConnectionClass.php';
include_once 'models/InvoiceClass.php';

/**
    Esta función graba una factura y sus correspondientes trabajos en la DDBB.
    Primeramente graba la factura. Si la grabación es correcta, procede a grabar
    en el fichero auxiliar la relación de los trabajos con las facturas.
    Devuelve EL NUMERO DE LA FACTURA CREADA o FALSE con el resultado de la operación.
*/
function recordInvoice($invoice, $worksArray) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }
    $fact=$invoice->getNumero();
    $company=$invoice->getCompany();
    $data1=' (company, fecha, numero, destinatario, albaranes, baseimponible1, cuotaiva1, baseimponible2, cuotaiva2, baseimponible3, cuotaiva3, total, formapago, vencimiento)';
    $data2=' VALUES ('.$company.',"'.$invoice->getFecha().'","'.$fact.'",'.$invoice->getDestinatario().','.$invoice->getAlbaranes().','.$invoice->getBaseimponible1().','.$invoice->getCuotaiva1().','.$invoice->getBaseimponible2().','.$invoice->getCuotaiva2().','.$invoice->getBaseimponible3().','.$invoice->getCuotaiva3().','.$invoice->getTotal().','.$invoice->getFormapago().',"'.$invoice->getVencimiento().'")';
    $sql='INSERT INTO invoices'.$data1.$data2;
    
    if ($conn->query($sql) === TRUE) {
        // la factura esta grabada, ahora hay que relacionar los albaranes

        foreach ($worksArray as $key => $value) {
            // grabamos albaran por albaran en la tabla auxiliar
            $idToRec=$value[0];
            if (recordRelationship($company,$fact,$idToRec)==false) {
                // error durante la grabación 
                $conn->close();
                return false; 
            }
        }
    } else {
        //echo 'ERROR FACT:'.$sql.'////'.$conn->error;
        $conn->close();
        return false;
    }

    $conn->close();
    return $fact;
}


/**
    Esta función modifica una factura y sus correspondientes trabajos en la DDBB.
    Primeramente modifica la factura. Si la grabación es correcta, procede a modificar
    en el fichero auxiliar la relación de los trabajos con las facturas.
    Devuelve TRUE o FALSE con el resultado de la operación.
*/
function modifyInvoice($invoice, $worksArray, $company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }
    $fact=$invoice->getNumero();

    $data1=' SET fecha="'.$invoice->getFecha().'", numero="'.$fact.'", destinatario='.$invoice->getDestinatario().', albaranes='.$invoice->getAlbaranes().',';
    $data2=' baseimponible1='.$invoice->getBaseimponible1().', cuotaiva1='.$invoice->getCuotaiva1().', baseimponible2='.$invoice->getBaseimponible2().', cuotaiva2='.$invoice->getCuotaiva2().', baseimponible3='.$invoice->getBaseimponible3().', cuotaiva3='.$invoice->getCuotaiva3().', total='.$invoice->getTotal().', formapago='.$invoice->getFormapago().', vencimiento="'.$invoice->getVencimiento().'"';
    $data3=' WHERE company='.$company.' AND numero="'.$fact.'"';
    $sql='UPDATE invoices'.$data1.$data2.$data3;
    
    if ($conn->query($sql) === TRUE) {
        // la factura esta grabada, ahora hay que relacionar los albaranes
        // PRIMERO borramos las relaciones antiguas de trabajo-factura
        if (deleteRelationship($_SESSION['workingCompany'],$fact)) {
            // SEGUNDO grabamos las nuevas relaciones
            foreach ($worksArray as $key => $value) {
                // grabamos albaran por albaran en la tabla auxiliar
                $idToRec=$value[0];
                if (recordRelationship($company,$fact,$idToRec)==false) {
                    // error durante la grabación 
                    $conn->close();
                    return false; 
                }
            }            
        } else {
            //echo 'ERROR WORK_FACT:'.$sql.'////'.$conn->error;
            $conn->close();
            return false;
        }
    } else {
        //echo 'ERROR FACT:'.$sql.'////'.$conn->error;
        $conn->close();
        return false;
    }

    $conn->close();
    return true;
}


/**
    Esta función elimina una factura y sus correspondientes trabajos en la DDBB.
    Primeramente elimina la factura. Si la operación es correcta, procede a eliminar
    en el fichero auxiliar la relación de los trabajos con las facturas.
    Devuelve TRUE o FALSE con el resultado de la operación.
*/
function deleteInvoice($company, $fact, $worksArray) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $data1=' WHERE company='.$company.' AND numero="'.$fact.'"';
    $sql='DELETE FROM invoices'.$data1;
    
    if ($conn->query($sql) === TRUE) {
        // la factura esta eliminada, ahora hay que eliminar los albaranes
        // borramos las relaciones antiguas de trabajo-factura
        if (deleteRelationship($_SESSION['workingCompany'],$fact)) {
            
        } else {
            //echo 'ERROR WORK_FACT:'.$sql.'////'.$conn->error;
            $conn->close();
            return false;
        }
    } else {
        //echo 'ERROR FACT:'.$sql.'////'.$conn->error;
        $conn->close();
        return false;
    }

    $conn->close();
    return true;
}


/**
    Este método devuelve un objeto factura mediante la busqueda por número. 
    Retorna false si no encuentra la factura con ese numero.
*/
function readInvoice($invoiceNumber, $company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company.' AND numero="'.$invoiceNumber.'"';
    $order='';
    $sql='SELECT * FROM invoices'.$filter.$order;
    $result=$conn->query($sql);

    $arrayToReturn=array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='fecha') $fec=$value;
                if ($key=='numero') $num=$value;
                if ($key=='destinatario') $des=$value;
                if ($key=='albaranes') $alb=$value;
                if ($key=='baseimponible1') $bs1=$value;
                if ($key=='cuotaiva1') $cu1=$value;
                if ($key=='baseimponible2') $bs2=$value;
                if ($key=='cuotaiva2') $cu2=$value;
                if ($key=='baseimponible3') $bs3=$value;
                if ($key=='cuotaiva3') $cu3=$value;
                if ($key=='total') $tot=$value;
                if ($key=='formapago') $fp=$value;
                if ($key=='vencimiento') $ven=$value;
            }
            $custom=new InvoiceClass($comp,$num,$fec,$des,$alb,$bs1,$cu1,$bs2,$cu2,$bs3,$cu3,$tot,$fp,$ven);
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
    Este método devuelve un array con todas las facturas de la compañia del argumento
    Si no encuentra, devuelve false.
*/

function getListInvoices($company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company;
    $order=' ORDER BY fecha, numero ASC';
    $sql='SELECT * FROM invoices'.$filter.$order;
    $result=$conn->query($sql);

    $arrayToReturn=array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='fecha') $fec=$value;
                if ($key=='numero') $num=$value;
                if ($key=='destinatario') $des=$value;
                if ($key=='albaranes') $alb=$value;
                if ($key=='baseimponible1') $bs1=$value;
                if ($key=='cuotaiva1') $cu1=$value;
                if ($key=='baseimponible2') $bs2=$value;
                if ($key=='cuotaiva2') $cu2=$value;
                if ($key=='baseimponible3') $bs3=$value;
                if ($key=='cuotaiva3') $cu3=$value;
                if ($key=='total') $tot=$value;
                if ($key=='formapago') $fp=$value;
                if ($key=='vencimiento') $ven=$value;
            }
            $custom=new InvoiceClass($comp,$num,$fec,$des,$alb,$bs1,$cu1,$bs2,$cu2,$bs3,$cu3,$tot,$fp,$ven);
            $custom->setId($id);
            $arrayToReturn[]=$custom;
        }        
    } else {
        $conn->close();
        return false;
    }	
    $conn->close();
    return $arrayToReturn;
}


/**
    Este método devuelve un array con todas las facturas de la compañia del argumento,
    correspondiente al cliente especificado y entre las fechas dadas.
    Si no encuentra, devuelve false.
*/

function getListInvoicesByCustomer($idCustomer,$fec1,$fec2,$company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company.' AND destinatario='.$idCustomer.' AND fecha>="'.$fec1.'" AND fecha<="'.$fec2.'"';
    $order=' ORDER BY fecha, numero ASC';
    $sql='SELECT * FROM invoices'.$filter.$order;
    $result=$conn->query($sql);

    $arrayToReturn=array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='fecha') $fec=$value;
                if ($key=='numero') $num=$value;
                if ($key=='destinatario') $des=$value;
                if ($key=='albaranes') $alb=$value;
                if ($key=='baseimponible1') $bs1=$value;
                if ($key=='cuotaiva1') $cu1=$value;
                if ($key=='baseimponible2') $bs2=$value;
                if ($key=='cuotaiva2') $cu2=$value;
                if ($key=='baseimponible3') $bs3=$value;
                if ($key=='cuotaiva3') $cu3=$value;
                if ($key=='total') $tot=$value;
                if ($key=='formapago') $fp=$value;
                if ($key=='vencimiento') $ven=$value;
            }
            $custom=new InvoiceClass($comp,$num,$fec,$des,$alb,$bs1,$cu1,$bs2,$cu2,$bs3,$cu3,$tot,$fp,$ven);
            $custom->setId($id);
            $arrayToReturn[]=$custom;
        }        
    } else {
        $conn->close();
        return false;
    }    
    $conn->close();
    return $arrayToReturn;
}

/**
    Este método devuelve un array con una lista de facturas a imprimir, ordenadas por fecha y numero,
    según los parámetros de cliente y fecha suministrado.
    En caso de error devuelve false.
*/
function getInvoicesToListInvoices($cli1,$cli2,$fec1,$fec2,$company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $sql1='SELECT invoices.numero,invoices.fecha,customer.nombre,customer.nif,customer.codpostal, ';
    $sql2="invoices.baseimponible1,invoices.cuotaiva1,invoices.baseimponible2,invoices.cuotaiva2, ";
    $sql3="invoices.baseimponible3,invoices.cuotaiva3,invoices.total ";
    $sql4="FROM invoices INNER JOIN customer ";
    $filter1='WHERE invoices.destinatario=customer.id AND customer.nombre>="'.$cli1.'" AND customer.nombre<="'.$cli2.'"';
    $filter2='AND invoices.company='.$company.' AND invoices.fecha>="'.$fec1.'" AND invoices.fecha<="'.$fec2.'"';
    $order=' ORDER BY fecha, numero ASC';
    $sql=$sql1.$sql2.$sql3.$sql4.$filter1.$filter2.$order;
    $result=$conn->query($sql);
    $arrayToReturn=array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $aux=array();
            foreach ($row as $key => $value) {
                if ($key=='numero') $aux[0]=$value;                
                if ($key=='fecha') $aux[1]=$value;
                if ($key=='nombre') $aux[2]=$value;
                if ($key=='nif') $aux[3]=$value;
                if ($key=='codpostal') $aux[4]=$value;                
                if ($key=='baseimponible1') $aux[5]=$value;
                if ($key=='cuotaiva1') $aux[6]=$value;
                if ($key=='baseimponible2') $aux[7]=$value;
                if ($key=='cuotaiva2') $aux[8]=$value;
                if ($key=='baseimponible3') $aux[9]=$value;
                if ($key=='cuotaiva3') $aux[10]=$value;
                if ($key=='total') $aux[11]=$value;
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
    Este método devuelve el siguiente numero de factura, buscando en la DDBB.
    El numero de trabajo es una combinación de numero y cabecera. La cabecera
    responde al parametro AA/.
    ADVERTENCIA: ESTE METODO SOLO SIRVE PARA 2015
    El metodo retorna un numero de construcción 2015/xxxxxxx siendo xxxxxxx un numero
    compuesto por ceros y numeros
*/
function getNextInvoiceNumber() {

    $lista=getListInvoices($_SESSION['workingCompany']);
    $num=0;
    if ($lista!=false) {
         // buscamos el ultimo numero facturado
        foreach ($lista as $key => $value) {
            $numList=$value->getNumero();
            if (substr($numList,0,5)=='2015/'){
                $numList=substr($numList,5);
                if ($numList>$num) {
                // cambiar el id
                $num=$numList;
                }
            }
        }       
    }
    // incrementamos el numero
    $num++;
    $string="000000".(String)$num;
    $string=substr($string, -7);
    $string="2015/".$string;

    return $string;
}


/**
    Este método graba en la tabla auxiliar de DDBB, la relación de un trabajo dentro de una factura.
    Devuelve false si no es posible grabar la relación.
*/
function recordRelationship($company,$numfact,$idwork) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }
    $data1=' (company, num_invoice, id_works)';
    $data2=' VALUES ('.$company.',"'.$numfact.'",'.$idwork.')';
    $sql='INSERT INTO invoices_works'.$data1.$data2;
    
    if ($conn->query($sql) === TRUE) {

    } else {
        $conn->close();
        return false;
    }

    $conn->close();
    return true;
}


/**
    Este método devuelve un array de los trabajos facturados en una factura determinada. 
    Retorna false si no encuentra ningún trabajo con ese número de factura.
*/
function readRelationships($invoiceNumber, $company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $filter=' WHERE company='.$company.' AND num_invoice="'.$invoiceNumber.'"';
    $order=' ORDER BY id_works ASC';
    $sql='SELECT * FROM invoices_works'.$filter.$order;
    $result=$conn->query($sql);

    $arrayToReturn=array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id_works') $arrayToReturn[]=$value;
            }
        }        
    } else {
        $conn->close();
        return false;
    }  
    $conn->close();
    return $arrayToReturn;

}


/**
    Este método borra de la tabla auxiliar de DDBB, las relación de trabajos con facturas.
    Devuelve TRUE o FALSE si no es posible borrar o no hay relación.
*/
function deleteRelationship($company,$numfact) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }
    $data1=' WHERE company='.$company.' AND num_invoice="'.$numfact.'"';
    $sql='DELETE FROM invoices_works'.$data1;
    
    if ($conn->query($sql) === TRUE) {

    } else {
        $conn->close();
        return false;
    }

    $conn->close();
    return true;
}