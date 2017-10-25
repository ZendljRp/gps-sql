<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include '../conecction/conecction.php';
$conn = conn();
$arrResult = array();
if(!empty($_POST)){
    $fchIni = !empty($_POST["fchini"])?$_POST["fchini"]:NULL;
    $fchFin = !empty($_POST["fchfin"])?$_POST["fchfin"]:NULL;
    $operad = !empty($_POST["slctoperador"])?$_POST["slctoperador"]:NULL;
    $respon = !empty($_POST["optionsRadios"])?$_POST["optionsRadios"]:NULL;
    $client = !empty($_POST["slctcliente"])?$_POST["slctcliente"]:NULL;

    $fchInit = changeDate($fchIni);
    $fchEnd  = changeDate($fchFin);

    $sql = "SELECT per.varNombreors AS nombre, gst.varRut AS documento, 
            gst.datFechagestion AS fecha, gst.varNumerotelefonico AS telefono, 
            gst.varCodigorespuesta AS codigogestion,  gst.varTipogestion AS tipogestion,             
            gst.varObservaciones AS observacion, gst.varAgente AS agente, 
            gst.intNumsec AS tiempollamada FROM gpsgestiones gst 
        INNER JOIN gpspersonas per ON gst.varRut = per.varDocumento WHERE gst.varAgente = '".$operad."'  "
        . "AND gst.idCliente = '$client'  AND gst.varOperador = 'DIRCON' AND gst.datFechagestion BETWEEN '".$fchInit."' AND '".$fchEnd."' ORDER BY gst.datFechagestion DESC "; 

    //$stmt = sqlsrv_query($conn, $sql);
    $stmt = $conn->query($sql);
    if ($stmt == FALSE){
        die(FormatErrors($conn->errorInfo()));
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == FALSE){
	FormatErrors ($stmt->errorInfo());
    }
    
    $i = 1;
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){        
        /*array_push($arrResult, array('item' => $i,
            'nombre' => $row["nombre"],
            'documento'    => $row["documento"],
            'fecha'        => $row["fecha"],
            'telefono'     => $row["telefono"],
            'tipogestion'  => $row["tipogestion"],
            'codigogestion' => $row["codigogestion"],       
            'observacion'  => $row["observacion"],
            'agente'       => $row["agente"],
            'tiempollamada' => $row["tiempollamada"]));
        $i++;*/
        /*$arrayContent = array(
            ""
        );*/
    } 
    echo var_dump($row);
    
    //$strStatus = substr($strStatus, 0, -2);
    
    
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

function changeDate($dateChange){
    $predate = explode('/', $dateChange);
    $newdate = implode('', array($predate[2],$predate[1],$predate[0]));
    return $newdate;
}
