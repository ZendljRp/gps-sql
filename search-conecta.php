<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include 'conecction/conecction.php';

if(!empty($_POST["btnSearchCustomer"]) OR !empty($_GET["strInput"])){
    $conn = conn();
    $strData = "";
    $addSQL  = "";
    $strResp = "";
    $predni  = $_REQUEST["strInput"];
    $dni = str_replace("'", "", $predni);
    $agency = $_POST["slctAgency"];
    if(!empty($agency)){
        $addSQL = " AND varOperador = 'DIRCON'";
        //$addSQL = " AND varOperador = '" . $agency . "'";
    }else{
        $addSQL = " AND varOperador = 'DIRCON'";
        //$addSQL = "";
    }    
    $sql = "SELECT varCuenta AS codproint,
        varOperacion AS codproclie,
        varNombres AS nombre,
        varDeuda_Actual AS deuda,
        varEnCampana AS campania,
        varFec_Vta AS fecha,
        varEntidad AS entidad,
        varUltFecPago AS ultimafechpago
        FROM conectacartera
        WHERE varDNI LIKE '%{$dni}%';"; 
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
    $i =1;
    //$result = sqlsrv_execute($stmt);
    while( $row = sqlsrv_fetch_object($stmt) ) {
        $strData .= "<tr>
                        <td>$i</td>
                        <td>$row->codproint</td>
                        <td>$row->codproclie</td>
                        <td>$row->nombre</td>
                        <td>$row->deuda</td>
                        <td>$row->campania</td>
                        <td>$row->fecha</td>
                        <td>$row->entidad</td>
                        <td>$row->ultimafechpago</td>
                    </tr>";
        $i++;
    }
    $sqlData = "SELECT UPPER((SELECT est.varDesgps FROM estadosgestion est WHERE est.varDesautodial = gest.varCodigorespuesta )) AS estadoDesc, 
        gest.* 
        FROM ga_gestiones AS gest
        WHERE gest.varrut NOT IN (SELECT varDocumento FROM gpspersonas)
        AND gest.varrut LIKE '%{$dni}%' $addSQL
        ORDER BY gest.datFechagestion DESC;";
    
    $stmtdata = sqlsrv_query($conn, $sqlData);
    
    $j =1;
    //$result = sqlsrv_execute($stmt);
    while( $row = sqlsrv_fetch_object($stmtdata) ) {
        $strResp .= "<tr>
                        <td style='text-align:center;'>$j</td>
                        <td style='text-align:center;'>".$row->datfechagestion->format('d/m/Y')."</td>
                        <td style='text-align:center;'>".$row->varNumerotelefonico."</td>
                        <td>". strtoupper($row->estadoDesc)."</td>
                        <td>".utf8_encode(utf8_decode($row->varobservaciones))."</td>
                    </tr>";
        $j++;
    }
    
}
?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title> Agent web client </title>
        <link rel='shortcut icon' href='assets/images/favicon.ico' />
        <!-- Optional theme -->
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/css/bootstrap-theme.css" />
        
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/css/bootstrap.min.css"  />
        
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/css/bootstrap.css.map"  />

        <!-- Latest compiled and minified JavaScript -->
        <script src="http://192.168.1.7:8080/gps-sql/assets/js/jquery2.2.4.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="container-fluid">
                    <form class="form-inline" id="formSearchCustomer" name="formSearchCustomer" method="POST" action="search-conecta.php">
                        <div class="row">
                            <br/>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="strInput">Ingrese DNI cliente:</label>
                                    <input name="strInput" id="strInput" type="text" class="form-control" value="<?php echo !empty($_GET["strInput"])?$_GET["strInput"]:"";?>">
                                    <label><?php echo  !empty($dni)?" DNI: " .  $dni:"";?></label>
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
                        <input type="submit" id="btnSearcCustomer" name="btnSearchCustomer" value="Buscar" class="btn btn-default" />
                    </form>
                </div>
            </div>
            <br/>
            <?php if(!empty($_POST["btnSearchCustomer"]) OR !empty($_GET["strInput"])): ?>
            <div class="container">
                <div class="content">
                    <table class="table table-striped table-responsive" border="0">
                        <thead>
                            <tr>
                                <th class="text-center">Item</th>                            
                                <th class="text-center">CUENTA</th>
                                <th class="text-center">OPERACION</th>
                                <th class="text-center">NOMBRES</th>
                                <th class="text-center">DEUDA</th>
                                <th class="text-center">CAMPAÑA</th>
                                <th class="text-center">FECH. MORA</th>
                                <th class="text-center">ENTIDAD</th>
                                <th class="text-center">ULT. PAGO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php echo !empty($strData)?$strData:"";?>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-responsive table-striped" border="0">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">Item</th>
                                <th class="text-center" width="10%">FECH. GESTION</th>
                                <th class="text-center" width="10%">TELÉFONO</th>
                                <th class="text-center" width="15%">ESTADO</th>
                                <th class="text-center" width="62%">OBSERVACIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>                            
                                <?php echo !empty($strResp)?$strResp:"";?>
                            </tr>
                        </tbody>
                    </table>
                </div>            
            </div>
            <?php endif;?>
        </div> 
        
    </body>
</html>