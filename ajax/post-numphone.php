<?php
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
        $checked = ($row['cd'] == 1)?"btn-success":"btn-danger";
        $boton   = ($row['cd'] == 1)?"CONFIRMADO":"CONFIRMAR";
        $disable = ($row['cd'] == 1)?"disabled":"";
    ?>
    <tr>
        <td><?=$i?></td>
        <td><?=htmlentities($row['varTitular'], ENT_QUOTES | ENT_HTML401, 'UTF-8')?></td>
        <td><?=$row['varTelf']?></td>
        <td><?=$row['varOperador']?></td>
        <td class="btnValite"><button type="button" class="btn <?=$checked?> btnValite" name="btnValite" id="btnValite" data-userv="<?=$row['idNumero']?>" <?=$disable?>><?=$boton?></button></td>
        </tr>
    <?php
        $i++;
    }
}else{
?>
9
<?php
}
?>