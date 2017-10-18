<?php
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
    
    $sqlInsert = "INSERT INTO datafonos(varDocumento,varTipodocumento,varTitular,varTelf,varOperador,datFechain,varOrigen,intEstado,varObs) VALUES ('{$values['intNumdoc']}','{$values['strDoc']}','{$name}','{$values['strTelf']}','{$values['slctOperator']}','".date('Ymd')."','DIRCON',1,'SIN CONFIRMAR');";
    
    $result = sqlsrv_query($conn, $sqlInsert);
    if(sqlsrv_errors()){
         echo "Conexión no se pudo establecer.<br />";
         die(print_r(sqlsrv_errors(), true));    
    }else{
        echo "1";
    }
}


