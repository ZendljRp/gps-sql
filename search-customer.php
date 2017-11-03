<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include 'conecction/conecction.php';

if(!empty($_POST["btnSearchCustomer"]) OR !empty($_GET["strInput"])){
    $conn = conn();
    $strData = "";
    $addSQL  = "";
    $strResp = '{"data":[';
    $predni  = $_REQUEST["strInput"];
    $dni = str_replace("'", "", $predni);
    $agency = $_POST["slctAgency"];
    if(!empty($agency)){
        $addSQL  = " AND varOperador = 'DIRCON'";
        $addSQL .= " AND idCliente = $agency";
    }else{
        $addSQL  = " AND varOperador = 'DIRCON'";
        $addSQL .= " AND idCliente = 1";
    }    
    $sql = "SELECT varCodprodinterno AS codproint, 
                    varCodprodcliente AS codproclie, 
                    varFechamora AS fechmora, 
                    varMonedasaldo AS moneda, 
                    decImportesaldo AS decimportsald, 
                    decImportesalfooperativo AS decimportsaldoper, 
                    decUltsaldoactinv AS decultsaldact
            FROM gpscartera
            WHERE varDocumento LIKE '%{$dni}%'"; 
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
                        <td>$row->fechmora</td>
                        <td>$row->moneda</td>
                        <td>$row->decultsaldact</td>
                    </tr>";
        $i++;
    }
    $sqlData = "SELECT per.varNombreors, (SELECT est.varDesgps FROM estadosgestion est WHERE est.varDesautodial = ges.varCodigorespuesta ) AS estadoDesc, ges.*
        FROM gpsgestiones ges
        INNER JOIN gpspersonas per
        ON per.varDocumento = ges.varRut
        WHERE varRut LIKE '%{$dni}%'$addSQL 
        ORDER BY ges.datFechagestion DESC;";
    
    $stmtdata = sqlsrv_query($conn, $sqlData);
    
    $j =1;
    //$result = sqlsrv_execute($stmt);
    while( $row = sqlsrv_fetch_object($stmtdata) ) {
        $strResp .= '{"item":"'.$j.'",'
                .'"nombre":"'.utf8_encode(utf8_decode($row->varNombreors)).'",'
                .'"fecha":"'.$row->datFechagestion->format('d/m/Y').'",'
                .'"telefono":"'.$row->varNumerotelefonico.'",'
                .'"estado":"'. strtoupper($row->estadoDesc).'",'
                .'"observacion":"'.utf8_encode(utf8_decode($row->varObservaciones)).'"},';
        $j++;
    }
    
    $strRespa = substr($strResp, 0, -1);
    $strRespa .= "]}"; 
    $fichero = 'search-customer.txt';
    $myfile = fopen("F:/xampp/htdocs/gps-sql/assets/file/report/$fichero", "wb") or die("Unable to open file!");
    $txt = $strRespa; 
    fwrite($myfile, $txt);    
    fclose($myfile);
    
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
        <link rel="stylesheet" href="http://192.168.1.112/gps-sql/assets/css/bootstrap/css/bootstrap-theme.css" />        
        <link rel="stylesheet" href="http://192.168.1.112/gps-sql/assets/css/bootstrap/css/bootstrap.min.css"  />        
        <link rel="stylesheet" href="http://192.168.1.112/gps-sql/assets/css/bootstrap/css/bootstrap.css.map"  />

        <link rel="stylesheet" type="text/css" href="http://192.168.1.112/gps-sql/assets/DataTables/media/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="http://192.168.1.112/gps-sql/assets/DataTables/media/css/dataTables.bootstrap.css">
        <style type="text/css">        
            table.fixedHeader-floating{position:fixed !important;background-color:white;top: -7px !important;}
            table.fixedHeader-floating.no-footer{border-bottom-width:0}
            table.fixedHeader-locked{position:absolute !important;background-color:white}
            @media print{table.fixedHeader-floating{display:none}}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="container-fluid">
                    <form class="form-inline" id="formSearchCustomer" name="formSearchCustomer" method="POST" action="search-customer.php">
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
                                        <option value="1">GPS PERÚ</option>
                                        <option value="2">CONECTA PERÚ</option>
                                        <option value="4">FRENO S.A. PERÚ</option> 
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
                                <th class="text-center">COD. PROD. INTERNO</th>
                                <th class="text-center">COD. PROD. CLIENTE</th>
                                <th class="text-center">FECH. MORA</th>
                                <th class="text-center">MONEDA</th>
                                <th class="text-center">IMP. ULT. SALDO ACTIVO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php echo !empty($strData)?$strData:"";?>
                            </tr>
                        </tbody>
                    </table>

                    <table id="searchCustomer" class="table table-responsive table-striped display" border="0">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">Item</th>                            
                                <th class="text-center" width="27%">NOMBRE</th>
                                <th class="text-center" width="10%">FECH. GESTION</th>
                                <th class="text-center" width="10%">TELÉFONO</th>
                                <th class="text-center" width="15%">ESTADO</th>
                                <th class="text-center" width="35%">OBSERVACIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>            
            </div>
            <?php endif;?>
        </div> 
        
        <!-- Latest compiled and minified JavaScript -->
        <script src="http://192.168.1.112/gps-sql/assets/js/jquery2.2.4.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
        <script src="http://192.168.1.112/gps-sql/assets/DataTables/media/js/jquery.dataTables.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/DataTables/media/js/dataTables.bootstrap.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#searchCustomer').DataTable( {                            
                    "ajax": "http://192.168.1.112/gps-sql/assets/file/report/search-customer.txt",
                    "columns": [
                        { "data": "item" },
                        { "data": "nombre" },
                        { "data": "fecha" },
                        { "data": "telefono" },
                        { "data": "estado" },
                        { "data": "observacion" }
                    ],
                    "language":{
                        "emptyTable":     "No hay datos disponibles en la tabla",
                        "info":           "Mostrando _START_ al _END_ de _TOTAL_ entradas",
                        "infoEmpty":      "Mostrando 0 a 0 de 0 entradas",
                        "infoFiltered":   "(filtrado de _MAX_ entradas totales)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "Mostrar _MENU_ entradas",
                        "loadingRecords": "Cargando...",
                        "processing":     "Procesando...",
                        "search":         "Buscar:",
                        "zeroRecords":    "No se encontraron coincidencia en los registros",
                        "paginate": {
                            "first":      "Primero",
                            "last":       "Último",
                            "next":       "Siguiente",
                            "previous":   "Anterior"
                        }
                    },
                    fixedHeader: {
                        header: true
                    },
                    "bDestroy": true
                });
            });
        </script>

        
    </body>
</html>


