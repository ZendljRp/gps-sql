<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title> Agent web client </title>
        <link rel='shortcut icon' href='assets/images/favicon.ico' />
        <!-- Optional theme -->
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/css/bootstrap.css" />
        <link href="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/fonts/glyphicons-halflings-regular.woff" />
        <link rel="stylesheet" href="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/css/bootstrap.css.map"  />

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
        <script src="http://192.168.1.7:8080/gps-sql/assets/css/bootstrap/js/bootstrap.js"></script>
        <script src="http://192.168.1.7:8080/gps-sql/assets/jquery-validation/jquery.validate.js"></script>
        <script src="http://192.168.1.7:8080/gps-sql/assets/jquery-validation/localization/messages_es_PE.js"></script>
        <script src="http://192.168.1.7:8080/gps-sql/assets/bootbox/bootbox.js"></script>
        <style type="text/css">
            /* Center the loader */
            .loader {
              position: absolute;
              left: 50%;
              top: 50%;
              z-index: 1;
              width: 150px;
              height: 150px;
              margin: -75px 0 0 -75px;
              border: 16px solid #f3f3f3;
              border-radius: 50%;
              border-top: 16px solid #3498db;
              width: 120px;
              height: 120px;
              -webkit-animation: spin 2s linear infinite;
              animation: spin 2s linear infinite;
              display: none;

                border-top: 16px solid #bfbfbf;
                border-right: 16px solid #cc3031;
                border-bottom: 16px solid #e9592c;
            }

            @-webkit-keyframes spin {
              0% { -webkit-transform: rotate(0deg); }
              100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
              0% { transform: rotate(0deg); }
              100% { transform: rotate(360deg); }
            }

            /* Add animation to "page content" */
            .animate-bottom {
              position: relative;
              -webkit-animation-name: animatebottom;
              -webkit-animation-duration: 1s;
              animation-name: animatebottom;
              animation-duration: 1s
            }

            @-webkit-keyframes animatebottom {
              from { bottom:-100px; opacity:0 } 
              to { bottom:0px; opacity:1 }
            }

            @keyframes animatebottom { 
              from{ bottom:-100px; opacity:0 } 
              to{ bottom:0; opacity:1 }
            }

            .block {
		display: block;
            }
            form.cmxform label.error {
                display: none;
            }
            #addNewPhone label.error {
		margin-left: 10px;
		width: auto;
		display: inline;
                color: red;
            }
        </style>        
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div id="loader-telf"> 
                    <div class="loader"></div>
                </div>
                <div class="container-fluid">
                    <h1>MANTENIMIENTO TELÉFONO</h1>
                    <br/>
                    <div class="row">                        
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="strDoc">TIPO Busqueda:</label>
                                        <select name="strDoc" id="strDoc" class="form-control" required>
                                            <option value="">Selecione tipo documento</option>
                                            <option value="telefono"> TEL&Eacute;FONO </option>
                                            <option value="dni"> DNI </option>
                                            <option value="ruc"> RUC </option>
                                            <option value="extranjeria"> CARNET DE EXTRANJER&Iacute;A </option>                                                
                                        </select>    
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="intNumdoc">NUMERO Busqueda:</label>
                                        <input name="intNumdoc" id="intNumdoc" type="text" class="form-control" value="">
                                    </div>
                                </div>
                            </div>
                        </div>                            
                        <table class="table table-responsive table-striped">
                            <thead>
                                <th>Item</th>
                                <th>NOMBRE</th>
                                <th>TEL&Eacute;FONO</th>
                                <th>OPERADOR</th>
                                <th>CD</th>
                            </thead>
                            <tbody id="tbTelfOper">
                            </tbody>
                        </table>
                    </div>
                    
                    <form class="form-inline" id="addNewPhone" name="addNewPhone" method="POST">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="strNombre">NOMBRE COMPLETO:</label>
                                    <input style="text-transform: uppercase" name="strNombre" id="strNombre" type="text" class="form-control" value="" required>
                                    <input type="hidden" name="nameDNI" id="nameDNI" value="" />
                                    <input type="hidden" name="agente" id="agente" value="<?=$_REQUEST['agent']?>" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="strTelf">TEL&Eacute;FONO:</label>
                                    <input name="strTelf" id="strTelf" type="text" class="form-control" value="" size="9" maxlength="9" required>
                                </div>
                            </div>                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="slctAgency">OPERADOR:</label>
                                    <select name="slctAgency" id="slctAgency" class="form-control" title="Seleccione opcion!" required>
                                        <option value="">Seleccione operador</option>
                                        <option value="MOVISTAR">MOVISTAR PERÚ</option>
                                        <option value="CLARO">CLARO PERÚ</option>  
                                        <option value="ENTEL">ENTEL PERÚ</option>
                                        <option value="BITEL">BITEL PERÚ</option>
                                        <option value="TUENTI">TUENTI PERÚ</option>
                                        <option value="OTROS">OTROS</option>   
                                    </select>
                                </div>  
                            </div>                       
                        </div>                        
                        <input type="submit" id="btnAddPhone" name="btnAddPhone" value="AGREGAR" class="btn btn-default" />
                    </form>
                </div>
            </div>
            <br/>
            <!-- Modal Guardado-->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Correcto</h4>
                        </div>
                        <div class="modal-body">
                            <p>El telefono se agrego correctamente.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Agregar-->
            <div id="myModalAdd" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Agregar</h4>
                        </div>
                        <div class="modal-body">
                            <p>No se encunetra registrado en nuestra base de datos el numero telefonico, para este documento, agregarlo por favor..</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <!-- Modal Advertencia-->
            <div id="myWarring" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Advertencia</h4>
                        </div>
                        <div class="modal-body">
                            <p>El número telefónico, ya existe.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Confirmar-->
            <div id="myWarring" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Confirmar</h4>
                        </div>
                        <div class="modal-body">
                            <p>¿Esta seguro de actualizar los dato?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#intNumdoc").keyup(function(){
                    var numdoc = $(this).val();
                    var typeDoc = $("#strDoc").val();                    
                    if(numdoc.length >= 8 && typeDoc != ""){
                        $(".loader").css('display', 'block');
                        $.post('http://192.168.1.7:8080/gps-sql/ajax/post-numphone.php', {doc:numdoc, typ:typeDoc}, function(data){
                            if(data != "0"){
                                $(".loader").css('display', 'none');
                                $("#tbTelfOper").html(data);
                            }else{
                                $("#strNombre").val("").attr('disabled', false);
                                $("#nameDNI").val('');
                                $(".loader").css('display', 'none');
                                $("#tbTelfOper").remove();
                                $("#myModalAdd").modal();                                
                            }                        
                        });
                    }                    
                });
                $("#strTelf").keydown(function (e) {
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
                               
                $("#slctAgency").validate({
                    
                });
                $.validator.setDefaults({
                    submitHandler: function() {
                        var strDoc    = $("#strDoc").val();
                        var intNumdoc = $("#intNumdoc").val();
                        var strNombre = $("#strNombre").val();
                        var strTelf   = $("#strTelf").val();
                        var agente   = $("#agente").val();
                        var slctOperator = $("#slctAgency option:selected").val();
                        var datos = "strDoc="+strDoc+"&intNumdoc="+intNumdoc+"&strNombre="+strNombre+"&strTelf="+strTelf+"&slctOperator="+slctOperator+"&agente="+agente;
                        $.post('http://192.168.1.7:8080/gps-sql/ajax/insert-new-phone.php', {fulldata:datos}, function(response){
                            if(response == "ok"){
                                $("#myModal").modal();
                                $("#strDoc").val('');
                                $("#strNombre").val("").attr('disabled', false);
                                $("#nameDNI").val('');
                                //location.reload();                            
                            }else if(response == "701"){
                                $("#myWarring").modal();
                                //location.reload();
                            }else{
                                console.log(response);
                            }
                        });                        
                    }
                });
               
                $( "#addNewPhone" ).validate( {
                    rules: {
                        strNombre: "required",
                        strTelf: {
                            required: true,
                            minlength: 7,
                            maxlength: 9
                        }
                    },
                    errorElement: "em",
                    errorPlacement: function ( error, element ) {
                        // Add the `help-block` class to the error element
                        error.addClass( "alert-danger" );

                        // Add `has-feedback` class to the parent div.form-group
                        // in order to add icons to inputs
                        element.parents( ".col-sm-5" ).addClass( "has-error" );

                        if ( element.prop( "type" ) === "checkbox" ) {
                            error.insertAfter( element.parent( "label" ) );
                        } else {
                            error.insertAfter( element );
                        }
                        // Add the span element, if doesn't exists, and apply the icon classes to it.
                        if ( !element.next( "span" )[ 0 ] ) {
                            $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
                        }
                    },
                    success: function ( label, element ) {
                        // Add the span element, if doesn't exists, and apply the icon classes to it.
                        if ( !$( element ).next( "span" )[ 0 ] ) {
                            $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
                        }
                    },
                    highlight: function ( element, errorClass, validClass ) {
                        $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
                        $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
                    },
                    unhighlight: function ( element, errorClass, validClass ) {
                        $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
                        $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
                    }
                } );          
                
            });
        </script>
        
    </body>
</html>