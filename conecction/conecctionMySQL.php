<?php
function connMySQL(){
    $serverName = "192.168.1.5";
    $username   = "pcluis";
    $password   = "";
    $database   = "asterisk";
       
    try {
        $conn = new PDO("mysql:host=$serverName;dbname=$database", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));        
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        return $conn;
    }catch(PDOException $e){
        echo "Error en la conexión: " . $e->getMessage();
    }
}