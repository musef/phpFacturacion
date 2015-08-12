<?php 
    include_once 'controllers/CustomersController.php';
?>
<!DOCTYPE html>
<html>
    <head>       
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/phpMockupGeneral.css" />     
        <link rel="stylesheet" type="text/css" href="css/phpMockupCustomers.css">    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/customers.js"></script>   
        <title>Página principal</title>
    </head>
    <body>

    <!-- Cabecera de la página web-->
        <?php include_once 'cabecera.php' ?>

    <!-- Cuerpo de la página web-->
        <form action="mainCustomers.php" method="post">
        <div class="bodyClass">
           
            <div id="selectorClientes" class="selectorClientes">
                <h2>Selección por clientes</h2>
                <div>
                    <select name="customer">
                        <option>Seleccione cliente</option>
                        <?php getListCustomersName($idCust) ?>
                    </select>                        
                </div>
                <h2>Selección por provincia</h2>
                <div>
                    <select id="provincia" name="provincia">
                        <option>Todos las provincias</option>
                    </select>
                </div>
                
                <div class="buttonSelector">
                    <input type="submit" id="Sel" name="Sel" value="Seleccionar" title="Pulse para seleccionar el cliente">
                </div>

            </div>  



                <div id="seleccionClientes" class="seleccionClientes">
                    <h2>Detalle Clientes</h2>
                    <div id="mensaje" class="mensaje"><?php echo $_SESSION["mensajeCust"];?></div>
                    <div>
                        <input id="idcust" name="idcust" type="hidden" value="<?php echo $idCust?>" >
                        <div class="codeCustomers">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label>Nombre del cliente</label>
                                        </td>
                                        <td>
                                            <input id="namecust" name="namecust" type="text" value="<?php echo $name?>" title="Nombre o Razón Social del cliente (Entre 3 y 50 caracteres)" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Dirección</label>
                                        </td>
                                        <td>                                            
                                            <input id="addresscust" name="addresscust" type="text" value="<?php echo $address?>" title="Dirección del cliente (Entre 5 y 50 caracteres)" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Código Postal</label>
                                        </td>
                                        <td>                                            
                                            <input id="cpostcust" name="cpostcust" type="text" value="<?php echo $cpost?>" title="código postal del cliente (5 números)" />
                                        </td>
                                    </tr>    
                                    <tr>
                                        <td>
                                            <label>Localidad</label>
                                        </td>
                                        <td>                                            
                                            <input id="citycust" name="citycust" type="text" value="<?php echo $city?>" title="Localidad fiscal del cliente (Entre 3 y 50 caracteres)" />
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td>
                                            <label>País</label>
                                        </td>
                                        <td>                                            
                                            <select>
                                                <option>España</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>N.I.F.</label>
                                        </td>
                                        <td>                                            
                                            <input id="nifcust" name="nifcust" type="text" value="<?php echo $nif?>" title="N.I.F. del cliente (9 caracteres)" />
                                        </td>
                                    </tr>           
                                    <tr>
                                        <td>
                                            <label>Forma de pago</label>
                                        </td>
                                        <td>                                            
                                            <select  id="formaPago" name="formaPago">
                                                <?php getListFormasPago($formaPago) ?>
                                            </select>
                                        </td>
                                    </tr> 
                                </tbody>
                            </table>                                                   
                        </div>
 
                        <br/>

                        <div class="buttonsCustomers">
                            <button id="grabar" name="grabar" title="Pulse para grabar el cliente">Grabar</button>
                            <button id="eliminar" name="eliminar" type="submit" title="Pulse para eliminar el cliente" <?php echo $usable; ?>>Eliminar</button>
                            <button id="Borrar" name="borrar" title="Pulse para borrar el formulario" >Borrar</button>
                        </div>
                    </div>
                </div>

        </div>
        </form>
        
    <!-- Pie de la página web-->
        <?php include_once 'pie.php' ?>
        
    </body>
</html>