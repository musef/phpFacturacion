<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
	/*
    Esta funcion devuelve el numero formateado en formato texto español
	*/
	function formato($num) {
    	return number_format($num, 2, ',', '.');
	}
	// arrays de impresión
	//$_SESSION['cabeceraListado']=array();
	//$_SESSION['cuerpoListado']=array(array());
	$cabecera=array();
	$cuerpo=array();

	$numCab=0;
	$column=count($_SESSION['cabeceraListado']);
	for ($i=0; $i < $column ; $i++) { 
		$cabecera[$i]=$_SESSION['cabeceraListado'][$i];
		$numCab++;	
	}

	$numOper=0;
	for ($i=0; $i <count($_SESSION['cuerpoListado']) ; $i++) { 
		$cuerpo[$i]=$_SESSION['cuerpoListado'][$i];
		$numOper++;	
	}

	/*
		Esta función construye el cuerpo de la tabla con la información del listado. Discrimina
		entre contenido numérico y no numérico, con el fin de asignarle un class diferente que
		permita alineación a izquierda o a derecha.
	*/
	function makeBodyTable () {
		
		$column=count($_SESSION['cabeceraListado']);
		$data="";

		for ($i=0; $i <count($_SESSION['cuerpoListado']); $i++) {
			$data=$data."<tr>";
			for ($j=0; $j < $column ; $j++) { 
				$dato=$_SESSION['cuerpoListado'][$i][$j];
				if (is_numeric($dato)) {
					// los numeros se alinean a la derecha
					$data=$data.'<td class="der">'.formato($dato).'</td>';			
				} else {
					if (substr($dato, 0,1)=='_') {
						// las rayas de suma se alinean a la derecha
						$data=$data.'<td class="der">'.$dato.'</td>';
					} else {
						// el resto de datos se alinean a la izquierda
						$data=$data.'<td class="izq">'.$dato.'</td>';	
					}
				}
			}
			$data=$data."</tr>";
		}
			
		return $data;
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/phpMockupGeneral.css" />     
        <link rel="stylesheet" type="text/css" href="css/phpMockupPrintList.css" />      
        <title>Impresión listados</title>
	</head>
	<body>
		<div class="titleCompany">
			<div class="dataCompany">
			<h1>Compañia de prueba S.L.</h1>
			</div>
			<div class="logoCompany">
				<img src="img/fabrica.jpg" alt="anagrama de la compañía">
			</div>			
		</div>
		<hr>
		<div>
			<h2><?php echo $_SESSION['tituloListado'];?></h2>
			<h3><?php echo $_SESSION['subtituloListado'];?></h3>
		</div>
		<div class="detail">
			<table>
				<thead>
					<tr>
						<?php for ($i=0; $i < $numCab ; $i++) { ?>
						<th><?php echo $cabecera[$i]; ?></th>
						<?php } ?>
					</tr>	
				</thead>
				<tbody>
					<?php echo makeBodyTable();?>
				</tbody>
			</table>
		</div>		
	</body>
</html>