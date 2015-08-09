<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/phpMockupGeneral.css" />
        <link type="text/css" rel="stylesheet" href="css/phpMockupAdmon.css" />
        
        <title>Acceso no autorizado</title>
    </head>
    <body>
    <!-- Cabecera de la página web-->
        <?php include_once 'cabecera.php' ?>

    <!-- Cuerpo de la página web-->        
        <div class="errorClass">
            <h1>Administración</h1>
            <br />
            <h1>Uffff.... ud. no está autorizado para el acceso a esta página :(</h1>
            <form action="mainWorks.php" method="post">
                <button name="volver" type="submit">Volver</button>
            </form>
        </div>
    <!-- Pie de la página web-->
        <?php include_once 'pie.php' ?>        
    </body>
</html>