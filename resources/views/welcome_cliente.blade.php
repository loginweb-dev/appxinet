@extends('master')


@section('content')
    <div class="container-fluid">
        <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="Perfil">
        <div class="row">
            <div class="col s12">
                <center>
                    <h4>Bienvenid@ a Appxi</h4>
                    <p>para empezar a solicitar viajes, verificaremos tu cuenta. te enviamos un mensaje a tu whatsapp</p>
                    <input placeholder="Ingresa tu telefono - 8 digitos" id="telefono" type="number" class="validate" readonly value="{{ $cliente->telefono }}">
                    <input placeholder="PIN - 4 digitos" id="pin" type="number" class="validate">
                    <button id="btn_verificar" style="background-color: #0C2746;" class="btn waves-effect waves-light" type="submit" name="action" onclick="get_pin()">Verificar
                        <i class="material-icons right">key</i>
                    </button>
                    <p>Revisa tu Whatsapp, luego de darle click en verificar.</p>
                </center>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $('document').ready(function () {
            verificar()
        });
        async function verificar() {
            var pin = Math.floor(1000 + Math.random() * 9000);
            var telefono = $("#telefono").val()
            var midata={'telefono':telefono}
            var cliente0 = await axios.post("{{ setting('admin.url_api') }}cliente/by", midata)
            var mipin = await axios("{{ setting('admin.url_api') }}pin/save/"+cliente0.data.id+"/"+pin)
            var mensaje="Hola, tu pin para verificar tu cuenta en APPXI es: "+pin
            var wpp= await axios("https://chatbot.appxi.net/?type=text&phone="+cliente0.data.telefono+"&message="+mensaje)
            var miscoket = await socket.emit('nuevo_cliente', mensaje)
            M.toast({html : 'Revisa tu Whatsapp'})
        }
        async function get_pin() {
            var pin = $("#pin").val()
            var telefono = $("#telefono").val()
            var midata={'telefono':telefono, 'pin':pin}
            var miuser = await axios.post("{{ setting('admin.url_api') }}pin/get", midata)
            if (miuser.data) {
                var clienteupdate = await axios("{{ setting('admin.url_api') }}pin/update/"+miuser.data.id)
                localStorage.setItem('miuser', JSON.stringify(clienteupdate.data))
                M.toast({html : 'Bienvenido a APPXI'})
                location.href='/viaje/crear'
            }else{
                M.toast({html : 'Credenciales Invalidas'})
                $("#pin").val('')
            }
        }
    </script>
@endsection
