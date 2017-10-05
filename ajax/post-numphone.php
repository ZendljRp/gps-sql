<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include '../conecction/conecction.php';
$conn = conn();
$resultDNI = "";
$sql = "";
$stringTable = "";
if(!empty($_POST["doc"])){
    $dni = $_POST["doc"];
    $typ = $_POST["typ"];
    if($typ != 'telefono'){
        $sql = "SELECT * FROM datafonos WHERE varDocumento LIKE '%$dni%'";
    }else{
        $sql = "SELECT * FROM datafonos WHERE varTelf LIKE '%$dni%'";
    }
    $i=1;    
    $result = sqlsrv_query($conn, $sql);
    while( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ) {
        $stringTable .= "<tr>";
        $stringTable .= "<td>$i</td>";
        $stringTable .= "<td>".htmlentities($row['varTitular'], ENT_QUOTES | ENT_HTML401, 'UTF-8')."</td>";
        $stringTable .= "<td>".$row['varTelf']."</td>";
        $stringTable .= "<td>".$row['varOperador']."</td>";
        $stringTable .= "</tr>";
        $i++;
        //$resultDNI = htmlentities($row['varTitular'], ENT_QUOTES | ENT_HTML401, 'UTF-8');
    }
}
if(!empty($stringTable)){//$resultDNI
    echo $stringTable; //$resultDNI
}else{
    echo "0";
}
 