<?php $agente = $_GET["agente"];?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title> Agent web client </title>
        <link rel='shortcut icon' href='assets/images/favicon.ico' />
        <!-- Optional theme -->
        <link rel="stylesheet" href="http://localhost/gps-sql/assets/css/bootstrap/css/bootstrap-theme.css" />
        
        <link rel="stylesheet" href="http://localhost/gps-sql/assets/css/bootstrap/css/bootstrap.min.css"  />
        
        <link rel="stylesheet" href="http://localhost/gps-sql/assets/css/bootstrap/css/bootstrap.css.map"  />
        <!-- Latest compiled and minified JavaScript -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <head>
        <title>Busqueda de Información por persona</title>
        <link rel='shortcut icon' href='assets/images/favicon.ico' />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="assets/css/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
        <link href="assets/datepicker/css/bootstrap-datepicker.css" rel="stylesheet" media="screen">
        <style type="text/css"> 
        #packt {
            padding-top: 25px;
            padding-bottom: 25px;
            padding-right: 50px;
            padding-left: 50px;
        }
        </style>
    </head>
    <body id="packt">
        <form role="form" id="formSearchReport" class="form-inline">
            <h1 class="text-center">Busqueda de Información por persona</h1>
            <br><br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                   <div class="col-sm-3">
                        <div class="form-group">
                            <label for="strInput">Dni Cliente:</label>
                            <input name="strInput" id="strInput" type="text" class="form-control" value="<?php echo !empty($_GET["strInput"])?$_GET["strInput"]:"";?>">
                            <label><?php echo  !empty($dni)?" DNI: " .  $dni:"";?></label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="strtelf">Telefono:</label>
                            <input name="strtelf" id="strtelf" type="text" class="form-control" value="<?php echo !empty($_GET["strtelf"])?$_GET["strtelf"]:"";?>">
                            <label><?php echo  !empty($Telefono)?" Telefono: " .  $Telefono:"";?></label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group date">
                            <input type="text" name="fchini" class="form-control" placeholder="Fecha Inicio"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                        </div>
                        <input type="hidden" name="hdagente" value="<?=$agente?>" id="hdagente" />
                    </div>
                            <input type="button" id="btnGrabar" name="btnGrabar" value="Grabar" class="btn btn-default" />  
                    </div>
                </div>
                <br/>
                <br/>
                <br/>
                <br/>
                <div class="col-sm-3">
                    <label>Comentario:
                    <br/>
                    <textarea class="form-control" id="txtcomentario" type="text" name="Comentario" rows="5" cols="60"></textarea>
                    </label>
                </div>
            </div>           
        </form>
               <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
        <script src="http://localhost/gps-sql/assets/css/bootstrap/js/bootstrap.js"></script>
        <script src="http://localhost/gps-sql/assets/datepicker/js/bootstrap-datepicker.js"></script>
        <script src="http://localhost/gps-sql/assets/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
        <script type="text/javascript"></script>
                <script type="text/javascript">
            $(document).ready(function(){
                $('.date').datepicker({
                    language: "es",
                    autoclose: true,
                    todayHighlight: true
                });
                
                $("#btnGrabar").click(function(){
                    var alldata = $("#formSearchReport").serialize();
                    alert(alldata);
                    $.post('http://localhost/gps-sql/ajax/post-Search-Client.php', alldata, function(response){
                        console.log(response); 
                        $("#resultData").attr(response);
                    });
                    
                });
            });
        </script>  
    </body>
</html>