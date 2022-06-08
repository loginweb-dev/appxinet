@extends('master')

@section('css')
@endsection

@section('content')
<div class="container-fluid" id="miul" hidden>
    <div class="row">
        <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="Perfil">
        <div class="col s12">
            {{-- <center>
                <h4>Mis Viajes</h4>
            </center> --}}
            <ul class="collection">
                <div id="milist"></div>
            </ul>

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

<div id="modal2" class="modal bottom-sheet">
    <div class="modal-content">
        <h4>Negociaciones</h4>
        <table class="table table-responsive" id="milist_detalle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Chofer</th>
                    <th>Oferta</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@section('javascript')
<script>
    socket.on('contraoferta', (obj)=>{
        location.reload()
    })
    $(document).ready(function(){
        $('.modal').modal();
        var miuser = JSON.parse(localStorage.getItem('miuser'))
        if (miuser) {
            $("#miul").attr('hidden', false)
            get_list()
            socket.emit('traking', {lat: -14.8350387349957, lng: -64.9041263226692})
        } else {
            $('#modal1').modal('open')
        }
    });

    async function get_list() {
        // var miestado = await axios("{{ setting('admin.url_api') }}cliente/viajes/"+miuser.id)
        var miuser = JSON.parse(localStorage.getItem('miuser'))
        var milist = await axios("{{ setting('admin.url_api') }}cliente/viajes/"+miuser.id)
        var miul = ''
        for (let index = 0; index < milist.data.length; index++) {
            var img = milist.data[index].cliente.perfil ? milist.data[index].cliente.perfil : "@php echo setting('cliente.perfil_default')  @endphp"
            miul = miul + "<li class='collection-item avatar'><img src='{{ setting('admin.url_storage') }}"+img+"' class='circle'><p>Viaje #"+milist.data[index].id+"<span class='badge' data-badge-caption='"+milist.data[index].estado.name+"' style='background-color: #0C2746;'></span></p><span class='title'>"+milist.data[index].cliente.nombres+' '+milist.data[index].cliente.apellidos+"</span><p>Fecha: "+milist.data[index].published+"</p><p>Precio Ofertado: "+milist.data[index].precio_inicial+" Bs.</p><p>Distancia: "+milist.data[index].dt+"</p><p>Tiempo: "+milist.data[index].tt+"</p><p>Origen Detalle: "+milist.data[index].origen.descripcion+"</p><p>Destino Detalle: "+milist.data[index].destino.descripcion+"</p><p>Vehiculo: "+milist.data[index].categoria.name+"</p></li>"

            if (milist.data[index].status_id == 2) {
                miul = miul + "<li class='collection-item'><span>Ofertas del viaje #"+milist.data[index].id+"</span><a class='secondary-content' onclick='get_negociaciones("+milist.data[index].id+")'><i class='material-icons'>list</i></a></li>"
            }
            // miul = miul + "<li class='collection-item avatar'><span>Ver mapa: </span><a class='secondary-content' onclick='get_mapa("+milist.data[index].id+")'><i class='material-icons'>map</i></a></li>"
        }
        $("#milist").html(miul)
    }

    async function get_mapa(viaje_id) {
        location.href = "/mapa/cliente/"+viaje_id
    }

    async function get_negociaciones(id) {
        var nego = await axios("{{ setting('admin.url_api') }}cliente/viaje/negociaciones/"+id)
        console.log(nego.data)
        $("#milist_detalle tbody tr").remove();
        for (let index = 0; index < nego.data.length; index++) {
            $("#milist_detalle").append("<tr><td>"+nego.data[index].id+"</td><td>"+nego.data[index].published+"</td><td>"+nego.data[index].chofer.nombres+" "+nego.data[index].chofer.apellidos+"</td><td>"+nego.data[index].precio_contraofertado+" Bs.</td><td><a onclick='set_viaje("+nego.data[index].viaje_id+","+nego.data[index].id+", "+nego.data[index].chofer_id+", "+nego.data[index].precio_contraofertado+")' style='background-color: #0C2746;' class='waves-effect waves-light btn-sm btn'><i class='material-icons'>done</i></a></td><td><a onclick='delete_nego("+nego.data[index].id+")' style='background-color: #F82F47;' class='waves-effect waves-light btn-sm btn'><i class='material-icons'>close</i></a></td</tr>")
        }
        $('#modal2').modal('open')
    }


    async function delete_nego(id) {
        table= await axios("{{setting('admin.url_api')}}delete_nego/"+id)
        location.reload()
    }

    async function set_viaje(viaje_id, nego_id, chofer_id, precio_final) {

        var table= await axios("{{setting('admin.url_api')}}chofer_por_id/"+chofer_id)
        console.log(table.data)
        if(table.data.estado){
            var midata = {
                viaje_id: viaje_id,
                nego_id: nego_id,
                chofer_id: chofer_id,
                precio_final: precio_final
            }
            var viaje = await axios("{{ setting('admin.url_api') }}viaje/aprobado/"+JSON.stringify(midata))
            var chofer = await axios("{{ setting('admin.url_api') }}chofer/id/"+chofer_id)
            var cliente = await axios("{{ setting('admin.url_api') }}cliente_por_id/"+viaje.data.cliente_id)

            //notificacion al chofer
            var mensaje=chofer.data.nombres+" "+chofer.data.apellidos+", el viaje ha sido solicitado y aprobado por el cliente: "+cliente.data.nombres+" "+cliente.data.apellidos+"%0A Ingresa al siguiente link para mas informacion: "
            var wpp= await axios("https://chatbot.appxi.net/?type=text&phone="+chofer.data.telefono+"&message="+mensaje)
            var mensaje="https://appxi.net/viajes/monitor"
            var wpp= await axios("https://chatbot.appxi.net/?type=text&phone="+chofer.data.telefono+"&message="+mensaje)
            //socket
            socket.emit('viaje_aprobado', mensaje)
            //console.log(mensaje)
            location.href = '/mapa/cliente'
        }
        else{
            M.toast({html:'El chofer ya se encuentra ocupado con otro viaje'})
        }
    }

    async function get_cliente() {
            var telefono = $("#telefono").val()
            if (telefono == '') {
                M.toast({html : 'Ingresa un telefono valido'})
            } else {
                var midata={
                    'telefono':telefono
                }
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
            var midata={'telefono':telefono, 'pin':pin}
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
