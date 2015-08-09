<?php 
    include_once 'controllers/WorksController.php';
?>
<!DOCTYPE html>
<html>
    <head>       
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/phpMockupGeneral.css" />
        <link type="text/css" rel="stylesheet" href="css/phpMockupWorks.css" />  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/works.js"></script>   
        <title>Página principal</title>
    </head>
    <body>

    <!-- Cabecera de la página web-->
        <?php include_once 'cabecera.php' ?>

    <!-- Cuerpo de la página web-->
        <form action="mainWorks.php" method="post">
        <div class="bodyClass">
           
                <div id="selectorTrabajos" class="selectorTrabajos">
                    <h2>Selección por fecha</h2>
                    <div>
                        <select id="year" name="year">
                            <option <?php echo $yearSel[0];?> >Todos los años</option>
                            <option <?php echo $yearSel[1];?> >2014</option>
                            <option <?php echo $yearSel[2];?> >2015</option>
                        </select> 
                        <select id="month" name="month" >
                            <option>Todos los meses</option>
                            <option>Enero</option>
                            <option>Febrero</option>
                            <option>Marzo</option>
                            <option>Abril</option>      
                            <option>Mayo</option>
                            <option>Junio</option>
                            <option>Julio</option>
                            <option>Agosto</option> 
                            <option>Septiembre</option>
                            <option>Octubre</option>
                            <option>Noviembre</option>
                            <option>Diciembre</option>                         
                        </select>
                    </div>
                    <br />
                    <h2>Selección por clientes</h2>
                    <div>
                        <select id="customer" name="customer">
                            <option>Todos los clientes</option>
                            <?php getListCustomersName($clienteId) ?>
                        </select>                       
                    </div>
                    <br />
                    <h2>Selección por estado</h2>
                    <div class="radioSelector">
                        <input type="radio" name="situacion" value="todos" <?php echo $chequed1 ?> >Todos los trabajos<br>
                        <input type="radio" name="situacion" value="sin" <?php echo $chequed2 ?> >Sin facturar<br>
                        <input type="radio" name="situacion" value="con" <?php echo $chequed3 ?> >Facturados<br>
                    </div>
                    
                    <div class="buttonSelector">
                        <input type="submit" id="Sel" name="Sel" value="Seleccionar">
                    </div>

                </div>  

                <div id="seleccionTrabajos" class="seleccionTrabajos">
                    <h2>Detalle trabajo</h2>
                    <div>
                        <input id="idwork" name="idwork" type="hidden" value="<?php echo $idWork?>" >
                        <input id="grabW" type="hidden" value="<?php echo $_SESSION['grabW']; ?>">
                        <div class="codeWorks">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label>Número</label>
                                            <input id="codework" name="codework" type="text" value="<?php echo $codigo?>" title="suministrado por la aplicación" readonly />
                                            <label>Cliente</label>
                                            <select id="clientework" name="clientework" <?php echo $editable?> title="seleccione el cliente">
                                                <option>Seleccione cliente</option>
                                                <?php getListCustomersName($clienteId) ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                          <td>
                                            <label>Fecha</label>
                                            <input id="datework" name="datework" type="text" value="<?php echo $fecha?>" <?php echo $writable?> title="introduzca fecha formato dd/mm/aaaa" /> 
                                            <label>Factura</label>
                                            <input id="invoicenumber" name="invoicenumber" type="text" value="<?php echo $factura?>" title="suministrado por la aplicación" readonly/>                                          
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                                                   
                        </div>
                        <div id="mensajeWork"><?php echo $_SESSION['mensajeWork'];?></div>
                        <div class="textWorks">
                            <label>Texto</label>
                            <textarea id="textwork" name="textwork" rows="4" <?php echo $writable?> title="Texto hasta 200 caracteres"><?php echo $texto?></textarea>
                        </div>
                        <br/>
                        <div class="amountsWorks">
                            <table>
                                <tr>
                                    <td><label>Unidades... </label></td>
                                    <td><input id="udswork" name="udswork" type="text" value="<?php echo $cantidad ?>" <?php echo $writable?> title="introduzca cantidad"/></td>
                                    <td><label>Precio... </label></td>
                                    <td><input id="pricework" name="pricework" type="text" value="<?php echo $importe ?>" <?php echo $writable?> title="introduzca importes unitarios" /></td>
                                    <td><label>Iva... </label></td>
                                    <td>
                                        <select id="ivawork" name="ivawork" title="introduzca el % de iva a aplicar" >
                                            <?php echo getListCurrentIva($iva); ?>
                                        </select>
                                        <input id="calcwork" name="calcwork" type="button" value="calc" <?php echo $editable; ?> title="pulse para calcular el trabajo" > 
                                     </td>
                                </tr>
                                <tr>
                                    <td><label>Total... </label></td>
                                    <td><input id="basework" name="basework" type="text" value="<?php echo $base?>" <?php echo $writable?> title="importe del trabajo sin IVA"/></td>           
                                    <td><label> </label></td>
                                    <td><label> Total con IVA... </label></td>
                                    <td><label> </label></td>
                                    <td><input id="amountwork" name="amountwork" type="text" value="<?php echo $total?>" <?php echo $writable?> title="importe total del trabajo con IVA"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <br/>
                        <br/>
                        <div class="buttonsWorks">
                            <button id="grabar" name="grabar" <?php echo $editable; ?> title="Pulse para grabar el trabajo">Grabar</button>
                            <button id="eliminar" name="eliminar" type="submit" <?php echo $usable; ?> title="Pulse para eliminar el trabajo">Eliminar</button>
                            <button id="Borrar" name="borrar" title="Pulse para borrar el formulario" >Borrar</button>
                            <button id="imprimir" class="imprimir" name="imprimir" type="button" <?php echo $usable; ?> title="Pulse para imprimir el trabajo">Imp</button>
                        </div>
                    </div>
                </div>
                <div id="buttonsSelected" class="buttonsSelected">              
                    <?php echo getWorksSelected($yearStart,$yearFinal,$monthStart,$monthFinal,$customerSearched,$opcion) ?>
                </div>
        </div>
        </form>
        
    <!-- Pie de la página web-->
        <?php include_once 'pie.php' ?>
        
    </body>
</html>
