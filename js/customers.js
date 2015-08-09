/**
	* * * * * customers es el fichero js de mainCustomers.php *** *
    * * @Author musef v.1.0 2015-08-01
*/

$(window).load(function showDisplay(){

		// remarca la seleccion
		$('#trabajos').css('background-color','appworkspace');
		$('#trabajos').css('color','black');		
		$('#facturas').css('background-color','appworkspace');
		$('#facturas').css('color','black');
		$('#clientes').css('background-color','black');
		$('#clientes').css('color','white');		
		$('#listados').css('background-color','appworkspace');
		$('#listados').css('color','black');
		$('#admon').css('background-color','appworkspace');
		$('#admon').css('color','black');

	// muestra, brevemente, el mensaje si es que lo hay
	$('#mensaje').fadeIn();
	$('#mensaje').fadeOut(2000);
	//$('#mensaje').hide(2000);
});

$(document).ready(function(){
	
	// pulsado boton eliminar
	$('#eliminar').click(function(){
		if ($('#idcust').val()<1) {
			alert('No hay cliente seleccionado');
			return false;
		}

		return confirm('¿Está seguro de querer eliminar este cliente?\nSi confirma, los datos no podrán recuperarse');
	});

		// pulsado boton grabar. Realiza la revision del formulario
	$('#grabar').click(function(){
		
		// ponemos en blanco los backgrounds para comenzar revision		
		$('#namecust').css('background','white');
		$('#addresscust').css('background','white');
		$('#cpostcust').css('background','white');
		$('#citycust').css('background','white');
		$('#nifcust').css('background','white');

		var incorrect=false;
		var mensaje="";
		var name=$('#namecust').val();
		var address=$('#addresscust').val();
		var cpost=$('#cpostcust').val();
		var city=$('#citycust').val();
		var nif=$('#nifcust').val();				


		if (name.length<3 || name.length>50) {
			incorrect=true;
			mensaje=mensaje+"Longitud de nombre incorrecta: debe tener entre 3 y 50 caracteres\n";
			$('#namecust').css('background','red');
		}

		if (address.length<5 || address.length>50) {
			incorrect=true;
			mensaje=mensaje+"Longitud de dirección incorrecta: debe tener entre 5 y 50 caracteres\n";
			$('#addresscust').css('background','red');
		}

		if (city.length<3 || city.length>50) {
			incorrect=true;
			mensaje=mensaje+"Longitud de localidad incorrecta: debe tener entre 3 y 50 caracteres\n";
			$('#citycust').css('background','red');
		}		

		if (cpost.length!=5) {
			incorrect=true;
			mensaje=mensaje+"Formato de código postal incorrecto: debe medir 5 caracteres\n";
			$('#cpostcust').css('background','red');
		} else {
			if (isNaN(cpost)) {
				incorrect=true;
				mensaje=mensaje+"El código postal debe ser un número\n";
				$('#cpostcust').css('background','red');	
			}
		}

		if (nif.length!=9) {
			incorrect=true;
			mensaje=mensaje+"Formato de NIF incorrecto: debe medir 9 caracteres\n";
			$('#nifcust').css('background','red');
		}

		if (incorrect==true) {
			mensaje="Se han encontrado los siguientes errores:\n"+mensaje;
			alert(mensaje);
			return false;
		}

		return true;
	});		

});