/**
	* * * * * index es el fichero js de index.php *** *
    * * @Author musef v.1.0 2015-08-01
*/

function animacion() {

	$("h1").fadeTo(3000,0.2);
	$("h1").fadeIn(3000);
    $("h1").animate({
        left: '450px',
        opacity: '0.5',
        fontSize: '4em'
    });

    for (var i=0;i<1000000;i++) {}
    
    $("h1").animate({
        left: '450px',
        opacity: '0.5',
        fontSize: '4em'
    });    	

	for (var i=0;i<1000000;i++) {}
    $("h1").animate({
        left: '425px',
        opacity: '1',
        fontSize: '3em'
    });  
}



$(window).load(function(){

	if ($('h1').text()!='') {
		animacion();
	} 

});

$(document).ready(function(){
	$('#enviar').click(function identificarse(){
		
		$('#login').css('background','white');
		$('#pass').css('background','white');

		var correct=false;
		var mensaje="";
		var log=$('#login').val();
		var pas=$('#pass').val();

		if (log.length<6 || log.length>15) {
			correct=true;
			mensaje=mensaje+"Longitud de login incorrecta: debe tener entre 6-15 caracteres\n";
			$('#login').css('background','red');
		}

		if (pas.length<6 || pas.length>15) {
			correct=true;
			mensaje=mensaje+"Longitud de password incorrecta: debe tener entre 6-15 caracteres\n";
			$('#pass').css('background','red');
		}

		if (correct==true) {
			mensaje="Se han encontrado los siguientes errores:\n"+mensaje;
			alert(mensaje);
			return false;
		}

		return true;
	});
});