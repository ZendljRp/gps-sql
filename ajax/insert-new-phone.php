<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include '../conecction/conecction.php';
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
    
    $insertListGo = "INSERT INTO vicidial_list(lead_id,"
            . "entry_date, status, list_id, gmt_offset_now, called_since_last_reset,phone_code,phone_number,"
            . "first_name, address2) VALUES('', NOW(), 'NEW', '', '-5.00', 'N', '51', '{$values['strTelf']}','$name', '{$values['strDoc']}');";
    
    $sqlInsert = "INSERT INTO datafonos VALUES ('{$values['intNumdoc']}','{$values['strDoc']}','{$name}','{$values['strTelf']}','{$values['slctOperator']}','".date('Ymd')."','DIRCON',1,'SIN CONFIRMAR');";
    //echo var_dump($sqlInsert);
    $result = sqlsrv_query($conn, $sqlInsert);
    if(sqlsrv_errors()){
         echo "Conexión no se pudo establecer.<br />";
         die(print_r(sqlsrv_errors(), true));    
    }else{
        echo "1";
    }
}

 /*
 * lead_id,
   entry_date-->now(), 
   status-->NEW, 
   list_id-->1018, 
   gmt_offset_now-->-5.00, 
   called_since_last_reset--> N, 
   phone_code-->51, 
   phone_number-->number, 
   first_name,
   address2-->dni,
 */

/*strDoc=dni
 * &intNumdoc=43287388
 * &strNombre=luis+junior+ruiz+peralta
 * &nameDNI=
 * &strTelf=987654321
 * &slctOperator=ENTEL
 */

