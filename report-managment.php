<!DOCTYPE html>
<html>
    <head>
        <title>Reporte Administrativo</title>
        <link rel='shortcut icon' href='assets/images/favicon.ico' />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="http://192.168.1.112/gps-sql/assets/css/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
        <link href="http://192.168.1.112/gps-sql/assets/datepicker/css/bootstrap-datepicker.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" type="text/css" href="http://192.168.1.112/gps-sql/assets/DataTables/media/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="http://192.168.1.112/gps-sql/assets/DataTables/media/css/dataTables.bootstrap.css">
        
        <style type="text/css"> 
        #packt {
            padding-top: 25px;
            padding-bottom: 25px;
            padding-right: 50px;
            padding-left: 50px;
        }
        table.fixedHeader-floating{position:fixed !important;background-color:white;top: -7px !important;}
        table.fixedHeader-floating.no-footer{border-bottom-width:0}
        table.fixedHeader-locked{position:absolute !important;background-color:white}
        @media print{table.fixedHeader-floating{display:none}}
        </style>
    </head>
    <body id="packt">
        <div class="container">
            <div class="row">
                <form role="form" id="formSearchReport" class="form-inline" method="GET">
                    <h2>Reporte Administrativo</h2>
                    <br/>
                    <div class="content">
                        <div class="input-group">
                            <input type="text" name="fchini" class="form-control date" placeholder="Fecha Inicio"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                        </div>

                        <div class="input-group">
                            <input type="text" name="fchfin" class="form-control date" placeholder="Fecha Fin"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                        </div>

                        <div class="input-group">
                            <select class="form-control" name="slctoperador">
                                <option value="">Seleccionar Operador</option>
                                <option value="A">Todos</option>
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
                            <select class="form-control" name="slctcliente">
                                <option value="">Seleccionar Cliente</option>
                                <option value="A">TODOS</option>
                                <option value="1">GPS</option>
                                <option value="2">CONECTA</option>
                                <option value="3">FRENOSA</option>
                            </select>
                        </div>
                    </div>
                    <br/>
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
                    <br/><br/>      	
                    <button type="button" id="btnSearchReport" name="btnSearchReport" class="btn btn-default">Buscar</button>
                </form>
            </div>
        </div>       
        <br/>
        <div class="container">
            <table id="reportTabl" class="table table-responsive table-striped display" cellspacing="0" width="100%">
                <thead style="font-size: 11px; font-weight: 600;">
                    <th>ITEM</th>
                    <th>NOMBRE</th>
                    <th>DOCUMENTO</th>
                    <th>FECH. GESTION</th>
                    <th>TELEFONO</th>
                    <th>COD. GESTION</th>
                    <th>TIP. GESTION</th>
                    <th>OBSERVACION</th>
                    <th>OPERADOR</th>
                    <th>DURACION(seg)</th>
                </thead>
                <tbody style="font-size: 11px;">                    
                </tbody>                
            </table>  
            <div id="resultData">                
            </div>
        </div>
        
        <script src="http://192.168.1.112/gps-sql/assets/js/jquery.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/css/bootstrap/js/bootstrap.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/DataTables/media/js/jquery.dataTables.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/DataTables/media/js/dataTables.bootstrap.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/datepicker/js/bootstrap-datepicker.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/jquery-validation/jquery.validate.js"></script>
        <script src="http://192.168.1.112/gps-sql/assets/jquery-validation/localization/messages_es_PE.min.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){
                $('.date').datepicker({
                    language: "es",
                    autoclose: true,
                    todayHighlight: true
                });
                
                $("#btnSearchReport").click(function(){
                    var alldata = $("#formSearchReport").serialize();
                    $.post('http://192.168.1.112/gps-sql/ajax/post-report-managment.php', alldata, function(response){                        
                        $('#reportTabl').DataTable( {                            
                            "ajax": "http://192.168.1.112/gps-sql/assets/file/report/report-managment.txt",
                            "columns": [
                                    { "data": "item" },
                                    { "data": "nombre" },
                                    { "data": "documento" },
                                    { "data": "fecha" },
                                    { "data": "telefono" },
                                    { "data": "tipogestion" },
                                    { "data": "codigogestion" },
                                    { "data": "observacion" },
                                    { "data": "agente" },
                                    { "data": "tiempollamada" }
                            ],
                            fixedHeader: {
                                header: true
                            },
                            "bDestroy": true
                        }); 
                        //$("#resultData").html(response);
                    });
                    
                });
            });
        </script>        
    </body>
</html>
