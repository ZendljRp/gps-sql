<?php
date_default_timezone_set('America/Lima');
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
        $checked = ($row['cd'] == 1)?"btn-success":"btn-danger";
        $boton   = ($row['cd'] == 1)?"CONFIRMADO":"CONFIRMAR";
        $disable = ($row['cd'] == 1)?"disabled":"";
        $stringTable .= "<tr>";
        $stringTable .= "<td>$i</td>";
        $stringTable .= "<td>".htmlentities($row['varTitular'], ENT_QUOTES | ENT_HTML401, 'UTF-8')."</td>";
        $stringTable .= "<td>".$row['varTelf']."</td>";
        $stringTable .= "<td>".$row['varOperador']."</td>";
        $stringTable .= "<td><button type=\"button\" class=\"btn ".$checked." btnValite\" name=\"btnValite\" data-userv=\"".$row['idNumero']."\" $disable>$boton</button></td>";
        $stringTable .= "</tr>";
        $i++;
    }
}

if(!empty($stringTable)){//$resultDNI
    echo ($stringTable); //$resultDNI
?>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btnValite").click(function(){                
                bootbox.confirm({
                    title:'Confirmar',
                    message: "¿Esta seguro de actualizar los dato?",
                    buttons: {
                        confirm: {
                            label: 'Si',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        console.log('This was logged in the callback: ' + result);
                    }
                });
                
                /*var id = $(this).data("userv");
                var user = $("#agente").val();
                $.post('http://192.168.1.112/gps-sql/ajax/post-update-status.php', {id:id,user:user}, function(data){
                    if(data == '1'){
                        alert("Confirmacion con exito.");
                        location.reload();
                    }else{
                        alert("No se ha confirmado la peticion.");
                    }
                });*/
        
            });
        });
    </script>
<?php
}else{
    echo "0";
}
 
