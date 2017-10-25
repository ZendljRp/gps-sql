<?php
include 'conecction/conecction.php';

if (!empty($_POST["btnBusquedaTelefono"])OR !empty($_GET["strInput"])) {
    $conn = conn();
    $strData = "";
    $addSQL  = "";
    $strResp = "";
    $seleccione="";
    $predni  = $_REQUEST["strInput"];
    $DNI = str_replace("'", "", $predni);
    $agency = $_POST["slctAgency"];

    if(!empty($agency)){
        $addSQL = " AND varOperador = 'DIRCON'";
        //$addSQL = " AND varOperador = '" . $agency . "'";
    }else{
        $addSQL = " AND varOperador = 'DIRCON'";
        //$addSQL = "";
    }
    //$limit =($pn - 1)* $itemsPerPage;
    //$next_page = $itemsPerPage;
    //$offset = 0;
    $sql = "SELECT datfechagestion AS fechagestion,
                   varNumerotelefonico  AS Numerotelefonico,
                   varrut AS DNI, varcodigorespuesta AS Estado, varobservaciones AS Observacion,
                   varagente AS Agente 
                   FROM ga_gestiones 
                   /*INNER JOIN estadosgestion AS datatelf on ga_gestiones.intestado = datatelf.intEstado*/ 
                   WHERE varrut LIKE '%{$DNI}%';"; 

    $sqlestado = "SELECT  varDesautodial As Descripcion
                   FROM estadosgestion"; 

    $stmt = sqlsrv_query($conn, $sql);
    if( $stmt === false ) {
        $hay= 0;
        if( ($errors = sqlsrv_errors() ) != null) {
            foreach( $errors as $error ) {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
            }
        //Codigo necesario para terminar la ejecución con gracia
        }
    }
    $stmtestado = sqlsrv_query($conn, $sqlestado);
    if( $stmtestado === false ) {
        $hay= 0;
        if( ($errors = sqlsrv_errors() ) != null) {
            foreach( $errors as $error ) {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
            }
        //Codigo necesario para terminar la ejecución con gracia
        }
    }
    
  
    $i =1;
    //$result = sqlsrv_execute($stmt);
    while( $row = sqlsrv_fetch_object($stmt) ) {

           $strestado="<select name='estados' >"; 
          while( $rowEstado = sqlsrv_fetch_object($stmtestado) ) {
            $seleccione=($row->Estado==$rowEstado->Descripcion)?"selected":"";
       
                $strestado .= "<option ".$rowEstado->Descripcion."</option>";
            }
            $strestado .= "</select>";

        $strData .= "<tr>
                        <td>".$i."</td>
                        <td style='text-align:center;'>".$row->fechagestion->format('d/m/Y')."</td>
                        <td>".$row->Numerotelefonico."</td>
                        <td><input type='text' id='Dni' name='Dni' value='".$row->DNI."' disable/></td>
                        <td><select><option>".$row->Estado."</option></select></td>
                        <td>".$row->Observacion."</td>
                        <td>".$row->Agente."</td>
                        <td style='text-align:center;'><input type='checkbox' id='checked' name='checked' onchange='isCheck'/></td>
                        <td><input class='btn btn-success btn-lg pull-right' id='Guardar' value='Guardar' type='submit'/></td>
                    </tr>";
        $i++;

    }
    
}
?>
<script>
</script>


<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title> Agent web client </title>
        <link rel='shortcut icon' href='assets/images/favicon.ico' />
        <!-- Optional theme -->
        <link rel="stylesheet" href="http://192.168.1.112/gps-sql/assets/css/bootstrap/css/bootstrap-theme.css" />
        
        <link rel="stylesheet" href="http://192.168.1.112/gps-sql/assets/css/bootstrap/css/bootstrap.min.css"  />
        
        <link rel="stylesheet" href="http://192.168.1.112/gps-sql/assets/css/bootstrap/css/bootstrap.css.map"  />        
        <link rel="stylesheet" href="http://localhost/gps-sql/assets/datatables/dataTables.css"  />
        <link rel="stylesheet" href="http://localhost/gps-sql/assets/datatables/DataTables/css/dataTables.bootstrap.css"  />

        <!-- Latest compiled and minified JavaScript -->
         <script src="http://localhost/gps-sql/assets/js/jquery.2.2.4.js" crossorigin="anonymous"></script>
        <script src="http://localhost/gps-sql/assets/css/bootstrap/js/bootstrap.js" crossorigin="anonymous"></script>         
        <script src="http://localhost/gps-sql/assets/datatables/dataTables.js"></script>   
        <script src="http://localhost/gps-sql/assets/datatables/DataTables/js/dataTables.bootstrap.js"></script> 
        
    </head>
    <head>
        <style type="text/css">
            #tableTelefono{

            padding-top: 15px;
            padding-bottom: 25px;
            padding-right: 25px;
            padding-left: 25px;
            width: 100%;
            height: 80%;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="container-fluid">
                     <h1 class="text-center">Mantenimiento de Busqueda</h1>
                    <form class="form-inline" id="formatBusquedaTelefono" name="formBusquedaTelefono" method="POST" action="BusquedaTelefono.php">
                        <div class="row">
                            <br/>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="strInput">Busqueda de dni:</label>
                                    <input name="strInput" id="strInput" type="text" class="form-control" value="<?php echo !empty($_GET["strInput"])?$_GET["strInput"]:"";?>">
                                    <label><?php echo  !empty($DNI)?" DNI: " .  $DNI:"";?></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="slctAgency">AGENCIA:</label>
                                    <select name="slctAgency" id="slctAgency" class="form-control">
                                        <option value="">Seleccione agencia</option>
                                        <option value="DIRCON">DIRCON PERÚ</option>
                                        <!--option value="GPS">GPS PERÚ</option-->               
                                    </select>
                                </div>  
                            </div>
                        </div>                        
                        <input type="submit" id="btnBusquedaTelefono" name="btnBusquedaTelefono" value="Buscar" class="btn btn-default" />
                    </form>
                </div>
            </div>
            <br/>
            <?php if(!empty($_POST["btnBusquedaTelefono"]) OR !empty($_GET["strInput"])): ?>
            <div  class="container">
                <div class="container" id="tableTelefono">
                    <table id="tableTelefono" class="table table-striped table-responsive" border="1">
                        <thead>
                            <tr >
                                <td class="text-center" bgcolor="#F9E79F">Item</td> 
                                <td class="text-center" bgcolor="#F9E79F">FECHA. DE. GESTION</td> 
                                <td class="text-center" bgcolor="#F9E79F">TELEFONO</td>  
                                <td class="text-center" bgcolor="#F9E79F">DNI</td>
                                <td class="text-center" bgcolor="#F9E79F">ESTADO</td>  
                                <td class="text-center" bgcolor="#F9E79F">OBSERVACION</td>  
                                <td class="text-center" bgcolor="#F9E79F">AGENTE</td>
                                <td class="text-center" bgcolor="#F9E79F">SELECCIONAR</td>
                                <td class="text-center" bgcolor="#F9E79F">GUARDAR</td>
                        </thead>
                        <tbody>
                            <tr>
                                <?php echo !empty($strData)?$strData:"";?>
                            </tr>
                        </tbody>
                    </table>
                        <ul class = "pagination">
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">&laquo;</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">1</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">2</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">3</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">4</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">5</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">&raquo;</a></li>
                        </ul>
                </div>        
            </div>  
            <?php endif;?>
        </div> 

        <script type="text/JavaScript">
            $(document).ready(function() {
             $("#tableTelefono").DataTable();
            });
        </script>
    </body>
</html>