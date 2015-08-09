<?php 
/**

    
    * * * * * FormasPagoDAO es el DAO del model FormasPagoClass.php *** *
    * * @Author musef v.1.0 2015-08-01
    

*/
include_once 'controllers/ConnectionClass.php';
include_once 'models/FormasPagoClass.php';


/**
    Este método retorna el id de una forma de pago, dado su nombre.
    devuelve 0 si no encuentra el dato.
*/
function getIdFormaPago($nameForma,$company) {

    if ($nameForma=='') return 0;

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $sql='SELECT id FROM formaspago WHERE company='.$company.' AND nombrepago="'.$nameForma.'"';
    $result=$conn->query($sql);
  
    $id=0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
            }
        }        
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
        return 0;
    }

    $conn->close();    
    return $id;

}


/**
    Este método retorna el nombre de una forma de pago, dada su id.
    Devuelve false si no lo encuentra.
*/
function getNameFormaPago($id,$company) {

    if ($id==0) return false;

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $sql="SELECT nombrepago FROM formaspago WHERE company=".$company.' AND id='.$id;
    $result=$conn->query($sql);
  
    $name="";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='nombrepago') $name=$value;
            }
        }        
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        $conn->close();
        return false;
    }

    $conn->close();    
    return $name;
}


/**
    Este método devuelve un array con todos las formas de pago de la compañia del argumento.
    Devuelve false si hay algún error.
*/
function getListFormasPagos($company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $sql="SELECT * FROM formaspago WHERE company=".$company;
    $result=$conn->query($sql);
  
    $arrayToReturn=array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                if ($key=='id') $id=$value;
                if ($key=='company') $comp=$value;
                if ($key=='nombrepago') $nom=$value;
                if ($key=='diff') $dif=$value;
                if ($key=='diapago') $pago=$value;
            }
            $custom=new FormasPagoClass($comp,$nom,$dif,$pago);
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
    Este metodo devuelve la fecha de vencimiento de la factura, teniendo en
    cuenta la forma de pago y el dia de pago, si lo tiene.
*/
function getVencimiento($id,$fechaFact,$company) {

    $conn=new ConnectionClass();
    $conn=$conn->getConnection();
    if ($conn->connect_error) {
        die("Fallo de conexión con DDBB: " . $conn->connect_error);
    }

    $sql='SELECT * FROM formaspago WHERE company='.$company.' AND id='.$id;
    $result=$conn->query($sql);

    $dif="";
    $pago="";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                
                if ($key=='nombrepago') $nom=$value;
                if ($key=='diff') $dif=$value;
                if ($key=='diapago') {
                    $pago=$value;
                    break;
                }
                
            }
        }        
    } else {
        $conn->close();
        return false;
    }
    $conn->close(); 

    // convertimos a Datetime la fecha de factura
    $fecha = new DateTime();
    $y=substr($fechaFact, 0,4);
    $m=substr($fechaFact, 5,2);
    $d=substr($fechaFact, 8);
    $fecha->setDate($y,$m,$d);
    // fabricamos el intervalo
    if ($dif=='' || $dif==0) {
        // no hay que hacer ningún intervalo
    } else {
        // sumamos el intervalo a la fecha de factura
        // añadimos $dif dias a la fecha de factura
        $intervalCalc='P'.$dif.'D';
        $interval=new DateInterval($intervalCalc);
        // añadimos a la fecha de factura el intervalo
        $fecha->add($interval);
    }
    $fechaRet=$fecha->format('Y-m-d');
    // comprobamos dia de pago

    // Si dia de pago es 0, la fecha es pagadera en la
    // fecha de emisión de la factura
    if ($pago==0) {
        // devolvemos la fecha en formato español
        $dia=substr($fechaRet, 8, 2);
        $mes=substr($fechaRet, 5, 2);
        $anno=substr($fechaRet, 0, 4);
        return $dia.'-'.$mes.'-'.$anno;
    }

    // Si dia de pago es 31, la fecha es pagadera en
    // el ultimo dia del mes
    if ($pago>=31) {
        // devolvemos la fecha en formato español
        $mes=substr($fechaRet, 5, 2);
        $anno=substr($fechaRet, 0, 4);

        if ($mes==2) {
            $dia=28;
        } else if ($mes==4 || $mes==6 || $mes==9 || $mes==11) {
            $dia=30;
        } else {
            $dia=31;
        }
        return $dia.'-'.$mes.'-'.$anno;
    }

    // finalmente, si dia de pago es entre 0 y 31, hay que pasar la
    // fecha de pago al día correspondiente, pasando el mes e incluso
    // el año si procede
    
    if (substr($fechaRet, 8, 2)<=$pago) {
        // si el dia de vencimiento esta por debajo del
        // dia de pago, solo hay que cambiar el dia de pago
        $dia=$pago;
        $mes=substr($fechaRet, 5, 2);
        $anno=substr($fechaRet, 0, 4);
    } else {
        // pero si esta por encima, entonces hay que cambiar de mes
        // además del día
        $dia=$pago;
        $mes=substr($fechaRet, 5, 2);
        if ($mes!=12) {
            // si el mes no es 12, entonces solo hay que
            // cambiar el mes
            $mes++;
            $anno=substr($fechaRet, 0, 4);
        } else {
            // hay que cambiar todo, dia, mes y año :(
            $mes=01;
            $anno=substr($fechaRet, 0, 4);
            $anno++;    
        }
    }
    // devolvemos la fecha en formato español
    return $dia.'-'.$mes.'-'.$anno;
}

?>