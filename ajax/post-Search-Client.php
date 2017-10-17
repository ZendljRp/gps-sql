<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include '../conecction/conecction.php';
$conn = conn();
date_default_timezone_set('America/Lima');
if(!empty($_POST)){    
    
    $fechadatos     = $_POST["fchini"];
    $dni            = $_POST["strInput"];
    $Telefono       = $_POST["strtelf"];
    $Observaciones  = $_POST["Comentario"];
    $agente         = $_POST["hdagente"];
    $arrfecha       = explode('/', $fechadatos);
    $fecha          = implode('', array($arrfecha[2],$arrfecha[1],$arrfecha[0]));
    $hora           = (string)date("H:i:s");
    $fechaInsert    = $fecha.' '.$hora;

    //insert into gpsgestiones values ('20170907 00:00:00.000','$','$','$','','T','BUSQUEDA','$', 'DIRCON', '$','60','MANUAL', '', '', 'MASTER'); 
    $sqlInsert = "INSERT INTO gpsgestiones VALUES ('20170907 00:00:00.000','{$dni}','$fechaInsert','{$Telefono}', '','T','BUSQUEDA','{$Observaciones}','DIRCON','{$agente}','60','MANUAL','','','MASTER');";
    //echo var_dump($sqlInsert);
    $result = sqlsrv_query($conn, $sqlInsert);
    if(sqlsrv_errors()){
         echo "Conexión no se pudo establecer.<br />";
         die(print_r(sqlsrv_errors(), true));    
    }else{
        echo "1";
    }
}




