<?php 
    include_once 'controllers/InvoicesController.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/phpMockupGeneral.css" />
        <link type="text/css" rel="stylesheet" href="css/phpMockupInvoices.css" />  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/invoices.js"></script>   
        <title>Página principal</title>
    </head>
    <body>

    <!-- Cabecera de la página web-->
        <?php include_once 'cabecera.php' ?>

    <!-- Cuerpo de la página web-->
        <form action="mainInvoices.php" method="post">
        <div class="bodyClass">
            <div class="facturas">
                <div class="selectorTrabajos">
                    <h3>Seleccione cliente</h3>
                    <div>
                        <!-- SELECT DE NOMBRES-->    
                        <?php getListCustomersNameToInvoices($clienteId) ?>                      
                    </div>
                    <hr />                    
                    <h3>Facturación trabajos</h3>
                    <div class="radioselector">
                        <input type="radio" name="seleccion" value="0" <?php echo $ch0; ?> >Todos los trabajos<br>
                        <input type="radio" name="seleccion" value="1" <?php echo $ch1; ?> >Trabajos del mes<br>
                        <input type="radio" name="seleccion" value="2" <?php echo $ch2; ?> >Trabajos del trimestre<br>
                    </div>
                    <div class="buttonSelector">
                        <input type="submit" id="Sel" name="Sel" value="Seleccionar" title="pulse para mostrar los trabajos pendientes del cliente" <?php echo $controlButton1;?> >
                    </div> 
                    <hr />                    
                    <h3>Facturas Emitidas</h3>
                    <div class="radioselector">
                        <input type="radio" name="seleccionF" value="0" <?php echo $chF0; ?> >Todas las facturas<br>
                        <input type="radio" name="seleccionF" value="1" <?php echo $chF1; ?> >Facturas del mes<br>
                        <input type="radio" name="seleccionF" value="2" <?php echo $chF2; ?> >Facturas del trimestre<br>
                    </div>

                    <div class="buttonSelector">
                        <input type="submit" id="SelF" name="SelF" value="Seleccionar" title="pulse para mostrar las facturas del cliente" <?php echo $controlButton2;?>>
                    </div>                                        
                </div>

                
                <div id="seleccionFacturas" class="seleccionFacturas">
                    <h2>Detalle factura</h2>
                    <div>
                        <div class="codeInvoices">
                            <input id="grab" type="hidden" value="<?php echo $_SESSION['grab']; ?>">
                            <label>Número</label>
                            <input id="codeinvoice" class="codeinvoice" name="codeinvoice" type="text" value="<?php echo $numberInvoice;?>" readonly/>
                            <label>Cliente</label>
                            <input id="customerName" class="customerinvoice" name="customerinvoice" type="text" value="<?php echo $_SESSION['nameCustomer'];?>" readonly/>
                            <label>Fecha</label>
                            <input id="dateinvoice" class="dateinvoice" name="dateinvoice" type="text" value="<?php echo $dateInvoice;?>" />
                        </div>
                        <div id="mensaje"><?php echo $_SESSION['mensajeInvoice'];?></div>
                        <div class="selectedWorks">
                                <input class="headNormal" name="cod" type="text" value="Código" readonly/>
                                <input class="headShorty" name="uds" type="text" value="Uds" readonly/>
                                <input class="headSuper"  name="des" type="text" value="Descripción" readonly/>
                                <input class="headNormal" name="imp" type="text" value="Importe" readonly/>
                                <input class="headShorty" name="iva" type="text" value="Iva" readonly/>                            
                            <br />
                            <input name="wk1" type="hidden" value="<?php echo $_SESSION['dataInvoice'][0][0]; ?>" >
                            <input class="normal" id="cod1" name="cod1" type="text" value="<?php echo $_SESSION['dataInvoice'][0][1]; ?>" readonly/>
                            <input class="shorty" id="uds1" name="uds1" type="text" value="<?php echo $_SESSION['dataInvoice'][0][2]; ?>" readonly/>
                            <input class="super"  id="des1" name="des1" type="text" value="<?php echo $_SESSION['dataInvoice'][0][3]; ?>" readonly/>
                            <input class="normal" id="imp1" name="imp1" type="text" value="<?php echo $_SESSION['dataInvoice'][0][4]; ?>" readonly/>
                            <input class="shorty" id="iva1" name="iva1" type="text" value="<?php echo $_SESSION['dataInvoice'][0][5]; ?>" readonly/>
                            <input class="xsmall" id="del1" name="del1" type="submit" value="X" title="pulse para eliminar" >
                            <br />
                            <input name="wk2" type="hidden" value="<?php echo $_SESSION['dataInvoice'][1][0]; ?>" >
                            <input class="normal" id="cod2" name="cod2" type="text" value="<?php echo $_SESSION['dataInvoice'][1][1]; ?>" readonly/>
                            <input class="shorty" id="uds2" name="uds2" type="text" value="<?php echo $_SESSION['dataInvoice'][1][2]; ?>" readonly/>
                            <input class="super"  id="des2" name="des2" type="text" value="<?php echo $_SESSION['dataInvoice'][1][3]; ?>" readonly/>
                            <input class="normal" id="imp2" name="imp2" type="text" value="<?php echo $_SESSION['dataInvoice'][1][4]; ?>" readonly/>
                            <input class="shorty" id="iva2" name="iva2" type="text" value="<?php echo $_SESSION['dataInvoice'][1][5]; ?>" readonly/>
                            <input class="xsmall" id="del2" name="del2" type="submit" value="X" title="pulse para eliminar" >                           
                            <br />
                            <input name="wk3" type="hidden" value="<?php echo $_SESSION['dataInvoice'][2][0]; ?>" >
                            <input class="normal" id="cod3" name="cod3" type="text" value="<?php echo $_SESSION['dataInvoice'][2][1]; ?>" readonly/>
                            <input class="shorty" id="uds3" name="uds3" type="text" value="<?php echo $_SESSION['dataInvoice'][2][2]; ?>" readonly/>
                            <input class="super"  id="des3" name="des3" type="text" value="<?php echo $_SESSION['dataInvoice'][2][3]; ?>" readonly/>
                            <input class="normal" id="imp3" name="imp3" type="text" value="<?php echo $_SESSION['dataInvoice'][2][4]; ?>" readonly/>
                            <input class="shorty" id="iva3" name="iva3" type="text" value="<?php echo $_SESSION['dataInvoice'][2][5]; ?>" readonly/>                            
                            <input class="xsmall" id="del3" name="del3" type="submit" value="X" title="pulse para eliminar" >
                            <br />
                            <input name="wk4" type="hidden" value="<?php echo $_SESSION['dataInvoice'][3][0]; ?>" >
                            <input class="normal" id="cod4" name="cod4" type="text" value="<?php echo $_SESSION['dataInvoice'][3][1]; ?>" readonly/>
                            <input class="shorty" id="uds4" name="uds4" type="text" value="<?php echo $_SESSION['dataInvoice'][3][2]; ?>" readonly/>
                            <input class="super"  id="des4" name="des4" type="text" value="<?php echo $_SESSION['dataInvoice'][3][3]; ?>" readonly/>
                            <input class="normal" id="imp4" name="imp4" type="text" value="<?php echo $_SESSION['dataInvoice'][3][4]; ?>" readonly/>
                            <input class="shorty" id="iva4" name="iva4" type="text" value="<?php echo $_SESSION['dataInvoice'][3][5]; ?>" readonly/>                               
                            <input class="xsmall" id="del4" name="del4" type="submit" value="X" title="pulse para eliminar" >
                            <br />
                            <input name="wk5" type="hidden" value="<?php echo $_SESSION['dataInvoice'][4][0]; ?>" >
                            <input class="normal" id="cod5" name="cod5" type="text" value="<?php echo $_SESSION['dataInvoice'][4][1]; ?>" readonly/>
                            <input class="shorty" id="uds5" name="uds5" type="text" value="<?php echo $_SESSION['dataInvoice'][4][2]; ?>" readonly/>
                            <input class="super"  id="des5" name="des5" type="text" value="<?php echo $_SESSION['dataInvoice'][4][3]; ?>" readonly/>
                            <input class="normal" id="imp5" name="imp5" type="text" value="<?php echo $_SESSION['dataInvoice'][4][4]; ?>" readonly/>
                            <input class="shorty" id="iva5" name="iva5" type="text" value="<?php echo $_SESSION['dataInvoice'][4][5]; ?>" readonly/>                                                                                                            
                            <input class="xsmall" id="del5" name="del5" type="submit" value="X" title="pulse para eliminar" >
                        </div>

                        <div class="amountsInvoices">
                            <table>
                                <tr>
                                    <td><label>Base Imp.</label></td>
                                    <td><input id="baseinv1" name="baseinv1" type="text" value="<?php echo $bs1; ?>"/></td>
                                    <td><label>Base Imp.</label></td>
                                    <td><input id="baseinv2" name="baseinv2" type="text" value="<?php echo $bs2; ?>"/></td>
                                    <td><label>Base Imp.</label></td>
                                    <td><input id="baseinv3" name="baseinv3" type="text" value="<?php echo $bs3; ?>"/></td>
                                </tr>
                                <tr>
                                    <td><label>Iva <?php echo $_SESSION['ivaTipo1'];?>%  </label></td>
                                    <td><input id="cuoinv1" name="cuoinv1" type="text" value="<?php echo $cu1; ?>"/></td>
                                    <td><label>Iva <?php echo $_SESSION['ivaTipo2'];?>% </label></td>
                                    <td><input id="cuoinv2" name="cuoinv2" type="text" value="<?php echo $cu2; ?>"/></td>
                                    <td><label>Iva <?php echo $_SESSION['ivaTipo3'];?>% </label></td>
                                    <td><input id="cuoinv3" name="cuoinv3" type="text" value="<?php echo $cu3; ?>"/></td>
                                </tr>                                
                                <tr>
                                    <td></td>
                                    <td></td>           
                                    <td><label> </label></td>
                                    <td><label> Total... </label></td>
                                    <td><label> </label></td>
                                    <td><input id="amountinv" name="amountinv" type="text" value="<?php echo $ttl; ?>"/></td>
                                </tr>
                            </table>
                        </div>
                        <div class="paimentSelector">
                            <label>Forma de pago: </label>
                            <select  id="formaPago" name="formaPago">
                                <?php getListFormasPagoToInvoice($_SESSION['formaDpago']) ?>
                            </select>
                            <input id="vencimiento" name="vencimiento" type="text" value="<?php echo $vencimiento;?>" readonly>
                        </div>
                        <div class="buttonsInvoices">
                            <button id="grabar" name="grabar" title="Pulse para grabar la factura">Grabar</button>
                            <button id="eliminar" name="eliminar" type="submit" title="Pulse para eliminar la factura" <?php echo $controlButton3;?> >Eliminar</button>
                            <button id="Borrar" name="borrar" type="submit" title="Pulse para borrar el formulario">Borrar</button>
                            <button id="imprimir" class="imprimir" name="imprimir" type="submit" title="Pulse para imprimir la factura" <?php echo $controlButton3;?>  >Imp</button>
                        </div>
                    </div>
                </div>
                <div id="buttonsSelected" class="buttonsSelected">              
                    <?php if ($divToShow==1) echo getWorksSelectedToInvoice($customerSearched,$opcion); ?>
                </div>                
                <div id="invoicesSelected" class="invoicesSelected">              
                    <?php if ($divToShow==2) echo getInvoicesSelectedToEdit($customerSearched,$opcionF); ?>
                </div>
                <input id="showlist" name="showlist" type="hidden" value="<?php echo $divToShow; ?>">           
            </div>
        </div>
        </form>
        
    <!-- Pie de la página web-->
        <?php include_once 'pie.php' ?>
    </body>
</html>
