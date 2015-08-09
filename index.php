<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        
        <link type="text/css" rel="stylesheet" href="css/phpMockupGeneral.css" />
        <link type="text/css" rel="stylesheet" href="css/phpMockupIndex.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/index.js"></script>         
        <title>Welcome</title>
    </head>
    <body>
        <h1>Bienvenido a fmsFacturación</h1>
        <div class="identificacion">
            <form action="controllers/IndexController.php" method="post">
                <fieldset>
                    <legend>Identificación</legend>
                    <table>
                        <tbody>
                            <tr>
                                <td><label>Usuario</label></td>
                                <td><input id="login" name="login" type="text" title="Entre 6 y 15 caracteres"/></td>
                            </tr>
                            <tr>
                                <td><label>Contraseña</label></td>
                                <td><input id="pass" name="pass" type="password" title="Entre 6 y 15 caracteres"/></td>
                            </tr>                            
                        </tbody>
                    </table>
                    <br />
                    <table>
                        <tbody>
                            <tr>
                                <td><button id="enviar" name="enviar" type="submit" value="Identificarse" title="pulse para identificarse en la aplicación">Identificarse</button></td>
                                <td><button id="crear" name="crear" type="submit" value="Nuevo usuario" title="pulse para crear un nuevo usuario">Nuevo usuario</button></td>
                            </tr>                            
                        </tbody>
                    </table>                    
                </fieldset>
            </form>          
        </div>
    </body>
</html>
