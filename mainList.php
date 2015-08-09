<?php 
    include_once 'controllers/ListController.php';
?>
<!DOCTYPE html>
<html>
    <head>       
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="css/phpMockupGeneral.css" />     
        <link rel="stylesheet" type="text/css" href="css/phpMockupList.css">    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/list.js"></script>   
        <title>Página principal</title>
    </head>
    <body>
    <!-- Cabecera de la página web-->
        <?php include_once 'cabecera.php' ?>

    <!-- Cuerpo de la página web-->
        
        <div id="bodyClass" class="bodyClass">
          <form action="mainList.php" method="post">
            <input id="newTab" name="tab" type="hidden" value="<?php echo $newTab?>">
            <input id="opcionFiltro" type="hidden" value="<?php echo $_SESSION['opcionFiltro']; ?>">
            
            <div id="selectorListados" class="selectorListados">
                <h2>Listados de clientes</h2>
                <div>
                    <button id="selcust" name="SelCust" type="button" title="Pulse para listados de clientes" >Clientes</button>
                </div>
                <hr>
                <h2>Listados de trabajos</h2>
                <div>
                    <button id="selwork" name="SelWork" type="button" title="Pulse para listados de trabajos" >Trabajos</button>
                </div>
                <hr>
                <h2>Listados de facturas</h2>
                <div >
                    <button id="selinv" name="SelInv" type="button" title="Pulse para listados de facturas" >Facturas</button>
                </div>
            </div>  

            <div id="seleccionListados" class="seleccionListados">
                <h2>Listados por filtros</h2>
                <div id="listadoclientes" class="listadoClientes">
                    <select name="cliIniC">
                        <option >Cliente inicial</option>
                        <?php getListCustomersToList(0) ?>
                    </select>
                    <select name="cliFinC">
                        <option >Cliente final</option>
                        <?php getListCustomersToList(0) ?>
                    </select>                 
                    <select id="fechIni" name="fechIniC">
                        <option>Fecha inicial</option>
                        <?php getListFechas();?>
                    </select>
                    <select id="fechFin" name="fechFinC">
                        <option>Fecha actual</option>
                        <?php getListFechas();?>
                    </select>         
                    <select name="direcC">
                        <option>Con direcciones</option>
                        <option>Sin direcciones</option>
                    </select>              
                    <select id="amounts" name="amountsC">
                        <option>Sin importes</option>
                        <option>Con facturación</option>
                        <option>Con trabajos</option>
                    </select>     
                    <select id="order" name="orderC">
                        <option>Orden alfabético</option>
                        <option>Orden importes</option>
                    </select>    
                    <select id="provincias">
                        <option>Sin selección provincias</option>
                    </select>  
                    <div class="buttonsList">
                        <button name="listarC" title="Pulse para visualizar listado" type="submit">Listar</button>
                        <button name="borrarC" title="Pulse para eliminar filtros" >Limpiar</button>
                    </div>                                                                             
                </div>

                <div id="listadofacturas" class="listadofacturas">
                    <select name="cliIniF">
                        <option >Cliente inicial</option>
                        <?php getListCustomersToList(0) ?>
                    </select>
                    <select name="cliFinF">
                        <option >Cliente final</option>
                        <?php getListCustomersToList(0) ?>
                    </select>                 
                    <select name="fechIniF">
                        <option>Fecha inicial</option>
                        <?php getListFechas();?>
                    </select>
                    <select name="fechFinF">
                        <option>Fecha actual</option>
                        <?php getListFechas();?>
                    </select>                        
                    <select name="orderF">
                        <option>Agrupado</option>
                        <option>Por facturas</option>
                    </select>    
                    <select id="provinciasF">
                        <option>Sin selección provincias</option>
                    </select>    

                    <div class="buttonsList">
                        <button name="listarF" title="Pulse para visualizar listado" type="submit">Listar</button>
                        <button name="borrarF" title="Pulse para eliminar filtros" >Limpiar</button>
                    </div>                                                                            
                </div>

                <div id="listadotrabajos" class="listadotrabajos">
                    <select name="cliIniW">
                        <option >Cliente inicial</option>
                        <?php getListCustomersToList(0) ?>
                    </select>
                    <select name="cliFinW">
                        <option >Cliente final</option>
                        <?php getListCustomersToList(0) ?>
                    </select>                 
                    <select name="fechIniW">
                        <option>Fecha inicial</option>
                        <?php getListFechas();?>
                    </select>
                    <select name="fechFinW">
                        <option>Fecha actual</option>
                        <?php getListFechas();?>
                    </select>                        
                    <select name="orderW">
                        <option>Agrupado</option>
                        <option>Por trabajos</option>
                    </select>    
                    <select id="provinciasW">
                        <option>Sin selección provincias</option>
                    </select>    

                    <div class="buttonsList">
                        <button name="listarW" title="Pulse para visualizar listado" type="submit">Listar</button>
                        <button name="borrarW" title="Pulse para eliminar filtros">Limpiar</button>
                    </div>                                                                            
                </div>                
            </div>

          </form>
        </div>


    <!-- Pie de la página web-->
        <?php include_once 'pie.php' ?>
        
    </body>
</html>