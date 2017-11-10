<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include("../conecction/conecction.php");

$conn = conn();
$arrResult["data"] = array();
$datas     = array();
$json      = "";
if(!empty($_POST)){
    $fchIni = !empty($_POST["fchini"])?$_POST["fchini"]:NULL;
    $fchFin = !empty($_POST["fchfin"])?$_POST["fchfin"]:NULL;
    $operad = !empty($_POST["slctoperador"])?$_POST["slctoperador"]:NULL;
    $respon = !empty($_POST["optionsRadios"])?$_POST["optionsRadios"]:NULL;
    $client = !empty($_POST["slctcliente"])?$_POST["slctcliente"]:NULL;
    
    echo var_dump(array($fchFin, $fchIni, $operad, $respon, $client));
    $jsonString = '{"data":[';
    $fchInit = changeDate($fchIni);
    $fchEnd  = changeDate($fchFin);
    
    $sql = "SELECT per.varNombreors AS nombre, 
        gst.varRut AS documento, 
        gst.datFechagestion AS fecha, 
        gst.varNumerotelefonico AS telefono, 
        gst.varCodigorespuesta AS codigogestion,  
        gst.varTipogestion AS tipogestion,             
        gst.varObservaciones AS observacion, 
        gst.varAgente AS agente, 
        gst.intNumsec AS tiempollamada 
        FROM gpsgestiones gst 
        INNER JOIN gpspersonas per ON gst.varRut = per.varDocumento 
        WHERE gst.varAgente = '$operad' 
        AND gst.idCliente = $client  
        AND gst.varOperador = 'DIRCON' 
        AND gst.datFechagestion BETWEEN '$fchInit' AND '$fchEnd' 
        ORDER BY gst.datFechagestion DESC";
    
    $stmt = sqlsrv_query($conn, $sql);
    
    $i = 1;
    while($row  =  sqlsrv_fetch_object($stmt)){  
        $jsonString .= '{"item":"'.$i.'",'
            . '"nombre":"'.utf8_encode($row->nombre).'",'
            . '"documento":"'.$row->documento.'",'
            . '"fecha":"'.$row->fecha->format("d/m/Y H:i:s").'",'
            . '"telefono":"'.$row->telefono.'",'
            . '"tipogestion":"'.utf8_encode($row->tipogestion).'",'
            . '"codigogestion":"'.utf8_encode($row->codigogestion).'",'
            . '"observacion":"'.utf8_encode($row->observacion).'",'
            . '"agente":"'.utf8_encode($row->agente).'",'
            . '"tiempollamada":"'.$row->tiempollamada.'"},';
        $i++;
    }
    $jsonStringBest = substr($jsonString, 0, -1);
    $jsonStringBest .= "]}";   
    echo $jsonStringBest;
    $fichero = 'report-managment.txt';
    $myfile = fopen("F:/xampp/htdocs/gps-sql/assets/file/report/$fichero", "wb") or die("Unable to open file!");
    $txt = $jsonStringBest; 
    fwrite($myfile, $txt);    
    fclose($myfile);
    
}

function changeDate($dateChange){
    $predate = explode('/', $dateChange);
    $newdate = implode('', array($predate[2],$predate[1],$predate[0]));
    return $newdate;
}