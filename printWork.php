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



?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/phpMockupGeneral.css" />     
        <link rel="stylesheet" type="text/css" href="css/phpMockupPrintWork.css" />      
        <title>Impresión trabajo</title>
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
			<div class="invoiceCustomer">
				<label>Cliente:</label>
				<h3><?php echo $_SESSION['dataCustomer'][0]; ?></h3>
				<h4><?php echo $_SESSION['dataCustomer'][1]; ?></h4>
				<h4><?php echo $_SESSION['dataCustomer'][2]; ?></h4>
				<h4><?php echo $_SESSION['dataCustomer'][3]; ?></h4>
			</div>
			<div class="invoiceRef">
				<h4>Fecha trabajo: <?php  echo $_SESSION['dataInfo'][0]; ?></h4>
				<h4>Número trabajo: <?php  echo $_SESSION['dataInfo'][1]; ?></h4>
			</div>			
		</div>
		<hr>
		<div class="detail">
			<div class="desc">
	            <input class="headSuper" id="des1" name="des1" type="text" value="Descripción" readonly/>
	            <br />
	            <textarea class="super" id="des1" name="des1" readonly><?php echo $_SESSION['dataWork'][0]; ?></textarea>            
	            <br />
        	</div>
            <div class="amount">
	            <input class="headShorty" id="uds1" name="uds1" type="text" value="Uds" readonly/>
	            <input class="headNormal" id="pri1" name="pri1" type="text" value="Precio" readonly/>
	            <input class="headNormal" id="imp1" name="imp1" type="text" value="Importe" readonly/>
	            <input class="headShorty" id="iva1" name="iva1" type="text" value="Iva" readonly/>                            
            
	            <br />      
	            <input class="shorty" id="uds1" name="uds1" type="text" value="<?php echo $_SESSION['dataWork'][1]; ?>" readonly/>
	            <input class="normal" id="pri1" name="pri1" type="text" value="<?php echo formato($_SESSION['dataWork'][2]); ?>" readonly/>
	            <input class="normal" id="imp1" name="imp1" type="text" value="<?php echo formato($_SESSION['dataWork'][1]*$_SESSION['dataWork'][2]);?>" readonly/>
	            <input class="shorty" id="iva1" name="iva1" type="text" value="<?php echo formato($_SESSION['dataWork'][3]); ?>" readonly/>

	            <br />              
            </div>
		</div>
            <hr>		
		<div class="subtotals">
			<table>
				<thead>
					<tr>
						<th>Base Imp. al <?php echo formato($_SESSION['dataWork'][3]); ?>%</th>
						<th>IVA al <?php echo formato($_SESSION['dataWork'][3]); ?>%</th>											
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo formato($_SESSION['dataAmounts'][0]); ?></td>
						<td><?php echo formato($_SESSION['dataAmounts'][1]); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="total">
			<label>Total trabajo con IVA: <?php echo formato($_SESSION['dataAmounts'][2]); ?> euros</label>
		</div>
		<div class="footer">
			<p>Las reclamaciones sobre este trabajo tendrán un plazo de 10 días naturales.</p>
		</div>
	</body>
</html>