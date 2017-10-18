<?php
function connMySQL(){
    $serverName = "192.168.1.5";
    $username   = "pcluis";
    $password   = "";
    $database   = "asterisk";
       
    try {
        $connMySQL = new PDO("mysql:host=$serverName;dbname=$database", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));        
        // set the PDO error mode to exception
        $connMySQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connMySQL->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        return $connMySQL;
    }catch(PDOException $e){
        echo "Error en la conexión: " . $e->getMessage();
    }
}