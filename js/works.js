/**
	* * * * * works es el fichero js de mainWorks.php *** *
    * * @Author musef v.1.0 2015-08-01
*/

function revisionForm(){
		
	// ponemos en blanco los backgrounds para comenzar revision		
	$('#datework').css('background','white');
	$('#textwork').css('background','white');
	$('#udswork').css('background','white');
	$('#pricework').css('background','white');
	$('#basework').css('background','white');
	$('#amountwork').css('background','white');

	var incorrect=false;
	var mensaje="";
		
	var date=$('#datework').val();
	var text=$('#textwork').val();
	var uds=$('#udswork').val();
	uds=uds.replace(',','.');
	$('#udswork').val(uds);
	var price=$('#pricework').val();
	price=price.replace(',','.');
	$('#pricework').val(price);
	var iva=$('#ivawork').val();
	var base=$('#basework').val();
	base=base.replace(',','.');
	$('#basework').val(base);
	var total=$('#amountwork').val();
	total=total.replace(',','.');
	$('#amountwork').val(total);

	var cliente=$('#clientework').val();

	if (cliente=='Seleccione cliente') {
		incorrect=true;
		mensaje=mensaje+"Debe seleccionar un cliente\n";
	}

	if (date.length!=10) {
		incorrect=true;
		mensaje=mensaje+"Formato de fecha incorrecto: debe medir 10 caracteres\n";
		$('#datework').css('background','red');
	} else {
		var day=date.substr(0,2);
		var month=date.substr(3,2);
		var year=date.substr(6);
		if (isNaN(day)||isNaN(month)||isNaN(year)) {
			// si las fechas no son numero
			incorrect=true;
			mensaje=mensaje+"Formato de fecha incorrecto: debe ser DD/MM/AAAA\n";
			$('#datework').css('background','red');
		} else if (day<1 || day>31 || month<1 || month>12 || year<2014 || year>2099) {
			// si las fechas son numeros fuera de los rangos
			incorrect=true;
			mensaje=mensaje+"Formato de fecha incorrecto: debe ser DD/MM/AAAA\n";
			$('#datework').css('background','red');
		} else {
			date=day+"/"+month+"/"+year;	
		}
	}

	if (text.length<1 || text.length>200) {
		incorrect=true;
		mensaje=mensaje+"Longitud de texto incorrecta: debe tener entre 1 y 200 caracteres\n";
		$('#textwork').css('background','red');
	}

	if (isNaN(uds) || uds.length<1) {
		incorrect=true;
		mensaje=mensaje+"Uds inadecuadas: debe ser un número\n";
		$('#udswork').css('background','red');	
	}

	if (isNaN(price) || price.length<1) {
		incorrect=true;
		mensaje=mensaje+"Precio inadecuado: debe ser un número\n";
		$('#pricework').css('background','red');	
	}

	if (isNaN(base) || base.length<1) {
		incorrect=true;
		mensaje=mensaje+"Base inadecuada: debe ser un número\n";
		$('#basework').css('background','red');	
	}

	if (isNaN(total) || total.length<1) {
		incorrect=true;
		mensaje=mensaje+"Total inadecuado: debe ser un número\n";
		$('#amountwork').css('background','red');	
	}

	if (incorrect==true) {
		mensaje="Se han encontrado los siguientes errores:\n"+mensaje;
		alert(mensaje);
		return false;
	} else {
		var bs=Math.round(uds*price*100)/100;
		var ct=Math.round(bs*iva)/100;
		var tt=Math.round((bs+ct)*100)/100;
		if ((total-tt)>0.01 || (total-tt)<-0.01) {
			return confirm('Existe una disparidad entre importes\n introducidos y el total de factura \n¿Desea grabar el trabajo?');
		}
	}

	return true;
}


$(window).load(function showDisplay(){

	// remarca la seleccion
	$('#trabajos').css('background-color','black');
	$('#trabajos').css('color','white');
	$('#facturas').css('background-color','appworkspace');
	$('#facturas').css('color','black');
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
	var data=$("#buttonsSelected ul li:first").text();

	if (data.length<1) {
		$('#buttonsSelected').hide();
	} else {
		$('#seleccionTrabajos').fadeTo(1000,0.2);
		$('#buttonsSelected').css('border','1px solid');			
		$('#buttonsSelected').fadeIn();
	}		

	// deshabilita el componente meses, si procede
	if ($('#year').val()=='Todos los años') {
		$('#month').hide();
	} 
	// muestra, brevemente, el mensaje si es que lo hay
	$('#mensajeWork').fadeIn();
	$('#mensajeWork').fadeOut(2000);
	//$('#mensaje').hide(2000);

	$('#grabar').focus();

	// impresion del albaran en pantalla
	$("#imprimir").click(function() {
		if (revisionForm()==true) {
   			// abrir la ventana de impresion independiente
      		window.open('printWork.php', '_blank');
      		return false;
		} else {
			alert("No es posible imprimir el trabajo");
		}

		});

	// impresión de la factura nueva
	if ($('#grabW').val()=='OK') {
		var imprimir=confirm('¿Desea imprimir el trabajo?');
		$('#grabW').val()=='';
		if (imprimir==true) {
			window.open('printWork.php', '_blank');
		    return true;
		} else return true;
	}   		

});


$(document).ready(function(){

	// pulsado boton salir
	$('#exit').click(function(){
		return confirm('¿Desea abandonar la aplicación?');
	});
	
	
	$('#year').change(function changeSel(){
		var yearSel=$('#year').val();
		if (yearSel=='Todos los años') {
			$('#month').hide();
		} else {
			$('#month').show();
		}	
	});

	// pulsado boton closeWindow de la ventana con los trabajos
	$('#closeWindow').click(function(){		
		$('#buttonsSelected').hide();
		// por algun motivo, hay que activar la opacity porque fadeIn no funciona aqui
		$('#seleccionTrabajos').css('opacity','1');
		$('#seleccionTrabajos').fadeIn();
	});

	// pulsado boton seleccion le pasa el focus a grabar
	$('#Sel').click(function(){
		$('#grabar').focus();
	});

	// pulsado boton eliminar
	$('#eliminar').click(function(){
		return confirm('¿Está seguro de querer borrar este trabajo?\nSi confirma, los datos no podrán recuperarse');
	});

	// pulsado boton grabar. Realiza la revision del formulario
	$('#grabar').click(function(){
		return revisionForm();		
	});

	// realiza el calculo de los datos del trabajo despues de hacer revision del formulario
	$('#calcwork').click(function calcular(){ 

		$('#udswork').css('background','white');
		$('#pricework').css('background','white');
		$('#basework').css('background','white');
		$('#amountwork').css('background','white');

		incorrect=false;
		mensaje="";
		var uds=$('#udswork').val();
		uds=uds.replace(',','.');
		$('#udswork').val(uds);
		var price=$('#pricework').val();
		price=price.replace(',','.');
		$('#pricework').val(price);
		var iva=$('#ivawork').val();

		if (isNaN(uds) || uds.length==0) {
			incorrect=true;
			mensaje=mensaje+"No puedo calcular: cantidad debe contener un número\n";
			$('#udswork').css('background','red');	
		}

		if (isNaN(price) || price.length==0) {
			incorrect=true;
			mensaje=mensaje+"No puedo calcular: importe debe ser un número\n";
			$('#pricework').css('background','red');	
		}

		if (incorrect==true) {
			mensaje="Se han encontrado los siguientes errores:\n"+mensaje;
			alert(mensaje);
			return false;
		}

		var base=Math.round(uds*price*100)/100;
		var cuota=Math.round(base*iva)/100;
		var total=Math.round((base+cuota)*100)/100;

		$('#basework').val(base);
		$('#amountwork').val(total);

		return true;
	});
});