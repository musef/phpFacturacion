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

	$num=0;
	for ($i=0; $i < 5 ; $i++) { 
		// comprueba cuantos trabajos tiene la factura
		if ($_SESSION['dataWorksInvoice'][$i][0]=='0') {
			$num=$i;
			break;
		}
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/phpMockupGeneral.css" />     
        <link rel="stylesheet" type="text/css" href="css/phpMockupPrintInvoice.css" />      
        <title>Impresión factura</title>
	</head>
	<body>
		<div class="titleCompany">
			<div class="dataCompany">
			<h1>Compañia de prueba S.L.</h1>
			<h2>c/ una cualquiera, nº 22</h2>
			<h2>280XX Madrid</h2>
			<h2>N.I.F.: B28000ZZZ</h2>
			</div>
			<div class="logoCompany">
				<img src="img/fabrica.jpg" alt="anagrama de la compañía">
			</div>
		</div>
		<div class="capInvoice">
			<div class="invoiceRef">
				<h3>Fecha factura: <?php  echo $_SESSION['dataInfo'][0]; ?></h3>
				<h3>Número factura: <?php  echo $_SESSION['dataInfo'][1]; ?></h3>
			</div>
			<div class="invoiceCustomer">
				<label>Cliente:</label>
				<h3><?php echo $_SESSION['dataCustomer'][0]; ?></h3>
				<h4><?php echo $_SESSION['dataCustomer'][1]; ?></h4>
				<h4><?php echo $_SESSION['dataCustomer'][2]; ?></h4>
				<h4><?php echo $_SESSION['dataCustomer'][3]; ?></h4>
			</div>			
		</div>
		<div class="detail">
			<?php for ($i=0; $i < $num; $i++) { 
				// itera segun el numero de trabajos que tiene la factura
			?>
			<div class="desc">
				<input class="headNormal" id="cod1" name="cod1" type="text" value="Trabajo" readonly/>
	            <input class="headSuper"  id="des1" name="des1" type="text" value="Descripción" readonly/>
	            <br />
	            <input class="normal" id="cod1" name="cod1" type="text" value="<?php echo $_SESSION['dataWorksInvoice'][$i][1]; ?>" readonly/>            
	            <input class="super"  id="des1" name="des1" type="text" value="<?php echo $_SESSION['dataWorksInvoice'][$i][3]; ?>" readonly/>
	            <br />
        	</div>
            <div class="amount">
	            <input class="headShorty" id="uds1" name="uds1" type="text" value="Uds" readonly/>
	            <input class="headNormal" id="pri1" name="pri1" type="text" value="Precio" readonly/>
	            <input class="headNormal" id="imp1" name="imp1" type="text" value="Importe" readonly/>
	            <input class="headShorty" id="iva1" name="iva1" type="text" value="Iva" readonly/>                            
            
	            <br />      
	            <input class="shorty" id="uds1" name="uds1" type="text" value="<?php echo $_SESSION['dataWorksInvoice'][$i][2]; ?>" readonly/>
	            <input class="normal" id="pri1" name="pri1" type="text" value="<?php echo formato($_SESSION['dataWorksInvoice'][$i][4]); ?>" readonly/>
	            <input class="normal" id="imp1" name="imp1" type="text" value="<?php echo formato($_SESSION['dataWorksInvoice'][$i][2]*$_SESSION['dataWorksInvoice'][$i][4]);?>" readonly/>
	            <input class="shorty" id="iva1" name="iva1" type="text" value="<?php echo formato($_SESSION['dataWorksInvoice'][$i][5]); ?>" readonly/>

	            <br />              
            </div>
            <?php } ?>

		</div>
            <hr>		
		<div class="subtotals">
			<table>
				<thead>
					<tr>
						<th>Base Imp. al <?php echo $_SESSION['ivaTipo1']?>%</th>
						<th>IVA al <?php echo $_SESSION['ivaTipo1']?>%</th>
						<th>Base Imp. al <?php echo $_SESSION['ivaTipo2']?>%</th>
						<th>IVA al <?php echo $_SESSION['ivaTipo2']?>%</th>
						<th>Base Imp. al <?php echo $_SESSION['ivaTipo3']?>%</th>
						<th>IVA al <?php echo $_SESSION['ivaTipo3']?>%</th>												
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $_SESSION['dataAmounts'][0]; ?></td>
						<td><?php echo $_SESSION['dataAmounts'][1]; ?></td>
						<td><?php echo $_SESSION['dataAmounts'][2]; ?></td>
						<td><?php echo $_SESSION['dataAmounts'][3]; ?></td>
						<td><?php echo $_SESSION['dataAmounts'][4]; ?></td>
						<td><?php echo $_SESSION['dataAmounts'][5]; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="total">
			<label>Importe total S.E.u.O.: <?php echo $_SESSION['dataAmounts'][6]; ?> euros</label>
		</div>
		<div class="payment">
			<label>Forma de pago: <?php echo $_SESSION['dataInfo'][2]; ?></label>
			<label>Vencimiento: <?php echo $_SESSION['dataInfo'][3]; ?></label>			
		</div>
		<div class="footer">
			<p>Compañia de prueba S.L, Registro Mercantil Hoja XX.XXX Folio XXX, Libro X, Tomo X Sección X</p>
		</div>
	</body>
</html>