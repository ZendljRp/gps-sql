<?php
date_default_timezone_set('America/Lima');
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include '../conecction/conecction.php';
include '../conecction/conecctionMySQL.php';
$conn = conn();
$values = [];
if(!empty($_POST)){    
    $prevalues = explode("&",$_POST["fulldata"]);
    $countArray = count($prevalues);
    for($i=0;$i<$countArray;$i++){
        $prevals = explode("=",$prevalues[$i]);
        $values[$prevals[0]] = $prevals[1];
    }
    if(!empty($values['strNombre'])){
        $name = strtoupper(str_replace('+', ' ', $values['strNombre']));
    }else{
        $name = strtoupper(str_replace('+', ' ', $values['nameDNI']));
    }    
    //$sqlMysql = "SELECT * FROM vicidial_list LIMIT 0, 15;";    
    /*$insertListGo = "INSERT INTO vicidial_list(lead_id,"
        . "entry_date, status, list_id, gmt_offset_now, called_since_last_reset,phone_code,phone_number,"
        . "first_name, address2) VALUES('', NOW(), 'NEW', '', '-5.00', 'N', '51', '{$values['strTelf']}','$name', '{$values['strDoc']}');";*/
    
    //$values['strTelf'];
    $search = "SELECT TOP 5 * FROM datafonos WHERE varTelf LIKE '%".$values['strTelf']."%'";
    
    $rssearch = sqlsrv_query($conn, $search);
    $rows = count($rssearch);
    if($rows > 0){
        echo "701";        
    }else{
        $sqlInsert = "INSERT INTO datafonos(varDocumento,varTipodocumento,varTitular,varTelf,varOperador,datFechain,varOrigen,intEstado,varObs,cd, varUser) VALUES ('{$values['intNumdoc']}','{$values['strDoc']}','{$name}','{$values['strTelf']}','{$values['slctOperator']}','".date('Ymd H:i:s')."','DIRCON',1,'CONFIRMADO', 1, '{$values['agente']}');";
        $result = sqlsrv_query($conn, $sqlInsert);
        if(sqlsrv_errors()){
            echo "Conexión no se pudo establecer.<br />";
            die(print_r(sqlsrv_errors(), true));    
        }else{
            echo "ok";
        }
    }    
}


