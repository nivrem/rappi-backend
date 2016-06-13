<!DOCTYPE html>
<html>
    <head>
        <title>Rappi Test</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Mervin Mujica">
        <meta name="_token" content="{!! csrf_token() !!}"/>
        <link rel="icon" type="image/jpg" href="{{asset("img/icon.jpg")}}">
        <!--<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">-->
        <link href="{{asset("css/bootstrap.css")}}" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="{{asset("js/jquery.1.11.1.js")}}"></script>
        <script type="text/javascript" src="{{asset("js/generico.js")}}"></script>
        <script type="text/javascript" src="{{asset("js/bootstrap.min.js")}}"></script>
    </head>
    <body>
        <div class="container container-fluid">
            <div class="jumbotron">
              <h2>Hackerrank - Cube Summation</h2>
            </div>
            <div id="div_tst_cases">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="test_cases">Numero de casos de prueba (T)</label>
                                <input type="number" name="test_cases" class="form-control" id="test_cases" placeholder="Cantidad">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <button type="button" id="siguiente_paso" class="btn btn-primary">Siguiente</button>
                            </div>
                        </div>
                    </div>
                </div>
            <div  id="inputs">
                
            </div>
            <hr>
            <div  id="outputs" class="panel panel-info" >
                <div class="panel-heading">
                <h3 class="panel-title">OUTPUT</h3>
              </div>
              <div class="panel-body">
                
              </div>
            </div>
                </div>
        <script type="text/javascript">
            var test_cases=0;
            var ind=0;
            var ind_ope=0;//indice de operacion
            var ind_case=0;//indice de caso actual
            var num_operations=0;
            $("#siguiente_paso").click(function(){
                test_cases=parseInt($("#test_cases").val());
                if (test_cases>0 && test_cases<=50){
                    $("#div_tst_cases").hide("slow");
                    $("#inputs").append(createTestCase(ind_case));
                }else{
                    alert("Valor incorrecto debe ingresar un numero entre 1 y 50");
                }
            });
            $("body").on("click","[id^='paso_1_']:button",function(){
                var n=$("#matriz"+ind_case).val();
                var m=$("#operations"+ind_case).val();
                if ((n>0 && n<=100) && (m>0 && m<=1000)){
                    num_operations=m;
                    submitAccionLocal("createMatrix", "form_create_"+ind_case, "", '{{route('createMatrix')}}', "POST");
                }else{
                    alert("error caso de prueba");
                }
            });
            $("body").on("click","[id^='paso_2_']:button",function(){
                var op=$("#operacion"+ind).val();
                switch(op){
                    case "1":
                        $("#inputs").append(createUpdate(ind));
                        break;
                    case "2":
                        $("#inputs").append(createQuery(ind));
                        break;
                    default:alert("Error operación desconocida");
                }
                $(this).hide();
            });
            $("body").on("click","[id^='update_']:button",function(){
                submitAccionLocal("update", "form_update_"+ind, "", '{{route('update')}}', "POST");
            });
            $("body").on("click","[id^='fetch_']:button",function(){
                submitAccionLocal("fetch", "form_fetch_"+ind, "", '{{route('fetch')}}', "POST");
            });
            $("body").on("click","#iniciar_nuevo",function(){
                iniciar();
            });
            
            function submitAccionLocal(tarea, formulario, capa, php, method) {
                switch (tarea) {
                    case "createMatrix":case "update":case "fetch":
                        enviarFormulario(tarea, formulario, capa, php, method);
                    break;
                }
            }
            function envioExitoso(response, textStatus, jqXHR, tarea, capa) {
                switch (tarea) {
                    case "createMatrix":
                        $("#inputs").append(createOperation(ind));
                        $("#paso_1_"+ind_case).remove();
                    break;
                    case "update":case "fetch":
                        if (Object.keys(response.errors).length>0){//ERRORES 
                            alert(response.errors);
                        }
                        if(response.msg!==undefined){
                            $(".panel-body").append("<div><b>"+(ind_ope+1)+" - "+response.msg+"</b></div>");
                            $("#"+tarea+"_"+ind).remove();
                            $("#form_"+tarea+"_"+ind).remove();
                            $("#operation__"+ind).remove();
                            ++ind;
                            ++ind_ope;
                            if (ind_ope<num_operations){
                                $("#inputs").append(createOperation(ind));
                            }else{
                                ind_ope=0;
                                $("#form_create_"+ind_case).remove();
                                ++ind_case;
                                if(ind_case<test_cases){
                                    $("#inputs").append(createTestCase(ind_case));
                                }else{
                                    $("#inputs").append('<button name="iniciar_nuevo" id="iniciar_nuevo">Iniciar de Nuevo</button>');
                                }
                            }
                        }
                        
                    break;
                }
            }
            function antesDeEnviar(jqXHR, settings, tarea, capa) {
            }
            
            function iniciar(){
                var test_cases=0;
                var ind_ope=0;//indice de operacion
                var ind_case=0;//indice de caso actual
                var num_operations=0;
                $("#div_tst_cases").show("slow");
                $("#iniciar_nuevo").remove();
            }
            
            function createTestCase(indice){
                return '<form name="form_create_'+indice+'" id="form_create_'+indice+'" method="POST" action="{{route('createMatrix')}}"><div class="form-group">'
                    +'<input type="hidden" name="_token" value="{!! csrf_token() !!}"/><h2>Caso de Prueba '+(indice+1)+'</h2>'
                    +'<div class="row">'
                        +'<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">'
                            +'<label>Tama&ntilde;o Matriz (N)</label>'
                        +'</div>'
                        +'<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">'
                            +'<input type="number" name="matriz" class="form-control" id="matriz'+indice+'" placeholder="Cantidad">'
                        +'</div>'
                        +'<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">'
                            +'<label >Cantidad de Operaciones (M)</label>'
                        +'</div>'
                        +'<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">'
                            +'<input type="number" name="operations'+indice+'" class="form-control" id="operations'+indice+'" placeholder="Cantidad">'
                        +'</div>'
                        +'<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">'
                            +'<button type="button" name="paso_1_'+indice+'" id="paso_1_'+indice+'" class="btn btn-success">Siguiente</button>'
                        +'</div>'
                    +'</div>'
                +'</div></form>';
            }
            
            function createOperation(indice){
                return '<div class="form-group" id="operation__'+indice+'">'
                    +'<div class="well well-lg">'
                        +'<h4>Operación '+(ind_ope+1)+'</h4>'
                        +'<div class="row">'
                            +'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                                +'<p><b>Seleccionar Operación</b></p>'
                                +'<p>1. UPDATE x y z W</p>'
                                +'<p>2. QUERY  x1 y1 z1 x2 y2 z2 </p>'
                            +'</div>'
                        +'</div>'
                        +'<div class="row">'
                            +'<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">'
                                +'<label>Operación</label>'
                            +'</div>'
                            +'<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">'
                                +'<select name="operacion'+indice+'" id="operacion'+indice+'" class="form-control">'
                                    +'<option value="1">1- UPDATE</option>'
                                    +'<option value="2">2- QUERY</option>'
                                +'</select>'
                            +'</div>'
                            +'<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">'
                                +'<button type="button" id="paso_2_'+indice+'" class="btn btn-success">Siguiente</button>'
                            +'</div>'
                        +'</div>'
                    +'</div>'
                +'</div>';
            }
            
            function createUpdate(indice){
                return '<form name="form_update_'+indice+'" id="form_update_'+indice+'" method="POST" action="{{route('update')}}"><div class="form-group">'
                    +'<fieldset>'
                        +'<legend>UPDATE</legend>'
                    +'</fieldset>'
                    +'<div class="row">'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<label>x</label>'
                        +'</div>'
                        +'<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">'
                            +'<input type="number" name="x" class="form-control" id="x1_'+indice+'" placeholder="x1">'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<label>y</label>'
                        +'</div>'
                        +'<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">'
                            +'<input type="number" name="y" class="form-control" id="y1_'+indice+'" placeholder="y1">'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<label>z</label>'
                        +'</div>'
                        +'<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">'
                            +'<input type="number" name="z" class="form-control" id="z1_'+indice+'" placeholder="z1">'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<label>W</label>'
                        +'</div>'
                        +'<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">'
                            +'<input type="number" name="w" class="form-control" id="w_'+indice+'" placeholder="W">'
                        +'</div>'
                    +'</div>'
                    +'<div class="row">'
                        +'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                            +'<button type="button" name="update_'+indice+'" id="update_'+indice+'" class="btn btn-primary">Actualizar</button>'
                        +'</div>'
                    +'</div>'
                +'</div></form>';
            }
            
            function createQuery(indice){
                return '<form name="form_fetch_'+indice+'" id="form_fetch_'+indice+'" method="POST" action="{{route('fetch')}}"><div class="form-group">'
                    +'<fieldset>'
                        +'<legend>QUERY</legend>'
                    +'</fieldset>'
                    +'<div class="row">'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<label>x1</label>'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<input type="number" name="x1" class="form-control" id="x1_'+indice+'" placeholder="x1">'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<label>y1</label>'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<input type="number" name="y1" class="form-control" id="y1_'+indice+'" placeholder="y1">'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<label>z1</label>'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<input type="number" name="z1" class="form-control" id="z1_'+indice+'" placeholder="z1">'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<label>x2</label>'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<input type="number" name="x2" class="form-control" id="x2_'+indice+'" placeholder="x2">'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<label>y2</label>'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<input type="number" name="y2" class="form-control" id="y2_'+indice+'" placeholder="y2">'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<label>z2</label>'
                        +'</div>'
                        +'<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">'
                            +'<input type="number" name="z2" class="form-control" id="z2_'+indice+'" placeholder="z2">'
                        +'</div>'
                    +'</div>'
                    +'<div class="row">'
                        +'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'
                            +'<button type="button" id="fetch_'+indice+'" name="fetch_'+indice+'" class="btn btn-primary">Consultar</button>'
                        +'</div>'
                    +'</div>'
                +'</div></form>';
            }
        </script>
    </body>
</html>
