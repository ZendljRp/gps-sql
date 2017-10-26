<?php
date_default_timezone_set('America/Lima');
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include '../conecction/conecction.php';
$conn = conn();
$values = [];
$rows   = [];
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
    
    if($values['strDoc'] != 'telefono'){
        $search = "SELECT TOP 100 * FROM datafonos WHERE varDocumento LIKE '%".$values['intNumdoc']."%'";
    }else{
        $search = "SELECT TOP 100 * FROM datafonos WHERE varTelf LIKE '%".$values['strTelf']."%'";
    }
    
    
    $rssearch = sqlsrv_query($conn, $search);
    while( $row = sqlsrv_fetch_array($rssearch, SQLSRV_FETCH_ASSOC) ) {
        $rows[] = $row["varTelf"];
    }
    $numrows = sqlsrv_num_rows($rssearch);
    if(count($rows)>0){
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


