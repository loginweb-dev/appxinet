@extends('master')


@section('content')
    <div class="container-fluid" id="miul" hidden>
        <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="Perfil" >
        <div class="row">

            <div class="col s12">
                <form action="{{route('registro_cliente')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="input-field col s3">
                            <label for="codigo">Codigo</label>
                            <input placeholder="Codigo"  type="number" id="codigo" name="codigo" value="+591" required readonly>
                        </div>
                        <div class="input-field col s9">
                            <label for="phone">Teléfono</label>
                            <input type="number" class="validate" id="phone" name="phone" placeholder="Número de Celular" value="{{ old('phone') }}" required>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <label for="firstname">Nombres (*)</label>
                                <input type="text" class="validate" id="firstname" name="firstname" placeholder="Ingrese tu nombre" value="{{ old('firstname') }}" required>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <label for="lastname">Apellidos (*)</label>
                                <input type="text" class="validate" id="lastname" name="lastname" placeholder="Ingrese tu apellido" value="{{ old('lastname') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <label for="lastname">Localidad (*)</label>
                            <select class="browser-default" name="ciudad_id" id="ciudad_select"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <input id="imgcliente" name="imgcliente" type="file">
                                    <i class="material-icons">photo_library</i>
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path" name="imgcliente" type="text" placeholder="Selfy">
                                </div>
                            </div>
                        </div>
                    </div>
                    <center>
                        <button id="btn_enviar" style="background-color: #0C2746;" class="btn waves-effect waves-light" type="submit" name="action">Editar Perfil </button>
                        <a style="background-color: #0C2746;" onclick="salir()" class="waves-effect waves-light btn">Salir</a>
                    </center>
                </form>
            </div>
        </div>
    </div>
    <div id="modal1" class="modal bottom-sheet">
        <div class="modal-content">
            <h5>Cual es tu Telefono</h5>
            <div class="row">
                <div class="col s9">
                    <input placeholder="Ingresa tu telefono - 8 digitos" id="telefono" type="number" class="validate">
                </div>
                <div class="col s3">
                    <a style="background-color: #0C2746;"  id="btn_telefono" href="#" onclick="get_cliente()" class="waves-effect waves-light btn"><i class="material-icons">search</i></a>
                </div>
            </div>
            <div class="row">
                <div class="col s9">
                    <input placeholder="PIN - 4 digitos" id="pin" type="number" class="validate" disabled>
                </div>
                <div class="col s3">
                    <a style="background-color: #0C2746;" id="btn_pin" href="#" onclick="get_pin()" class="waves-effect waves-light btn" disabled><i class="material-icons">key</i></a>
                </div>
            </div>
        </div>
    </div>
    <div id="modal1" class="modal bottom-sheet">
        <div class="modal-content">
            <h5>Cual es tu Telefono</h5>
            <div class="row">
                <div class="col s9">
                    <input placeholder="Ingresa tu telefono - 8 digitos" id="telefono" type="number" class="validate">
                </div>
                <div class="col s3">
                    <a style="background-color: #0C2746;"  id="btn_telefono" href="#" onclick="get_cliente()" class="waves-effect waves-light btn"><i class="material-icons">search</i></a>
                </div>
            </div>
            <div class="row">
                <div class="col s9">
                    <input placeholder="PIN - 4 digitos" id="pin" type="number" class="validate" disabled>
                </div>
                <div class="col s3">
                    <a style="background-color: #0C2746;" id="btn_pin" href="#" onclick="get_pin()" class="waves-effect waves-light btn" disabled><i class="material-icons">key</i></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function(){
            $('.modal').modal();
            var miuser = JSON.parse(localStorage.getItem('miuser'))
            if (miuser) {
                M.toast({html: 'Bienvenido! '+miuser.nombres+' '+miuser.apellidos})
                $("#miul").attr('hidden', false)
                $("#phone").val(miuser.telefono)
            } else {
                $('#modal1').modal('open')
            }
        });
        function salir() {
            M.toast({html: 'Vuelve pronto'})
            localStorage.removeItem("miuser")
            location.href= "/welcome"
        }

        async function get_cliente() {
            var telefono = $("#telefono").val()
            if (telefono == '') {
                M.toast({html : 'Ingresa un telefono valido'})
            } else {
                var midata={'telefono':telefono}
                var miuser = await axios.post("{{ setting('admin.url_api') }}cliente/by", midata)
                if (miuser.data) {
                    var pin = Math.floor(1000 + Math.random() * 9000);
                    var cliente = await axios("{{ setting('admin.url_api') }}pin/save/"+miuser.data.id+"/"+pin)
                    var mensaje="Hola, tu pin para acceder a APPXI es: "+pin
                    var wpp= await axios("https://chatbot.appxi.net/?type=text&phone="+telefono+"&message="+mensaje)
                    M.toast({html : 'Revisa tu Whatsapp'})
                    $("#telefono").attr('disabled', true);
                    $("#btn_telefono").attr('disabled', true);
                    $("#pin").attr('disabled', false);
                    $("#btn_pin").attr('disabled', false);
                } else {
                    location.href= '/cliente/crear'
                }
            }
        }
        async function get_pin() {
            var pin = $("#pin").val()
            var telefono = $("#telefono").val()
            var miuser = await axios.post("{{ setting('admin.url_api') }}pin/get", midata)
            if (miuser.data) {
                localStorage.setItem('miuser', JSON.stringify(miuser.data))
                $('#modal1').modal('close')
                M.toast({html : 'Bienvenido'})
                $("#miul").attr('hidden', false);
            }else{
                M.toast({html : 'Credenciales Invalidas'})
            }
        }
    </script>
@endsection
