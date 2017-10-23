<!DOCTYPE html>
<html>
    <head>
        <title>Reporte Administrativo</title>
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
            <h2>Reporte Administrativo</h2>
            <br><br>
            <div>
                <div class="input-group date">
                    <input type="text" name="fchini" class="form-control" placeholder="Fecha Inicio"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>

                <div class="input-group date">
                    <input type="text" name="fchfin" class="form-control" placeholder="Fecha Fin"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
                
                <div class="input-group">
                    <select class="form-control" name="slctoperador">
                        <option value="">Seleccionar Operador</option>
                        <option value="1">Todos</option>
                        <option value="operador1">Operador 1</option>
                        <option value="operador2">Operador 2</option>
                        <option value="operador3">Operador 3</option>
                        <option value="operador4">Operador 4</option>
                        <option value="operador5">Operador 5</option>
                        <option value="operador6">Operador 6</option>
                        <option value="VDAD">VDAD</option>
                    </select>
                </div>
                
                <div class="input-group">
                    <select class="form-control" name="slctstatus">
                        <option value="">Seleccionar Agencia</option>
                        <option value="1">TODOS</option>
                        <option value="2">DIRCON</option>
                        <option value="3">GPS</option>
                        <option value="4">CONECTA</option>
                    </select>
                </div>
            </div>
            
            <br><br>
            <div class="radio">
                <label>
                    <input type="radio" name="optionsRadios" value="efectiva" id="radio1">
                    Rpta. Efectiva
                </label>
            </div>

            <div class="radio">
                <label>
                    <input type="radio" name="optionsRadios" value="noefectiva" id="radio2">
                    Sin respuesta
                </label>
            </div>
            <br><br>            	
            <button type="submit" id="btnSearchReport" name="btnSearchReport" class="btn btn-default">Buscar</button>
        </form>
        
        <div class="container hide">
            <table class="table table-responsive table-striped">
                <thead>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>                
            </table>  
            <div id="resultData">
                
            </div>
        </div>
        
        <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
        <script src="http://192.168.1.112/gps-sql/assets/css/bootstrap/js/bootstrap.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/datepicker/js/bootstrap-datepicker.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/jquery-validation/jquery.validate.js">
        <script src="http://192.168.1.112/gps-sql/assets/jquery-validation/localization/messages_es_PE.js">
        <script type="text/javascript">
            $(document).ready(function(){
                $('.date').datepicker({
                    language: "es",
                    autoclose: true,
                    todayHighlight: true
                });
                
                $("#btnSearchReport").click(function(){
                    var alldata = $("#formSearchReport").serialize();
                    alert(alldata);
                    $.post('http://192.168.1.112/gps-sql/ajax/post-report-managment.php', alldata, function(response){
                        console.log(response); 
                        $("#resultData").attr(response);
                    });
                    
                });

                $("#tblReportManagment").datatable();
            });
        </script>        
    </body>
</html>
