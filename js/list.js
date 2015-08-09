/**
	* * * * * list es el fichero js de mainList.php *** *
    * * @Author musef v.1.0 2015-08-01
*/

$(window).load(function (){
		
		// remarca la seleccion
		$('#trabajos').css('background-color','appworkspace');
		$('#trabajos').css('color','black');		
		$('#facturas').css('background-color','appworkspace');
		$('#facturas').css('color','black');
		$('#clientes').css('background-color','appworkspace');
		$('#clientes').css('color','black');
		$('#listados').css('background-color','black');
		$('#listados').css('color','white');		
		$('#admon').css('background-color','appworkspace');
		$('#admon').css('color','black');

	// inicialmente estan disabled algunos elementos que solo tienen funcion
	// cuando se busca por importes
	$('#fechIni').hide();
	$('#fechFin').hide();
	$('#order').hide();

	// abre la pestaña con los datos para imprimir
	if ($('#newTab').val()=='OK') {
   		// abrir la ventana de impresion independiente
      	window.open('printList.php', '_blank');
		return false;
	}
});

$(document).ready(function(){

	// al seleccionar importes se habilitan componentes de filtros
	$('#listadofacturas').hide();
	$('#listadoclientes').hide();
	$('#listadoImprimir').hide();

	if ($('#opcionFiltro').val()==2) {
		$('h2:last').text('Listado de trabajos por filtros');
		$('#listadoclientes').hide();
		$('#listadofacturas').hide();
		$('#listadotrabajos').show();
	} else 	if ($('#opcionFiltro').val()==1) {
		$('h2:last').text('Listado de facturas por filtros');
		$('#listadoclientes').hide();
		$('#listadofacturas').show();
		$('#listadotrabajos').hide();
	} else {
		$('h2:last').text('Listado de clientes por filtros');
		$('#listadoclientes').show();
		$('#listadofacturas').hide();
		$('#listadotrabajos').hide();
	} 
/**/
	$('#selcust').click(function show(){
		$('h2:last').text('Listado de clientes por filtros');
		$('#listadoclientes').show();
		$('#listadofacturas').hide();
		$('#listadotrabajos').hide();
	});

	$('#selwork').click(function show(){
		$('h2:last').text('Listado de trabajos por filtros');
		$('#listadoclientes').hide();
		$('#listadofacturas').hide();
		$('#listadotrabajos').show();
	});

	$('#selinv').click(function show(){
		$('h2:last').text('Listado de facturas por filtros');
		$('#listadoclientes').hide();
		$('#listadofacturas').show();
		$('#listadotrabajos').hide();
	});

	$('#amounts').change(function changeAmounts(){

		if ($('#amounts').val()=="Sin importes") {
			$('#fechIni').hide();
			$('#fechFin').hide();
			$('#order').hide();
		}
		if ($('#amounts').val()=="Con facturación") {
			$('#provincias').hide();	
			$('#fechIni').fadeIn(1500);
			$('#fechFin').fadeIn(1500);
			$('#order').fadeIn(1500);
			$('#provincias').fadeIn(1500);			
		}

		if ($('#amounts').val()=="Con trabajos") {
			$('#provincias').hide();	
			$('#fechIni').fadeIn(1500);
			$('#fechFin').fadeIn(1500);
			$('#order').fadeIn(1500);
			$('#provincias').fadeIn(1500);		
		}
	});

});