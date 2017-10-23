<?php
date_default_timezone_set('America/Lima');
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include '../conecction/conecction.php';
$conn = conn();
if(!empty($_POST["id"])){
    $id  = (int)$_POST["id"];
    $user = (string)$_POST["user"];
    $sql = "UPDATE datafonos SET cd=1, varUser='$user' WHERE idNumero=$id";
    $result = sqlsrv_query($conn, $sql);
    if(sqlsrv_errors()){
         echo "Conexión no se pudo establecer.<br />";
         die(print_r(sqlsrv_errors(), true));    
    }else{
        echo "1";
    }
    
}