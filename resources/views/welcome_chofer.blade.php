@extends('master')


@section('content')

<div class="container-fluid">
    <br>
    <div class="row">
        <div class="col s12">
            <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="APPXI" ><br>

            <h5 class="center-align"> Información</h5>

            <h6>
                Verificación
            </h6>
            <p>
                Tus datos y tus documentos están en verificación, el proceso tarda proximadamente 24 hrs,
                te notificaremos cualquier novedad al respecto.
            </p>
            <br>
            <h6>
                Créditos
            </h6>
            <p>
                El uso de la aplicación es totalmente gratuita, tanto para el chofer como para el cliente,
                podrás brindar tu servicio de chofer siempre y cuando estés verificado y tengas los créditos suficientes para
                realizar el servicio, dichos créditos tienen un costo.

                Pero tranquilo que solo por registrarte te regalaremos 500 créditos que te alcanzarán aproximadamente
                para X viajes de servicio.
            </p>
            <p>
                Hasta que seas verificado, si gustas puedes ir recargando créditos, ingresando al menú: "Cuenta".
            </p>
        </div>
        <div class="col s12">
            <center>
                <h4>Bienvenid@ a Appxi</h4>
                <p>para empezar a solicitar viajes, verificaremos tu cuenta. te enviamos un mensaje a tu whatsapp</p>
                <input placeholder="Ingresa tu telefono - 8 digitos" id="telefono" type="number" class="validate" readonly value="{{ $chofer->telefono }}">
                <input placeholder="PIN - 4 digitos" id="pin" type="number" class="validate">
                <button id="btn_verificar" style="background-color: #0C2746;" class="btn waves-effect waves-light" type="submit" name="action" onclick="get_pin()">Verificar
                    <i class="material-icons right">key</i>
                </button>
                <p>Revisa tu Whatsapp, luego de darle click en verificar.</p>
            </center>
        </div>

    </div>

</div>
<div id="modal1" class="modal">
    <div class="modal-content">
        <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="APPXI" ><br>
        <h5 class="center-align">¡Bienvenido(a) a APPXI!</h5>
        <p class="center-align">Nos alegra que hayas decidido unirte a la mejor comunidad de
            "Servicio de Taxis", cada vez somos mas los integrantes y queremos que te sientas
            lo más cómodo(a) posible con nuestro servicio, nos carazterizamos por darle  una verdadera
            seguridad tanto al cliente cómo al chofer, al mismo tiempo una experiencia de excelente calidad.
        </p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Aceptar</a>
    </div>
</div>

@endsection

@section('javascript')
    {{-- <script>
        socket.emit('nuevo_chofer', "Nuevo chofer registrado: {{ $chofer->id }}")
    </script> --}}
   <script>
        $('document').ready(function () {
            $('.modal').modal();
            $('#modal1').modal('open');
            // var michofer=JSON.parse(localStorage.getItem('michofer'));
            // var mensaje="*¡Bienvenido(a) a APPXI!* %0A Nos alegra que hayas decidido unirte a la mejor comunidad de 'Servicio de Taxis', cada vez somos mas los integrantes y queremos que te sientas lo más cómodo(a) posible con nuestro servicio, nos carazterizamos por darle una verdadera seguridad tanto al cliente cómo al chofer, al mismo tiempo una experiencia de excelente calidad"
            // var wpp=  axios("https://chatbot.appxi.net/?type=text&phone="+michofer.telefono+"&message="+mensaje)
            verificar()
        });


        async function verificar() {
            var pin = Math.floor(1000 + Math.random() * 9000);
            var telefono = $("#telefono").val()
            var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+telefono)
            var mipin = await axios("{{ setting('admin.url_api') }}chofer/pin/save/"+chofer.data.id+"/"+pin)
            var mensaje="Hola, tu pin para verificar tu cuenta en APPXI es (CHOFER): "+pin
            var wpp= await axios("https://chatbot.appxi.net/?type=text&phone="+chofer.data.telefono+"&message="+mensaje)
            var miscoket = await socket.emit('nuevo_chofer', mensaje)
            M.toast({html : 'Revisa tu Whatsapp'})
        }
        async function get_pin() {
            var pin = $("#pin").val()
            var telefono = $("#telefono").val()
            var midata={'telefono':telefono, 'pin':pin}
            var michofer = await axios.post("{{ setting('admin.url_api') }}chofer/pin/get", midata)
            if (michofer.data) {
                var clienteupdate = await axios("{{ setting('admin.url_api') }}chofer/pin/update/"+michofer.data.id)
                localStorage.setItem('michofer', JSON.stringify(clienteupdate.data))
                M.toast({html : 'Bienvenido a APPXI'})
                location.href='/viajes/monitor'
            }else{
                M.toast({html : 'Credenciales Invalidas'})
            }
        }
   </script>
@endsection
