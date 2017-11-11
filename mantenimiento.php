<?php
include 'conecction/conecction.php';
set_time_limit (180);
$conn           = conn();
$strData        = "";


if(!empty($_POST["btnSearch"])){
    $sql  = "SELECT top 20 idGagestion As id, idCliente AS Cliente,datfechagestion AS fechagestion,
    varNumerotelefonico  AS Numerotelefonico,
    varrut AS DNI, varcodigorespuesta AS Estado, varobservaciones AS Observacion,
    varagente AS Agente 
    FROM ga_gestiones 
    where varrut is null
    or varcodigorespuesta is null
    or idCliente is null
    order by datfechagestion desc "; 

    $stmt = sqlsrv_query($conn, $sql);
    if( $stmt === false ) {
        $hay= 0;
        if( ($errors = sqlsrv_errors() ) != null) {
            foreach( $errors as $error ) {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
            }
        //Codigo necesario para terminar la ejecución con gracia
        }
    }
    
    function getEstado($descripcion, $idrow) {
        $conn = conn();
        $sqlestado =" SELECT varDesautodial As descripcion, varCodautodial As Codigo FROM estadosgestion"; 
        $stmtEstado = sqlsrv_query($conn, $sqlestado);
        if( $stmtEstado === false ) {
            $hay= 0;
            if( ($errors = sqlsrv_errors() ) != null) {
                foreach( $errors as $error ) {
                    echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                    echo "code: ".$error[ 'code']."<br />";
                    echo "message: ".$error[ 'message']."<br />";
                }
            //Codigo necesario para terminar la ejecución con gracia
            }
        }
        $strestado = "<select name='descripcion".$idrow."' class='Estado'><option value=''>seleccione Estado</option>";
        while( $rowEstado = sqlsrv_fetch_object($stmtEstado) ) {
            $selectestado = ($rowEstado->descripcion == $descripcion)?"selected":"";      
            $strestado .="<option value='".$rowEstado->descripcion."' $selectestado>".$rowEstado->descripcion."</option>";
        }
        $strestado .=  "</select>";
        return $strestado;
    }

    function getcliente($idcliente, $idrow)  {
        $conn = conn();
        $sqlcliente ="SELECT idCliente As idcliente,varNombre AS nombre from clientes where idCliente<>3";
        $stmtcliente = sqlsrv_query($conn, $sqlcliente);
        if( $stmtcliente === false ) {
            $hay= 0;
            if( ($errors = sqlsrv_errors() ) != null) {
                foreach( $errors as $error ) {
                    echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                    echo "code: ".$error[ 'code']."<br />";
                    echo "message: ".$error[ 'message']."<br />";
                }
            //Codigo necesario para terminar la ejecución con gracia
            }
        }
        $strcliente = "<select name='cliente".$idrow."' class='Cliente'><option value=''>seleccione cliente</option>";
        while( $rowCliente = sqlsrv_fetch_object($stmtcliente) ) {
            $selectcliente = ($rowCliente->idcliente == $idcliente)?"selected":"";      
            $strcliente .="<option value='".$rowCliente->idcliente."' $selectcliente>".$rowCliente->nombre."</option>";
        }
        $strcliente .=  "</select>";
        return $strcliente;
    }
    
    $i =1;
    //$result = sqlsrv_execute($stmt);
    while( $row = sqlsrv_fetch_object($stmt) ) { 
        /*array_push($result["data"], array(
            "item"  => $i,
            "idcliente" => getcliente($row->Cliente, $row->id),
            "fchgestio" => "<input type='text' name='Fechagestion".$row->id."' class='form-control date' placeholder='fechagestion' value=".$row->fechagestion->format('d/m/Y')."></input>",
            "telefono"  => $row->Numerotelefonico,
            "documento" => "<input type='text' class='text Dni' name='Dni".$row->id."' value='".$row->DNI."'/>",
            "estado"    => getEstado(utf8_encode($row->Estado), $row->id),
            "observat"  => "<textarea type='text' name='Observacion".$row->id."' class='Observacion' >".utf8_encode($row->Observacion)."</textarea>", 
            "agente"    => "<input type='text' name='Agente".$row->id."' class='Agente' value='".$row->Agente."'/>",
            "checkbox"  => "<input class='chckSelec checkedsi' type='checkbox' name='chckSelec' data-idgagestion='".$row->id."'/>"
        ));*/
        $strData .= "<tr>
                <td>".$i."</td>
                <td>".getcliente($row->Cliente, $row->id)."</td>
                <td style='text-align:center;'><input type='text' name='Fechagestion".$row->id."' class='form-control date' placeholder='fechagestion' value=".$row->fechagestion->format('d/m/Y')."></input></td>
                <td>".$row->Numerotelefonico."</td>
                <td><input type='text' class='text Dni' name='Dni".$row->id."' value='".$row->DNI."'/></td>
                <td>".getEstado($row->Estado, $row->id)."</td>
                <td><textarea type='text' name='Observacion".$row->id."' class='Observacion' >".$row->Observacion."</textarea></td>
                <td><input type='text' name='Agente".$row->id."' class='Agente' value='".$row->Agente."'/></td>
                <td style='text-align:center;'><input class='chckSelec checkedsi' type='checkbox' name='chckSelec' data-idgagestion='".$row->id."'/></td>
            </tr>";
        $i++;
    }
    
    $fichero = 'managment-report.txt';
    $myfile = fopen("F:/xampp/htdocs/gps-sql/assets/file/report/$fichero", "wb") or die("Unable to open file!");
    $newtext = json_encode($result);
    fwrite($myfile, $newtext);    
    fclose($myfile);
    
}
    

if (!empty($_POST["btnAgregar"])) {
    $conn = conn();
    $strData = "";
    $addSQL  = "";
    $strResp = "";
    $seleccione="";
    $predni         = $_REQUEST["strInput"];
    $pretelf        = $_REQUEST["strInput"];
    $DNI            = str_replace("'", "", $predni);
    $agency         = $_POST["slctAgency"];
    $arrfecha       = explode('/', $fechadatos);
    $fecha          = implode('', array($arrfecha[2],$arrfecha[1],$arrfecha[0]));
    $hora           = (string)date("H:i:s");
    $fechaInsert    = $fecha.' '.$hora;
       
    }

?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title> Agent web client </title>
        <link rel='shortcut icon' href='assets/images/favicon.ico' />
        <!-- Optional theme -->
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/css/bootstrap-theme.css" />
        
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/css/bootstrap.min.css"  />
        
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/css/bootstrap.css.map"  />
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/DataTables/media/css/dataTables.bootstrap.css"  />
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/alertafy/css/alertify.min.css" />
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/alertafy/css/themes/default.min.css" />
        <link href="http://192.168.1.7:8080/gps-sql/assets/datepicker/css/bootstrap-datepicker.css" rel="stylesheet" media="screen" />

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">

    </head>
    <head>
        <style type="text/css">
            #tableTelefono{

            padding-top: 15px;
            padding-bottom: 25px;
            padding-right: 25px;
            padding-left: 25px;
            width: 100%;
            height: 80%;
            }
            #Estado{
                height: 29px;
                /*width: 192px;*/
            }
            #Guardar{
                height: 43px;
            }
            .select
            {
                height: 32px;
            }
/*Colorear caja de texto*/
            .box-blue,
            .box-gray,
            .box-green,
            .box-grey,
            .box-red,
            .box-yellow {
                margin:0 0 25px;
                overflow:hidden;
                padding:10px;
                -webkit-border-radius: 10px;
                border-radius: 10px;
            }
             
            .box-blue {
                background-color:#d8ecf7;
                border:1px solid #afcde3;
            }
             
            .box-gray {
                background-color:#e2e2e2;
                border:1px solid #bdbdbd;
            }
             
            .box-green {
                background-color:#d9edc2;
                border:1px solid #b2ce96;
            }
             
            .box-grey {
                background-color:#F5F5F5;
                border:1px solid #DDDDDD;
            }
             
            .box-red {
                background-color:#f9dbdb;
                border:1px solid #e9b3b3;
            }
             
            .box-yellow {
                background-color:#fef5c4;
                border:1px solid #fadf98;
            }
/* END */
            .btn-default {
                background-color:  #FFFFFF;
                border-radius: 5px;
                color: lightblue;
                padding: 2px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 5px 2px;
                height: 33px;
            }
/* icons Search*/
            .input-group-unstyled input.form-control {
            -webkit-border-radius: 4px !important;
            -moz-border-radius: 4px !important;
             border-radius: 4px !important;
             background-color: #87CEFA;

            }
            .input-group-unstyled .input-group-addon {
              border-radius: 4px;
              border: 0px;
              background-color: white;
            }
            .input-group-addon {
                padding: 9px 12px;
            }
            .form-control{
                background-color: #F5FFFA;
                width: 100px;
            }
            table.fixedHeader-floating{position:fixed !important;background-color:white;top: -7px !important;}
            table.fixedHeader-floating.no-footer{border-bottom-width:0}
            table.fixedHeader-locked{position:absolute !important;background-color:white}
            @media print{table.fixedHeader-floating{display:none}}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="container-fluid">
                    <h1 class="text-center">Mantenimiento total de Gestiones</h1>
                    <form class="form-inline" onsubmit="return searchGestions();" id="formatmantenimiento" name="searchGestions" method="POST" action="mantenimiento.php" >                         
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Fecha de inicio: </label>
                                <input type="text" name="bgndate" class="form-control date" />
                            </div>
                            <div class="col-sm-4">
                                <label>Fecha de fin: </label>
                                <input type="text" name="enddate" class="form-control date" />
                            </div>
                            <div class="col-sm-4">
                                <input type="submit" name="btnSearch" class="btn btn-default" value="Buscar"/>
                            </div>
                        </div>
                        <div style="float: right;">
                            <input type="button" id="btnAgregar" name="btnAgregar" value="ACTUALIZAR" class="btn btn-success"/>
                        </div>                        
                    </form>
                </div>
            </div>
            <br/>
        </div>
        <div class="content" style="padding-left: 17px; padding-right: 17px;"> 
            <table id="tblmantenimiento" class="table table-striped table-responsive display" border="1" width="100%" cellspacing="0">
                <thead style="font-size: 12px; background-color: #F9E79F; font-weight: bold;">
                    <th class="text-center">Item</th>
                    <th class="text-center">CLIENTE</th> 
                    <th class="text-center">FECHA. DE. GESTION</th> 
                    <th class="text-center">TELEFONO</th>  
                    <th class="text-center">DNI</th>
                    <th class="text-center">ESTADO</th>  
                    <th class="text-center">OBSERVACION</th>  
                    <th class="text-center">AGENTE</th>
                    <th class="text-center">SELECCIONAR <input id="checkedsi" class='chckSelec' type='checkbox' name='chckSelec' value="1" /></th>
                </thead>
                <tbody>                
                    <?php echo !empty($strData)?$strData:"";?>
                </tbody>
            </table>             
        </div>
        
        
        
        <!-- Modal -->
        <div id="valiteChecked" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header alert alert-danger">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Advertencia</h4>
                    </div>
                    <div class="modal-body">
                        <p>Seleccione la casilla para poder actualizar.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
        <script src="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/js/bootstrap.js"></script>         
        <script src="http://192.168.1.7:8080/gps-sql/assets/DataTables/media/js/jquery.dataTables.js"></script>
        <script src="http://192.168.1.7:8080/gps-sql/assets/DataTables/media/js/dataTables.bootstrap.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>
        <script src="http://192.168.1.7:8080/gps-sql/assets/alertafy/alertify.min.js"></script>
        <script src="http://192.168.1.7:8080/gps-sql/assets/datepicker/js/bootstrap-datepicker.js"></script>
        <script src="http://192.168.1.7:8080/gps-sql/assets/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
        <script type="text/javascript">
            function searchGestions(){
                var micampo = document.getElementsByTagName("bgndate").value;
                if (micampo.length== ""|| /^\s+$/.test(micampo)) {
                    alert('Ingrese fecha de inicio.');
                    return false;
                }
                var miCombo = document.getElementsByTagName("enddate").value;
                    if(miCombo == ""){     
                    alert('Ingrese fecha fin.');
                    return false;
                }
                return true;
            }

            function changeCheckedOn(){
                $('.checkedsi').prop('checked',true);
            }

            function changeCheckedOff(){
                $('.checkedsi').prop('checked',false);
            }
            /*var comprobar = function (){
                $('#chckSelec').change(function() {
                    $('#Guardar').attr('disabled',this.checked);
                    //$('#Guardar').attr('disabled', this.checked= false);
                });
             };*/
             // Habilitar y Desabilitar Checkbox
            /// End de chckselec
            ///datapicker
            $(document).ready(function(){
                $(".checkseleccione").bind("click",function(){
                    if ($(this).attr("checked")==true){
                        $("input.chckSelec").each(function(){ $(this).attr("checked",true); });
                    }else{
                        $("input.chckSelec").each(function(){ if ($(this).data("checked")==0) $(this).removeAttr("checked"); });
                    }
                });
                $('.date').datepicker({
                        language: "es",
                        autoclose: true,
                        todayHighlight: true
                    });
                    ////
                $('.Guardar').click(function(){
                        var alldata = $("#tableTelefono").serialize();
                        alert(alldata);
                        $.post('http://192.168.1.7:8080/gps-sql/ajax/mantenimiento.php', alldata, function(response){
                            console.log(response); 
                            $("#resultData").attr(response);
                        });
                    });
                    ///
                $(document).on('submit', '#tableTelefono', function() { 
                    //obtenemos datos.
                        var data = $(this).serialize();  
                        $.ajax({  
                            type : 'GET',
                            url  : 'mantenimiento.php',
                            data:  new FormData(this),
                            contentType: false,
                            cache: false,
                            processData:false,

                        success :  function(data) {  
                            $('#tableTelefono')[0].reset();
                            $("#cargando").html(data);                  
                            }
                        });
                    return false;
                    });
           
                $('.Agregar').on('click',function(){
                        var confirm= alertify.confirm('Desea Agregar','Agregar solicitud?',null,null).set('labels', {ok:'Agregar', cancel:'Cancelar'});  
                                confirm.set({transition:'slide'});      
                                    confirm.set('onok', function(){ //callbak al pulsar botón positivo
                                    alertify.success('El registro se ha Agregado ala base de datos');
                                    });
                                confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
                                alertify.error('has cancelado el registro');
                        });
                    });

                $('#checkedsi').click(function(){
                    if ($(this).is(":checked")==true) {
                        changeCheckedOn();                            
                    }else{
                        changeCheckedOff();                            
                    }
                });

                $('#btnAgregar').click(function(){
                    var countcheck = $('.checkedsi:checkbox:checked').size();
                    if(countcheck > 0){
                        $('.checkedsi:checkbox:checked').map(function(){
                            var dataid = $(this).data('idgagestion');                            
                            var Cliente=$('select[name=cliente'+dataid+']').val();
                            var date=$('input[name=Fechagestion'+dataid+']').val();
                            //var Numerotelefonico=$('.Numerotelefonico').val();
                            var Dni=$('input[name=Dni'+dataid+']').val(); 
                            var Estado=$('select[name=descripcion'+dataid+']').val();
                            var Observacion=$('textarea[name=Observacion'+dataid+']').val();
                            var Agente=$('input[name=Agente'+dataid+']').val();
                            console.log(dataid + " " + Cliente + " " + date + " " + Dni + " " + Estado + " " + Observacion + " " + Agente);
                        });
                        /*for(var i = 0; i<countcheck; i++){
                            var idcheck = $('.checkedsi:checkbox:checked')[i].data('idgagestion') ;
                            //var oll = idcheck.data('idgagestion');
                            console.log(idcheck);
                        }*/
                    }else{
                        $("#valiteChecked").modal('show');
                    }

                    console.log(countcheck);
                        /*$('.checkedsi:checkbox:checked').each(function(){
                            var checkedcliente= $('.checkedsi').is(":checked");
                            var countn = ($('.checkedsi:checkbox:checked')[counter]).data("idgagestion");
                            console.log(countn);
                            
                           if (checkedcliente==true) {
                                var Cliente=$('.Cliente').val();
                                var date=$('.date').val();
                                //var Numerotelefonico=$('.Numerotelefonico').val();
                                var Dni=$('.Dni').val(); 
                                var Estado=$('.Estado').val();
                                var Observacion=$('.Observacion').val();
                                var Agente=$('.Agente').val();
                            }
                            counter++;
                            alert(Dni);
                            //alert(Numerotelefonico);
                            alert(date);
                            alert(Cliente);
                            alert(Estado);
                            alert(Observacion);
                            alert(Agente); */
                    });


                           /* var checkedcliente= $('.checkedsi').is(":checked");
                            $(this).each(function(){
                                
                            });*/
                            
            });
            
            $("#tblmantenimiento").DataTable();                    
                /*$('#tableTelefono').DataTable({
                        pageLength: 10,
                        "columns": [
                                    { "data": "item" },
                                    { "data": "idcliente" },
                                    { "data": "fchgestio" },
                                    { "data": "telefono" },
                                    { "data": "documento" },
                                    { "data": "estado" },
                                    { "data": "observat" },
                                    { "data": "agente" },
                                    { "data": "checkbox" },
                            ],
                        paging: true,
                        searching: true,
                        order: [[0, "asc"]],
                        columnDefs: [{ orderable: false, targets: [9] }],
                                    fixedHeader: {
                                        header: true
                                    },
                                    "bDestroy": true
                });*/
                
             //});

           
            ///
                       /* $(function(){
                 $('.chckSelec').on('click',function(){
                    if ($(this).is(':chckSelec')) {
                        $('.Dni').attr('disabled','true');
                    }else{
                        $('.Dni').removeAttr('disabled');
                    }

                 });
            });*/
            /// Para validar los campos

            /// 
        </script>
    </body>
</html>