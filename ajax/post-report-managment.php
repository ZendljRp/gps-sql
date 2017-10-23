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

    $sql = "SELECT per.varNombreors, gst.*
        FROM gpsgestiones gst
        INNER JOIN gpspersonas per
        ON gst.varRut = per.varDocumento
        WHERE gst.varCodigorespuesta IN ($strStatus)
        AND gst.datFechagestion BETWEEN '$fchIni' AND '$fchFin'
        AND gst.varAgente = '$operad'
        AND gst.varOperador = 'DIRCON'
        ORDER BY gst.datFechagestion DESC"; 

    //$result = sqlsrv_query($conn, $sqlStatus);
    /*while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
        $strStatus .= "'{$row['status_name']}', ";
    }*/
    
    $result = $connMySQL->query($sqlStatus);

    $result->execute();
    while($row = $result->setFetchMode(PDO::FETCH_OBJ)){
        $strStatus .= "$row->status_name <br/> ";
    }
    echo $strStatus;
    
    $strStatus = substr($strStatus, 0, -2);
    
    
//    $resultSearch = sqlsrv_query($conn, $sql);
//    
//    while($rowa = sqlsrv_fetch_object($resultSearch)){
//        echo "'{$rowa['varNombreors']}' <br/>";
//    }
    
    
//    $sqlMysql = "SELECT * FROM vicidial_list LIMIT 0, 15;";
//    
//    $mysql  = connMySQL();
//    $stmt   = $mysql->query($sqlMysql);
//    $stmt->execute();
//    
//    while($row = $stmt->setFetchMode(PDO::FETCH_OBJ)){
//        echo $row->phone_number . "<br/>";
//    }
    
    
    
    
}
