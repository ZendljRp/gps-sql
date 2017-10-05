<?php
include "conecction/conecction.php";
//cadena de conexion.
$conn = conn();
if(!empty($_POST["btnEnvio"])){
    
}


?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>.:: Reporte GPS - DIRCON ::.</title>
    </head>
    <body>
        <div id="searchData">
            <form id="formSearchData" action="" method="POST" class="">
                <label>Fecha inicial: </label>
                <input name="fechInicial" type="date" /><br/>
                <br/>
                <label>Documento RUT: </label>
                <input name="documento" type="text" /><br/>
                <br/><br/>
                
                <input type="submit" name="btnEnvio" value="Buscar"/>
            </form>            
        </div>
        <div>
            <table>
                <thead>
                    <th>COD. SERIE</th>
                    <th>RUT CLIENTE</th>
                    <th>FECHA GESTION</th>
                    <th>HORA GESTION</th>
                    <th>NUMERO TELEFONICO</th>
                    <th>DIRECCION</th>
                    <th>TIPO GESTION</th>
                    <th>CODIGO RESPUESTA</th>
                    <th>OBSERVACION</th>
                </thead>
                <tbody>
                    
                </tbody>
                
            </table>
        </div>
        <?php
        $query = "SELECT * FROM clientes";
        $result = sqlsrv_query($conn, $query);
        while( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ) {
            echo $row['varNombre'].", ".$row['varRuc']."<br />";
        }      
        ?>
    </body>
</html>
