<?php
function conn(){
    $serverName = "APOLO"; //serverName\instanceName "LAPTOP-BD2FRCGI\SERVER"
    // Puesto que no se han especificado UID ni PWD en el array  $connectionInfo,
    // La conexión se intentará utilizando la autenticación Windows.
    $connectionInfo = array( "Database"=>"Dircrm", "UID"=>"sa", "PWD"=>"d1rc0n$$"); //"Database"=>"Dircrm", "UID"=>"sa", "PWD"=>"."
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    if( $conn ) {
        $connect = $conn;
    }else{
         echo "Conexión no se pudo establecer.<br />";
         die(print_r(sqlsrv_errors(), true));
    }
    return $connect;
    
 
//    $serverName = "tcp:LAPTOP-BD2FRCGI\SERVER";
//    $database   = "Dircrm";
//    $uid        = "sa";
//    $pwd        = ".";
//    $conn       = new PDO("sqlsrv:server=$serverName ; Database = $database", $uid, $pwd);
//    return $conn;    
}


function FormatErrors( $error ) {
    /* Display error. */
    echo "Error information: <br/>";
    echo "SQLSTATE: ".$error[0]."<br/>";
    echo "Code: ".$error[1]."<br/>";
    echo "Message: ".$error[2]."<br/>";
}


