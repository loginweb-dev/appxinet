@extends('master')


@section('content')


    <div class="container-fluid" id="miul" hidden>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)

                        @if($error=="The categoria id must be an integer.")
                            <span>Categoria y Ciudad son datos necesarios</span>


                            @elseif($error=="The ciudad id must be an integer." )
                            <li>Categoria y Ciudad son datos necesarios</li>

                            @else
                            <li>{{ $error }}</li>

                        @endif

                    @endforeach
                </ul>
            </div>

        @endif

        <form action="{{route('update_chofer')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

            <div class="row">
                <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="Perfil" ><br>
                <div class="col s5">
                    <h5>Perfil Conductor(a)</h5>
                </div>
                <div class="col s7">
                    <div id="verification" class="input-field col s4" ></div>
                    <div id="perfil_chofer" class="input-field col s8"></div>
                    <div class="divider"></div>
                </div>
                <div class="input-field col s6">
                    <label for="firstname">Nombres</label>
                    <input type="text" class="validate" id="firstname" name="firstname" placeholder="Ingrese sus nombres" required>
                </div>
                <div class="input-field col s6">
                    <label for="lastname">Apellidos</label>
                    <input type="text" class="validate" id="lastname" name="lastname" placeholder="Ingrese sus apellidos"  required>
                </div>
                <div class="input-field col s6">
                    <label for="email">Email</label>
                    <input type="email" class="validate" id="email" name="email" placeholder="Email"  required>
                </div>

                <div class="input-field col s6">
                    <label for="phone">Teléfono</label>
                    <input type="number" class="validate" id="phone" name="phone" placeholder="Número de Celular"  readonly>
                </div>

                <div class="input-field col s6">
                    <label for="verificacion">Estado de Verificación</label>
                    <input type="number" class="validate" id="verificacion" name="verificacion" placeholder="Estado de Verificación"   readonly>
                </div>

                <div class="input-field col s6">
                    <label for="verificacion">Categoria</label>
                    <input type="text" class="validate" id="micategoria" name="micategoria" placeholder="Categoría" readonly>
                </div>

            </div>
            <div class="row">
                <div class="col s6">
                    <p>Categoria</p>
                    <select class="browser-default" name="categoria_id" id="categoria_select" required></select>
                </div>
                <div class="col s6">
                    <p>Ciudad</p>
                    <select class="browser-default" name="ciudad_id" id="ciudad_select" required></select>
                </div>
            </div>
            <div class="row">

                <div class="file-field input-field">
                    <div class="btn">
                        <input id="imgchofer" name="imgchofer" type="file"  >
                        <i class="material-icons">photo_library</i>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" name="imgchofer" type="text" placeholder="Editar Foto de Perfil"  >
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <div class="row">
                <h6><b>Vehículo</b></h6>
                <div id="vehiculo_chofer" class="input-field col s6">
                </div>
                <div class="input-field col s6">
                    <div class="file-field input-field">
                        <div class="btn">
                            {{-- <span>Seleccione Archivo</span> --}}
                            <input id="imgfotosdelvehiculo" name="imgfotosdelvehiculo" type="file"  >
                            <i class="material-icons">photo_size_select_actual</i>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" name="imgfotosdelvehiculo" type="text" placeholder="Edit Vehículo" >
                        </div>
                    </div>
                </div>

            </div>
            <div class="divider"></div>

            <div class="row">

                <div class="input-field col s6">
                    <h6><b>Cédula de Identidad</b></h6>
                </div>
                <div class="input-field col s6">
                    <h6><b>Licencia de Conducir</b></h6>
                </div>
                <div id="carnet_chofer" class="input-field col s6">

                </div>

                <div id="licencia_chofer" class="input-field col s6">

                </div>
                <div class="row">
                    <div class="input-field col s6">

                        <div class="file-field input-field">
                            <div class="btn">
                                {{-- <span>Seleccione Archivos</span> --}}
                                <input id="imgcarnet" name="imgcarnet[]" type="file" multiple  >
                                <i class="material-icons">photo_size_select_actual</i>
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" name="imgcarnet[]"  type="text" placeholder="Edit Carnet" >
                            </div>
                        </div>
                    </div>
                    <div class="input-field col s6">
                        <div class="file-field input-field">
                            <div class="btn">
                                <input id="imglicencia" name="imglicencia[]"  type="file" multiple  >
                                <i class="material-icons">photo_size_select_actual</i>
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" name="imglicencia[]" type="text" placeholder="Edit Licencia" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider"></div>

            <center>
                <button style="background-color: #0C2746;" class="btn waves-effect waves-light" type="submit" name="action">Actualizar
                    <i class="material-icons right">save</i>
                </button>
            </center>

        </form>
        <a  style="background-color: #0C2746;" onclick="salir()" class="waves-effect waves-light btn">Salir</a>
    </div>


    <div id="modal1" class="modal bottom-sheet">
        <div class="modal-content">
            <h5>Cual es tu WhatsApp ?</h5>
            <small>para ingresar al sistema, te enviaremos un pin a tu WhatsApp</small>
            <div class="row">
                <div class="col s9">
                    <input placeholder="Ingresa tu telefono - 8 digitos" id="telefono" type="number" class="validate">
                </div>
                <div class="col s3">
                    <a id="btn_telefono" style="background-color: #0C2746;" onclick="get_chofer()" class="waves-effect waves-light btn"><i class="material-icons">search</i></a>
                </div>
            </div>
            <div class="row">
                <div class="col s9">
                    <input placeholder="PIN - 4 digitos" id="pin" type="number" class="validate" disabled>
                </div>
                <div class="col s3">
                    <a id="btn_pin" style="background-color: #0C2746;"  onclick="get_pin()" class="waves-effect waves-light btn" disabled><i class="material-icons">key</i></a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')

<script>
    $(document).ready( function(){
        $('.tooltipped').tooltip();
        $('.modal').modal();
        $('select').formSelect();

        RecargarChofer();
    });

    async function RecargarChofer() {

        //Consulto si existe chofer
        var michofer = JSON.parse(localStorage.getItem('michofer'))
        if (michofer) {

            //Ingreso los datos de sesión consultando a la BD
            var michofer2 = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
            localStorage.setItem('michofer', JSON.stringify(michofer2.data))

            // $("#micategoria").val(michofer.categoria.name)
            M.toast({html: 'Bienvenido! '+michofer2.data.nombres+' '+michofer2.data.apellidos})
            $("#miul").attr('hidden', false);
            setear_inputs();
            ImagenesExistentes();

            // $('#perfil_chofer').html("<img src='"+chofer()+"' alt='' width'' class='responsive-img circle'>")
            // $('#vehiculo_chofer').html("<img src='"+vehiculo_chofer()+"' alt='' width'' class='responsive-img'>")
            // carnet_chofer();
            // licencia_chofer();

            Categorias();
            Ciudades();
            Verification();
        } else {
            $('#modal1').modal('open');
        }

    }

    async function get_chofer() {
        var telefono = $("#telefono").val()
        if (telefono == '') {
                M.toast({html : 'Ingresa un telefono valido'})
        } else {
            var michofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+telefono)
            if (michofer.data) {
            var pin = Math.floor(1000 + Math.random() * 9000);
            var chofer = await axios("{{ setting('admin.url_api') }}chofer/pin/save/"+michofer.data.id+"/"+pin)
            //send chatbot
            var mensaje="Hola, tu pin para acceder a APPXI es: "+pin
            var wpp= await axios("https://chatbot.appxi.net/?type=text&phone="+telefono+"&message="+mensaje)
            M.toast({html : 'Revisa tu Whatsapp'})
            $("#telefono").attr('disabled', true);
            $("#btn_telefono").attr('disabled', true);
            $("#pin").attr('disabled', false);
            $("#btn_pin").attr('disabled', false);
            } else {
                location.href= '/chofer/crear'
            }
        }
    }

    async function get_pin() {
        var pin = $("#pin").val()
        var telefono = $("#telefono").val()
        var midata={'telefono':telefono, 'pin':pin}
        var michofer = await axios.post("{{ setting('admin.url_api') }}chofer/pin/get", midata)
        if (michofer.data) {
            localStorage.setItem('michofer', JSON.stringify(michofer.data))
            $('#modal1').modal('close')
            M.toast({html : 'Bienvenido'})
            $("#miul").attr('hidden', false);
            setear_inputs();
            ImagenesExistentes();
            // $('#perfil_chofer').html("<img src='"+chofer()+"' alt='' width'' class='responsive-img circle'>")
            // $('#vehiculo_chofer').html("<img src='"+vehiculo_chofer()+"' alt='' width'' class='responsive-img'>")
            // carnet_chofer();
            // licencia_chofer();
            Categorias();
            Ciudades();
            Verification();

        }else{
            M.toast({html : 'Credenciales Invalidas'})
        }
    }


    function salir() {
        localStorage.removeItem("michofer")
        M.toast({html: 'Vulve Pronto'})
        location.reload();
    }

    function chofer(){
        var michofer=JSON.parse(localStorage.getItem('michofer'));
        var perfil= "{{setting('admin.url_storage')}}"+michofer.perfil
        return perfil
    }

    function vehiculo_chofer(){
        var michofer=JSON.parse(localStorage.getItem('michofer'));

        //var table= await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
        var vehiculo= "{{setting('admin.url_storage')}}"+michofer.vehiculo
        return vehiculo
    }
    function carnet_chofer(){
        var michofer=JSON.parse(localStorage.getItem('michofer'));
        var carnet=JSON.parse(michofer.carnet)
        var midata=""
        for(let index=0;index <carnet.length;index++){
            var carnet_j= "{{setting('admin.url_storage')}}"+carnet[index]

            midata=midata+"<img src='"+carnet_j+"' alt='' width'' class='responsive-img'>"
        }
        $('#carnet_chofer').html(midata)

    }
    function licencia_chofer(){
        var michofer=JSON.parse(localStorage.getItem('michofer'));
        var licencia=JSON.parse(michofer.breve)
        var midata=""
        for(let index=0;index <licencia.length;index++){
            var licencia_j= "{{setting('admin.url_storage')}}"+licencia[index]

            midata=midata+"<img src='"+licencia_j+"' alt='' width'' class='responsive-img'>"
        }
        $('#licencia_chofer').html(midata)

    }

    async function setear_inputs() {
        var michofer=JSON.parse(localStorage.getItem('michofer'))
        $("#firstname").val(michofer.nombres)
        $("#lastname").val(michofer.apellidos)
        $("#email").val(michofer.email)
        $("#phone").val(michofer.telefono)
        // $("#micategoria").val(michofer.telefono)
        $("#micategoria").val(michofer.categoria.name)
        $('#verificacion').val(michofer.estado_verificacion)

    }

    async function Categorias() {
            $('#categoria_select').find('option').remove().end()
            var michofer=JSON.parse(localStorage.getItem('michofer'))

            var categoria_id=michofer.categoria_id
            var table_antigua=await axios("{{setting('admin.url_api')}}categoria/"+categoria_id)
            var table = await axios.get("{{ setting('admin.url_api') }}categorias")
            $('#categoria_select').append($('<option>', {
                value: categoria_id,
                text: ' '+table_antigua.data.name+' '
            }));
            for (let index = 0; index < table.data.length; index++) {
                if(table.data[index].id!=categoria_id){
                    $('#categoria_select').append($('<option>', {
                        value: table.data[index].id,
                        text: table.data[index].name
                    }));
                }
            }
        }

        async function Ciudades() {
            $('#ciudad_select').find('option').remove().end()
            var michofer=JSON.parse(localStorage.getItem('michofer'))


            var ciudad_id=michofer.ciudad_id
            var table_antigua=await axios("{{setting('admin.url_api')}}ciudad/"+ciudad_id)
            var table = await axios.get("{{setting('admin.url_api')}}ciudades");
            $('#ciudad_select').append($('<option>', {
                value: ciudad_id,
                text: ' '+table_antigua.data.name+' '
            }));
            for (let index = 0; index < table.data.length; index++) {
                if(table.data[index].id!=ciudad_id){
                        $('#ciudad_select').append($('<option>', {
                        value: table.data[index].id,
                        text: table.data[index].name
                    }));
                }
            }
        }
        async function Verification() {
            var michofer=JSON.parse(localStorage.getItem('michofer'))
            if(michofer.estado_verificacion){
                $('#verification').html("<div class='input-field col s3' > <br><br> <i class='material-icons'>verified_user</i> <br> <small>verificado</small> </div>")

            }
            else{
                $('#verification').html("<div class='input-field col s3' > <br><br> <i class='material-icons'>warning</i> <br> <small>sin verificar</small> </div>")
            }
        }

        async function ImagenesExistentes() {
            var michofer=JSON.parse(localStorage.getItem('michofer'));

            if (michofer.perfil!=null) {
                $('#perfil_chofer').html("<img src='"+chofer()+"' alt='' width'' class='responsive-img circle'>")
            }
            else{
                $('#perfil_chofer').html("<img src='{{url('storage').'/'.setting('chofer.perfil_default')}}' alt='' width'' class='responsive-img circle'>")
            }
            if (michofer.vehiculo!=null) {
                $('#vehiculo_chofer').html("<img src='"+vehiculo_chofer()+"' alt='' width'' class='responsive-img'>")
            }
            if(michofer.carnet!=null){
                carnet_chofer();
            }
            if (michofer.breve!=null) {
                licencia_chofer();
            }


        }
</script>

@endsection
