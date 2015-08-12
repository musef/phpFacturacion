/**
	* * * * * invoices es el fichero js de mainInvoices.php *** *
    * * @Author musef v.1.0 2015-08-01
*/

$(window).load(function (){

	// remarca la seleccion
	$('#facturas').css('background-color','black');
	$('#facturas').css('color','white');
	$('#trabajos').css('background-color','appworkspace');
	$('#trabajos').css('color','black');		
	$('#clientes').css('background-color','appworkspace');
	$('#clientes').css('color','black');
	$('#listados').css('background-color','appworkspace');
	$('#listados').css('color','black');
	$('#admon').css('background-color','appworkspace');
	$('#admon').css('color','black');				

	// funciona en base a la lista de clientes seleccionada
	// si no ha habido seleccion, se oculta el marco de trabajos seleccionados vacío
	// y si hay trabajos, se lleva a fade el principal, y se muestra el marco con los trabajos
	// se controla mediante la existencia de li. Si existe el primero, pues se muestra el div
	var dataWorks=$("#buttonsSelected ul li:first").text();
	if (dataWorks.length<1) {
		$('#buttonsSelected').hide();
	} else {
		// muestra la ventana de seleccion de trabajos
		$('#seleccionFacturas').fadeTo(1000,0.2);
		$('#buttonsSelected').css('border','1px solid');
		$('#buttonsSelected').fadeIn();
	}		

	// lo mismo que lo anterior, pero para mostrar las facturas
	var dataInvoices=$("#invoicesSelected ul li:first").text();
	if (dataInvoices.length<1) {
		$('#invoicesSelected').hide();
	} else {
		// muestra la ventana de seleccion de facturas
		$('#seleccionFacturas').fadeTo(1000,0.2);
		$('#invoicesSelected').css('border','1px solid');
		$('#invoicesSelected').fadeIn();
	}	

	// muestra, brevemente, el mensaje si es que lo hay
	$('#mensaje').fadeIn();
	$('#mensaje').fadeOut(2000);
	//$('#mensaje').hide(2000);

	// impresión de la factura en revisión
	$("#imprimir").click(function() {
   		// abrir la ventana de impresion independiente
      window.open('printInvoice.php', '_blank');
      return false;
   	});

	// impresión de la factura nueva
	if ($('#grab').val()=='OK') {
		var imprimir=confirm('¿Desea imprimir la factura?');
		$('#grab').val()=='';
		if (imprimir==true) {
			window.open('printInvoice.php', '_blank');
		    return true;
		} else return true;
	}

});

$(document).ready(function(){

	// pulsado boton salir
	$('#exit').click(function(){
		return confirm('¿Desea abandonar la aplicación?');
	});
	
	// pulsado boton seleccion le pasa el focus a grabar
	$('#Sel').click(function(){
		$('#invoicesSelected').hide();
		$('#grabar').focus();

		if ($('#customerInvoices').val()==0) {
			alert("Seleccione un cliente");
		}
	});

	// pulsado boton closeWindow de la ventana con los trabajos
	$('#closeWindow').click(function(){
		$('#buttonsSelected').hide();
		$('#grabar').focus();
	});

	// pulsado boton seleccion le pasa el focus a grabar
	$('#SelF').click(function(){
		$('#buttonsSelected').hide();
		$('#grabar').focus();

		if ($('#customerInvoices').val()==0) {
			alert("Seleccione un cliente");
		} 
	});

	$('#formaPago').change(function(){
		$('#vencimiento').text(" Vto: se recalculará al grabar");
		submit();
	});

	// pulsado boton closeWindow de la ventana con las facturas
	$('#closeWindowF').click(function(){
		$('#invoicesSelected').hide();
		$('#grabar').focus();
	});	

		// pulsado boton eliminar
	$('#eliminar').click(function(){
		return confirm('¿Está seguro de querer borrar esta factura?\nSi confirma, los datos no podrán recuperarse');
	});

			// pulsado boton grabar
	$('#grabar').click(function(){
		
		// ponemos en blanco los backgrounds para comenzar revision		
		$('#dateinvoice').css('background','white');
		$('#baseinv1').css('background','white');
		$('#baseinv2').css('background','white');
		$('#baseinv3').css('background','white');
		$('#cuoinv1').css('background','white');
		$('#cuoinv2').css('background','white');
		$('#cuoinv3').css('background','white');
		$('#amountinv').css('background','white');		

		var incorrect=false;
		var mensaje="";

		var date=$('#dateinvoice').val();

		var iva1=$('#cuoinv1').val();
		iva1=iva1.replace('.','');			
		iva1=iva1.replace(',','.');

		var iva2=$('#cuoinv2').val();
		iva2=iva2.replace('.','');			
		iva2=iva2.replace(',','.');

		var iva3=$('#cuoinv3').val();
		iva3=iva3.replace('.','');		
		iva3=iva3.replace(',','.');	

		var base1=$('#baseinv1').val();
		base1=base1.replace('.','');		
		base1=base1.replace(',','.');

		var base2=$('#baseinv2').val();
		base2=base2.replace('.','');		
		base2=base2.replace(',','.');

		var base3=$('#baseinv3').val();
		base3=base3.replace('.','');
		base3=base3.replace(',','.');				

		var total=$('#amountinv').val();
		total=total.replace('.','');			
		total=total.replace(',','.');

		if (date.length!=10) {
			incorrect=true;
			mensaje=mensaje+"Formato de fecha incorrecto: debe medir 10 caracteres\n";
			$('#dateinvoice').css('background','red');
		} else {
			var day=date.substr(0,2);
			var month=date.substr(3,2);
			var year=date.substr(6);
			if (isNaN(day)||isNaN(month)||isNaN(year)) {
				incorrect=true;
				mensaje=mensaje+"Formato de fecha incorrecto: debe ser DD/MM/AAAA\n";
				$('#dateinvoice').css('background','red');	
			} else if (day<1 || day>31 || month<1 || month>12 || year<2014 || year>2099) {
				// si las fechas son numeros fuera de los rangos
				incorrect=true;
				mensaje=mensaje+"Formato de fecha incorrecto: debe ser DD/MM/AAAA\n";
				$('#datework').css('background','red');
			} else {
				date=day+"/"+month+"/"+year;	
			}	
		}		

		if($('#customerName').val().length<1) {
			incorrect=true;
			mensaje=mensaje+"No hay cliente seleccionado\n";			
		}

		if(($('#cod1').val()=='') || ($('#des1').val()=='') ) {
			incorrect=true;
			mensaje=mensaje+"No hay trabajos para facturar\n";			
		}

		if (isNaN(iva1) || iva1.length<1) {
			incorrect=true;
			mensaje=mensaje+"IVA 1 inadecuado: debe ser un número\n";
			$('#cuoinv1').css('background','red');	
		}

		if (isNaN(iva2) || iva2.length<1) {
			incorrect=true;
			mensaje=mensaje+"IVA 2 inadecuado: debe ser un número\n";
			$('#cuoinv2').css('background','red');	
		}

		if (isNaN(iva3) || iva3.length<1) {
			incorrect=true;
			mensaje=mensaje+"IVA 3 inadecuado: debe ser un número\n";
			$('#cuoinv3').css('background','red');	
		}		

		if (isNaN(base1) || base1.length<1) {
			incorrect=true;
			mensaje=mensaje+"Base 1 inadecuada: debe ser un número\n";
			$('#baseinv1').css('background','red');	
		}

		if (isNaN(base2) || base2.length<1) {
			incorrect=true;
			mensaje=mensaje+"Base 2 inadecuada: debe ser un número\n";
			$('#baseinv2').css('background','red');	
		}

		if (isNaN(base3) || base3.length<1) {
			incorrect=true;
			mensaje=mensaje+"Base 3 inadecuada: debe ser un número\n";
			$('#baseinv3').css('background','red');	
		}		

		if (isNaN(total) || total.length<1) {
			incorrect=true;
			mensaje=mensaje+"Total inadecuado: debe ser un número\n";
			$('#amountinv').css('background','red');	
		}

		if (incorrect==true) {
			mensaje="Se han encontrado los siguientes errores:\n"+mensaje;
			alert(mensaje);
			return false;
		} else {
			var bs=Math.round((Number(base1)+Number(base2)+Number(base3))*100)/100;
			var ct=Math.round((Number(iva1)+Number(iva2)+Number(iva3))*100)/100;
			var tt=bs+ct;
			if ((total-tt)>0.01 || (tt-total)>0.01) {
				return confirm('Existe una disparidad entre importes\n introducidos y el total de factura \n¿Confirma grabar la factura?');
			} else {
				return confirm('¿Confirma grabar la factura?');
			}
		}
			
	});



})