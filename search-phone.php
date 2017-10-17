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
        
        <link rel="stylesheet" href="http://192.168.1.112/gps-sql/assets/css/bootstrap/css/bootstrap.css.map"  />

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
        <script src="http://192.168.1.112/gps-sql/assets/css/bootstrap/js/bootstrap.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="container-fluid"><br/>
                    <form class="form-inline" id="searchPhone" name="searchPhone" method="POST">
                        <div class="row">                            
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="strDoc">TIPO Busqueda:</label>
                                            <select name="strDoc" id="strDoc" class="form-control">
                                                <option value="">Selecione tipo documento</option>
                                                <option value="dni"> DNI </option>
                                                <option value="ruc"> RUC </option>
                                                <option value="extranjeria"> CARNET DE EXTRANJER&Iacute;A </option>
                                                <option value="telefono"> TEL&Eacute;FONO </option>
                                            </select>    
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="intNumdoc">NUMERO Busqueda:</label>
                                            <input name="intNumdoc" id="intNumdoc" type="text" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                      <input type="button" id="btnBuscar" name="btnBuscar" value="Buscar" class="btn btn-default" />  
                                    </div> 
                                </div>                                     
                            </div>
                            <br/>
                            <br/>
                            <table class="table table-responsive table-striped">
                                <thead>
                                    <th>Item</th>
                                    <th>NOMBRE</th>
                                    <th>TEL&Eacute;FONO</th>
                                    <th>OPERADOR</th>
                                </thead>
                                <tbody id="tbTelfOper">                                    
                                </tbody>
                            </table>                           
                        </div>                        
                    </form>
                </div>
            </div>
            <br/>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#btnBuscar").on("click",function(){
                var alldata = $("#searchPhone").serialize();
                $.post('http://localhost/gps-sql/ajax/post-search-phone.php', alldata, function(data){
                            //console.log(data);
                            if(data != "0"){
//                                $("#strNombre").val(data).attr('disabled', true);
//                                $("#nameDNI").val(data);
                                $("#tbTelfOper").html(data);
                            }else{
                                $("#strNombre").val("").attr('disabled', false);
                                $("#nameDNI").val('');
                                alert("No se ha encontrado, numero telefonico, para este documento, agregarlo por favor."); 
                            }                        
                        });
                });
                /*$("#intNumdoc").keyup(function(){
                    var numdoc = $(this).val();
                    var typeDoc = $("#strDoc").val();                    
                    if(numdoc.length >= 8 && typeDoc != ""){
                        console.log(numdoc + " " + typeDoc);
                        $.post('http://localhost/gps-sql/ajax/post-numphone.php', {doc:numdoc, typ:typeDoc}, function(data){
                            //console.log(data);
                            if(data != "0"){
//                                $("#strNombre").val(data).attr('disabled', true);
//                                $("#nameDNI").val(data);
                                $("#tbTelfOper").html(data);
                            }else{
                                $("#strNombre").val("").attr('disabled', false);
                                $("#nameDNI").val('');
                                alert("No se ha encontrado, numero telefonico, para este documento, agregarlo por favor."); 
                            }                        
                        });
                    }                    
                });*/
                
                $("#intNumdoc").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                         // Allow: Ctrl+A, Command+A
                        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
                         // Allow: home, end, left, right, down, up
                        (e.keyCode >= 35 && e.keyCode <= 40)) {
                             // let it happen, don't do anything
                             return;
                    }
                    // Ensure that it is a number and stop the keypress
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                });
                
                $("#btnAddPhone").on('click', function(){
                    //alert("Hello World");
                    var strDoc    = $("#strDoc").val();
                    var intNumdoc = $("#intNumdoc").val();
                    var strNombre = $("#strNombre").val();
                    var strTelf   = $("#strTelf").val();
                    var slctOperator = $("#slctAgency option:selected").val();
                    var datos = "strDoc="+strDoc+"&intNumdoc="+intNumdoc+"&strNombre="+strNombre+"&strTelf="+strTelf+"&slctOperator="+slctOperator;
                    //var datos = $("#addNewPhone").serialize();
                    console.log(datos);
                    $.post('http://localhost/gps-sql/ajax/insert-new-phone.php', {fulldata:datos}, function(response){
                        if(response == "1"){
                            alert("Se agrego correctamente el telefono, a la persona en cuestion.");
                            $("#strNombre").val("").attr('disabled', false);
                            $("#nameDNI").val('');
                            $("#addNewPhone")[0].reset();                            
                        }else{
                            console.log(response);
                        }
                    });
                    
                });                
            });
        </script>
        
    </body>
</html>