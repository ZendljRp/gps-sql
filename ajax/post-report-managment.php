<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include '../conecction/conecction.php';
$conn = conn();
$strStatus = "";
if(!empty($_POST)){
    $fchIni = $_POST["fchini"];
    $fchFin = $_POST["fchfin"];
    $operad = $_POST["slctoperador"];
    $respon = $_POST["optionsRadios"];//slctstatus
    $status = $_POST["slctstatus"];
    $sqlStatus = "SELECT status, status_name FROM vicidial_statuses";
    $result = sqlsrv_query($conn, $sqlStatus);
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
        $strStatus .= "'{$row['status_name']}', ";
    }
    $strStatus = substr($strStatus, 0, -2);
    $sql = "SELECT per.varNombreors, gst.*
        FROM gpsgestiones gst
        INNER JOIN gpspersonas per
        ON gst.varRut = per.varDocumento
        WHERE gst.varCodigorespuesta IN ($strStatus)
        AND gst.datFechagestion BETWEEN '$fchIni' AND '$fchFin'
        AND gst.varAgente = '$operad'
        AND gst.varOperador = 'DIRCON'
        ORDER BY gst.datFechagestion DESC"; 
    
    $resultSearch = sqlsrv_query($conn, $sql);
    
    while($row = sqlsrv_fetch_array($resultSearch, SQLSRV_FETCH_ASSOC)){
        echo "'{$row['varNombreors']}' <br/>";
    }
    
    
}
