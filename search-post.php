<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include "conecction/conecction.php";
if(!empty($_REQUEST["idDNI"]) && isset($_REQUEST["slctAgency"])){
    $conn = conn();
    $strData = "";
    $addSQL  = "";
    $strResp = "";
    $predni  = $_REQUEST["idDNI"];
    $dni = str_replace("'", "", $predni);    
    $agency = !empty($_REQUEST["slctAgency"])?$_REQUEST["slctAgency"]:"";
    if(!empty($agency)){
        $addSQL = " AND varOperador = 'DIRCON'";
        //$addSQL = " AND varOperador = '" . $agency . "'";
    }else{
        $addSQL = " AND varOperador = 'DIRCON'";
        //$addSQL = "";
    }
    $sql = "SELECT varCodprodinterno AS codproint, 
                    varCodprodcliente AS codproclie, 
                    varFechamora AS fechmora, 
                    varMonedasaldo AS moneda, 
                    decImportesaldo AS decimportsald, 
                    decImportesalfooperativo AS decimportsaldoper, 
                    decUltsaldoactinv AS decultsaldact
            FROM gpscartera
            WHERE varDocumento LIKE '%{$dni}%';"; 
    $stmt = sqlsrv_query($conn, $sql);
    if( $stmt === false ) {
        $hay= 0;
        if( ($errors = sqlsrv_errors() ) != null) {
            foreach( $errors as $error ) {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
            }
        }
    }
    $i =1;
    $strTableDescriptionCustomer = '<table class="table table-striped table-responsive" border="0">
        <thead>
            <tr>
                <th class="text-center">Item</th>                            
                <th class="text-center">COD. PROD. INTERNO</th>
                <th class="text-center">COD. PROD. CLIENTE</th>
                <th class="text-center">FECH. MORA</th>
                <th class="text-center">MONEDA</th>
                <th class="text-center">IMP. SALDO</th>
                <th class="text-center">IMP. SALDO OPERATIVO</th>
                <th class="text-center">IMP. ULT. SALDO ACTIVO</th>
            </tr>
        </thead>
        <tbody>';
    //$result = sqlsrv_execute($stmt);
    while( $row = sqlsrv_fetch_object($stmt) ) {
        $strData .= "<tr>
                        <td>$i</td>
                        <td>$row->codproint</td>
                        <td>$row->codproclie</td>
                        <td>$row->fechmora</td>
                        <td>$row->moneda</td>
                        <td>$row->decimportsald</td>
                        <td>$row->decimportsaldoper</td>
                        <td>$row->decultsaldact</td>
                    </tr>";
        $i++;
    }
    $strTableDescriptionCustomer .= $strData . "</tbody></table>";
    /////////// fin de la tabla de descripcion de cliente
    
    /////////// inicio de la tabla de gestiones    
    $sqlData = "SELECT per.varNombreors, (SELECT est.varDesgps FROM estadosgestion est WHERE est.varDesautodial = ges.varCodigorespuesta ) AS estadoDesc, ges.*
        FROM gpsgestiones ges
        INNER JOIN gpspersonas per
        ON per.varDocumento = ges.varRut
        WHERE varRut LIKE '%{$dni}%'$addSQL 
        ORDER BY ges.datFechagestion DESC;";
    
    $stmtdata = sqlsrv_query($conn, $sqlData);
    $strTableGestion = '<table id="agentable" border="0">
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
        <tbody>';
    $j =1; // 
    //$result = sqlsrv_execute($stmt);
    while( $row = sqlsrv_fetch_object($stmtdata) ) {
        $numID = ($j%2==0)?'2':'1';
        $strResp .= "<tr id='q".$numID."'>
                        <td style='text-align:center;'>$j</td>
                        <td>".utf8_encode($row->varNombreors)."</td>
                        <td style='text-align:center;'>".$row->datFechagestion->format('d/m/Y')."</td>
                        <td style='text-align:center;'>".$row->varNumerotelefonico."</td>
                        <td>". utf8_encode(strtoupper($row->estadoDesc))."</td>
                        <td>".utf8_encode(strtoupper($row->varObservaciones))."</td>
                    </tr>";
        $j++;
    }
    $strTableGestion .= $strResp . "</tbody></table>";
    
    echo $strTableDescriptionCustomer . "<br/>" . $strTableGestion;
    
}
else{
    echo "<span> No se ha encontrado gestiones para el DNI o Telef. o Persona: " . $_REQUEST["idDNI"] . ".";
}