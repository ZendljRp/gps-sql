<?php
include 'conecction/conecction.php';
set_time_limit (180);
$conn = conn();
$strData ="";
 $sql = "SELECT TOP 10 idGagestion As id, idCliente AS Cliente,datfechagestion AS fechagestion,
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
    function getEstado($descripcion)
    {
        $conn = conn();
        $sqlestado =" SELECT  varDesautodial As descripcion , varCodautodial As Codigo
                        FROM estadosgestion"; 
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
        $strestado = "<select name='descripcion' class='Estado'><option value=''>seleccione Estado</option>";
          while( $rowEstado = sqlsrv_fetch_object($stmtEstado) ) {
                $selectestado = ($rowEstado->descripcion == $descripcion)?"selected":"";      
                $strestado .="<option value='".$rowEstado->descripcion."' $selectestado>".$rowEstado->descripcion."</option>";
            }
               $strestado .=  "</select>";
            return $strestado;
    }

    function getcliente($idcliente)
    {
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
        $strcliente = "<select name='cliente' class='Cliente'><option value=''>seleccione cliente</option>";
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
      $strData .= "<tr id='".$row->id."'>
                        <td>".$i."</td>
                        <td>".getcliente($row->Cliente)."</td>
                        <td style='text-align:center;'><input type='text' name='Fechagestion' class='form-control date' placeholder='fechagestion' value=".$row->fechagestion->format('d/m/Y')."></input></td>
                        <td>".$row->Numerotelefonico."</td>
                        <td><input type='text' class='text Dni' name='Dni' value='".$row->DNI."'/></td>
                        <td>".getEstado($row->Estado)."</td>
                        <td><textarea type='text' class='Observacion'>".$row->Observacion."</textarea></td>
                        <td><input type='text' class='Agente' value='".$row->Agente."'/></td>
                        <td style='text-align:center;'><input class='chckSelec checkedsi' type='checkbox' name='chckSelec' data-idgagestion='".$row->id."'/></td>
                    </tr>";
                $i++;
            }

if (!empty($_POST["btnAgregar"])) {
    $conn = conn();
    $strData = "";
    $addSQL  = "";
    $strResp = "";
    $seleccione="";
    $predni  = $_REQUEST["strInput"];
    $pretelf  = $_REQUEST["strInput"];
    $DNI = str_replace("'", "", $predni);
    $agency = $_POST["slctAgency"];
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
        <link rel="stylesheet" href="http://192.168.1.112/gps-sql/assets/css/bootstrap/css/bootstrap-theme.css" />
        
        <link rel="stylesheet" href="http://192.168.1.112/gps-sql/assets/css/bootstrap/css/bootstrap.min.css"  />
        
        <link rel="stylesheet" href="http://localhost/gps-sql/assets/css/bootstrap/css/bootstrap.css.map"  />        
        <link rel="stylesheet" href="http://localhost/gps-sql/assets/datatables/dataTables.css"  />
        <link rel="stylesheet" href="http://localhost/gps-sql/assets/datatables/DataTables/css/dataTables.bootstrap.css"  />
        <link rel="stylesheet" href="http://localhost/gps-sql/assets/alertafy/css/alertify.min.css" />
        <link rel="stylesheet" href="http://localhost/gps-sql/assets/alertafy/css/themes/default.min.css" />
        <link href="http://localhost/gps-sql/assets/datepicker/css/bootstrap-datepicker.css" rel="stylesheet" media="screen" />
        <link rel="stylesheet" type="text/css" href="http://localhost/gps-sql/assets/dataTables/DataTables/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/gps-sql/assets/dataTables/DataTables/css/dataTables.bootstrap.css">

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="http://localhost/gps-sql/assets/js/jquery.2.2.4.js" crossorigin="anonymous"></script>
        <script src="http://localhost/gps-sql/assets/css/bootstrap/js/bootstrap.js" crossorigin="anonymous"></script>         
        <script src="http://localhost/gps-sql/assets/dataTables/DataTables/js/jquery.dataTables.js"></script>
        <script src="http://localhost/gps-sql/assets/dataTables/DataTables/js/dataTables.bootstrap.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>
        <script src="http://localhost/gps-sql/assets/alertafy/alertify.min.js"></script>
        <script src="http://localhost/gps-sql/assets/datepicker/js/bootstrap-datepicker.js"></script>
        <script src="http://localhost/gps-sql/assets/datepicker/locales/bootstrap-datepicker.es.min.js"></script>    
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
                     <h1 class="text-center">Mantenimiento de Busqueda por Cliente</h1>
                    <form class="form-inline"  onsubmit="return validar()" id="formatBusquedaTelefono" name="formBusquedaTelefono" method="POST" action="BusquedaTelefono.php" >
                         
                        <br>
                        <input type="button" id="btnAgregar" name="btnAgregar" value="Agregar" class="btn btn-default"/>
                    </form>
                </div>
            </div>
            <br/>
            <div  class="container">
                <div class="container" id="tableCliente">
                    <table id="tableTelefono" class="table table-striped table-responsive display" border="1">
                        <thead>
                                <th class="text-center" bgcolor="#F9E79F">Item</th>
                                <th class="text-center" bgcolor="#F9E79F">CLIENTE</th> 
                                <th class="text-center" bgcolor="#F9E79F">FECHA. DE. GESTION</th> 
                                <th class="text-center" bgcolor="#F9E79F">TELEFONO</th>  
                                <th class="text-center" bgcolor="#F9E79F">DNI</th>
                                <th class="text-center" bgcolor="#F9E79F">ESTADO</th>  
                                <th class="text-center" bgcolor="#F9E79F">OBSERVACION</th>  
                                <th class="text-center" bgcolor="#F9E79F">AGENTE</th>
                                <th class="text-center" bgcolor="#F9E79F">SELECCIONAR <input id="checkedsi" class='chckSelec' type='checkbox' name='chckSelec' value="1" /></th>
                        </thead>
                        <tbody>
                            <?php echo !empty($strData)?$strData:"";?>
                        </tbody>
                    </table>
                        <ul class = "pagination">
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">&laquo;</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">1</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">2</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">3</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">4</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">5</a></li>
                           <li><a href = "http://localhost/gps-sql/BusquedaTelefono.php">&raquo;</a></li>
                        </ul>
                </div>        
            </div>
        </div> 
        <script type="text/JavaScript">
            function validar(){
                    var micampo = document.getElementById("strInput").value;
                    if (micampo.length== 0|| /^\s+$/.test(micampo)) {
                        alert('Los campos de textos estan vacio');
                        return false;
                    }
                    var miCombo = document.getElementById("slctAgency").value;
                        if(miCombo == ""){     
                        alert('Debe Elegir una opcion en el combo!');
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
                    $(".checkseleccione").bind("click",function()
                        {
                         if ($(this).attr("checked")==true)
                        {
                         $("input.chckSelec").each(function(){ $(this).attr("checked",true); });
                        }
                        else
                        {
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
                        $.post('http://localhost/gps-sql/ajax/BusquedaTelefono.php', alldata, function(response){
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
                            url  : 'BusquedaTelefono.php',
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
                        $('.checkedsi:checkbox:checked').each(function(){
                            var checkedcliente= $('.checkedsi').is(":checked");
                            var countn = $('.checkedsi:checkbox:checked').size();
                            console.log($('.checkedsi:checkbox:checked')[0]);


                           if (checkedcliente==true) {
                                var Cliente=$('.Cliente').val();
                                var date=$('.date').val();
                                //var Numerotelefonico=$('.Numerotelefonico').val();
                                var Dni=$('.Dni').val(); 
                                var Estado=$('.Estado').val();
                                var Observacion=$('.Observacion').val();
                                var Agente=$('.Agente').val();
                            }
                            alert(Dni);
                            //alert(Numerotelefonico);
                            alert(date);
                            alert(Cliente);
                            alert(Estado);
                            alert(Observacion);
                            alert(Agente); 
                        })


                           /* var checkedcliente= $('.checkedsi').is(":checked");
                            $(this).each(function(){
                                
                            });*/
                            
                    });




                    
                /*$('#tableTelefono').DataTable({

                        pageLength: 10,
                        "columns": [
                                    { "data": "item" },
                                    { "data": "Cliente" },
                                    { "data": "Fecha de gestion" },
                                    { "data": "telefono" },
                                    { "data": "dni" },
                                    { "data": "estado" },
                                    { "data": "observacion" },
                                    { "data": "agente" },
                                    { "data": "seleccionar" },
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
             });

           
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