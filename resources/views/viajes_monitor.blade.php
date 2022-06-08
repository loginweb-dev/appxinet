@extends('master')

@section('css')

    <style>
    .mimapa {
        width: 100%;
        height: 400px;
    }
    .oferta {
        width: 100%;
        height: 350px;
    }
    .label{
        text-align: right;
        align-content: right;
        align-items: right;
    }
    </style>

@endsection

@section('content')
    <div class="container-fluid" id="miul" hidden>
      <div class="row">
        {{-- <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="Perfil"> --}}
          <div id="chofer_verificado" hidden>
            <div class="col s12" >
              <center>
                <h5>
                        <div class="switch">
                            <label>
                              Ocupado
                              <input type="checkbox" id="estado_chofer">
                              <span class="lever"></span>
                              Libre
                            </label>
                          </div>
                   </div>
                </h5>
              </center>
              <ul class="collection">
                <div id="milist"></div>
              </ul>
            </div>

          </div>
          <div id="chofer_sin_verificar" class="col s12" hidden>
                <h6 class="center-align">CONDUCTOR SIN VERIFICACIÓN DE DOCUMENTOS </h6><br>
                <p>
                    Porfavor diríjase a su cuenta e ingrese todos los datos que se le piden para ser verificado,
                    una vez comparemos todos los datos y todo esté en órden, le llegará una notificación por WhatsApp
                    de confirmación, o caso contrario de que haya algún error.
                </p>
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
                    <a id="btn_telefono" style='background-color: #0C2746;' onclick="get_chofer()" class="waves-effect waves-light btn"><i class="material-icons">search</i></a>
                </div>
            </div>
            <div class="row">
                <div class="col s9">
                    <input placeholder="PIN - 4 digitos" id="pin" type="number" class="validate" disabled>
                </div>
                <div class="col s3">
                    <a id="btn_pin" style='background-color: #0C2746;' onclick="get_pin()" class="waves-effect waves-light btn" disabled><i class="material-icons">key</i></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<script>
    socket.on('nuevo_viaje', (obj) => {
            location.reload()
        })
        socket.on('viaje_aprobado', (obj) => {
            location.reload()
        })
    $('document').ready(function () {
        $('.tooltipped').tooltip();
        $('.modal').modal();
        var michofer = JSON.parse(localStorage.getItem('michofer'))
        if (michofer) {
            $("#miul").attr('hidden', false);
            Verificar();
            EstadoChofer();
            get_list();
        } else {
            $('#modal1').modal('open');
        }


    });

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
            Verificar();
            EstadoChofer();
            get_list();

        }else{
            M.toast({html : 'Credenciales Invalidas'})
        }
    }
    async function get_list() {
        var michofer = JSON.parse(localStorage.getItem('michofer'))
        var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
        var consulta_viaje_disponible= await axios("{{ setting('admin.url_api')}}chofer_viaje_consulta/"+chofer.data.id)
        var consulta_viaje_proceso= await axios("{{ setting('admin.url_api')}}viaje_chofer_encurso/"+chofer.data.id)
        if(consulta_viaje_disponible.data || consulta_viaje_proceso.data){
            // M.toast({html:'No puede ver viajes disponibles hasta que acabe el que tiene en marcha'})
            alert('No puede ver viajes disponibles hasta que acabe el que tiene en marcha')
            location.href='/mapa_chofer'
        }
        else{
            var midataviaje={
                'ciudad_id':chofer.data.ciudad.id,
                'categoria_id':chofer.data.categoria.id
            }
            var milist= await axios.post("{{setting('admin.url_api')}}viajes_chofer", midataviaje)
            // console.log(milist.data)
            var miul = ''
            //console.log(milist.data.length )
            if (milist.data.length > 0) {
                for (let index = 0; index < milist.data.length; index++) {
                    var img = milist.data[index].cliente.perfil ? milist.data[index].cliente.perfil : "@php echo setting('cliente.perfil_default')  @endphp"
                    miul = miul + "<li class='collection-item avatar'><p>Viaje #"+milist.data[index].id+"</p><img src='{{ setting('admin.url_storage') }}"+img+"' class='circle'><span class='title'>"+milist.data[index].cliente.nombres+' '+milist.data[index].cliente.apellidos+"</span><p>Fecha: "+milist.data[index].published+"</p><p>Precio Ofertado: "+milist.data[index].precio_inicial+" Bs.</p><p>Distancia: "+milist.data[index].dt+"</p><p>Tiempo: "+milist.data[index].tt+"</p><p>Origen: "+milist.data[index].origen.descripcion+"</p><p>Destino: "+milist.data[index].destino.descripcion+"</p><a class='secondary-content tooltipped' data-position='bottom' data-tooltip='Aceptar Viaje' onclick='aceptar_viaje("+milist.data[index].id+")'><i class='material-icons'>send</i></a><p class=''>Estado: "+milist.data[index].estado.name+"</p></li>"

                    miul = miul + "<li class='collection-item'><span> Mapa del Viaje #"+milist.data[index].id+"</span><a class='secondary-content' onclick='detalles_viaje("+milist.data[index].id+")'><i class='material-icons'>expand_more</i></a><a class='secondary-content' onclick='cerrar_mapa("+milist.data[index].id+")'><i class='material-icons'>expand_less</i></a><div id='mioferta_"+milist.data[index].id+"' class='oferta' hidden><div id='mimapa_"+milist.data[index].id+"' class='mimapa'></div><div class='col s9'><label for='oferta'>Contra Oferta</label><input id='oferta_"+milist.data[index].id+"' value='0' type='number'  class='validate'></div><div class='col s3'><label for=''>Enviar</label><a onclick='aceptar_viaje("+milist.data[index].id+")' style='background-color: #0C2746;' class='waves-effect waves-light btn'><i class='material-icons'>send</i></a></div></div><div><input id='estado_ofertado_viaje_"+milist.data[index].id+"' value='0' type='number'  class='validate' hidden></div></li>"
                }
                $("#milist").html(miul)
            } else {
                //console.log('vacio')
                miul = miul + "<center><li class='collection-item'> Espera en esta vantana, te llegaran los viajes.</li>"
                miul = miul + "<li class='collection-item'><i class='large material-icons'>add_alert</i></center></li>"

                $("#milist").html(miul)
            }
        }
    }

    async function aceptar_viaje(data) {
        var michofer=JSON.parse(localStorage.getItem('michofer'));
        var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
        var viaje= await axios("{{ setting('admin.url_api') }}viaje/"+data)
        var cliente= await axios("{{setting('admin.url_api')}}cliente_por_id/"+viaje.data.cliente_id)
        if(viaje.data.status_id==2){
            if((chofer.data.creditos* parseInt("{{setting('creditos.credito_km')}}"))>=parseInt(viaje.data.distancia)){
                if($('#estado_ofertado_viaje_'+data).val()==0){
                    if($('#oferta_'+data).val()==0){
                        var nuevo_precio=viaje.data.precio_inicial
                    }
                    else{
                        var nuevo_precio=$('#oferta_'+data).val();
                    }
                    var cliente_id= cliente.data.id
                    var chofer_id=michofer.id
                    var viaje_id= viaje.data.id
                    var precio_contraofertado=nuevo_precio
                    var status=0
                    var midata = JSON.stringify({'cliente_id':cliente_id, 'chofer_id':chofer_id, 'viaje_id':viaje_id, 'precio_contraofertado':precio_contraofertado, 'status':status})
                    var negociacion= await axios("{{setting('admin.url_api')}}save_negociaciones/"+midata)
                    cerrar_mapa(data);
                    if(negociacion){
                        M.toast({html : 'Viaje Aceptado Correctamente'})
                        var michofer=JSON.parse(localStorage.getItem('michofer'));
                        var mensaje="Hola, el Chofer: "+michofer.nombres+" "+michofer.apellidos+" aceptó el viaje, con el precio de: "+ nuevo_precio+" Bs. %0A Usted decidirá si está de acuerdo para cerrar la negociación."
                        var mensaje2=" %0AEntre al siguiente link para ver sus viajes en negociación: %0A https://appxi.net/historial/cliente"
                        var wpp=  axios("https://chatbot.appxi.net/?type=text&phone="+cliente.data.telefono+"&message="+mensaje+mensaje2)
                        socket.emit('controferta', 'Contra Oferta')
                    }
                    $('#estado_ofertado_viaje_'+data).val(1)
                }
                else{
                    M.toast({html : 'Ya postuló para este viaje, porfavor aguarde a que le respondan la solicitud'})
                    //console.log($('#estado_ofertado_viaje_'+data).val())
                }
            }
            else{
                M.toast({html : 'Sus Créditos no son suficientes para realizar ese viaje'})
                M.toast({html: 'Recuerde que 1 crédito equivale a 4km de recorrido en viajes'})
            }
        }
        else{
            location.reload()
            M.toast({html:'El cliente ya negoció con otro chofer, intente con otro viaje'})
        }
    }

    async function detalles_viaje(data) {
        $("#mioferta_"+data).attr('hidden', false);
        var table= await axios("{{setting('admin.url_api')}}viaje/"+data)
        var origen= await axios("{{setting('admin.url_api')}}ubicacion/"+table.data.origen_location)
        var destino= await axios("{{setting('admin.url_api')}}ubicacion/"+table.data.destino_location)
        var myLatLng = { lat: parseFloat(origen.data.latitud), lng: parseFloat(origen.data.longitud) }
        var destinoLatLong= { lat: parseFloat(destino.data.latitud), lng:parseFloat(destino.data.longitud)}
        var map = new google.maps.Map(document.getElementById("mimapa_"+data), {
                center: destinoLatLong,
                zoom: 13,
        });
        var viaje = {
            origin: myLatLng,
            destination: destinoLatLong,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        var directionsDisplay = new google.maps.DirectionsRenderer();
        directionsDisplay.suppressMarkers = true;
        var directionsService = new google.maps.DirectionsService();
        directionsDisplay.setMap(map);

        directionsService.route(viaje, async function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response)
            }
        });
    }

    async function cerrar_mapa(id) {
        $("#mioferta_"+id).attr('hidden', true);
    }

    async function Verificar() {
        var michofer = JSON.parse(localStorage.getItem('michofer'))
        var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
        if(chofer.data.estado_verificacion){
            $("#chofer_verificado").attr('hidden', false);
        }
        else{
            $("#chofer_sin_verificar").attr('hidden', false);
        }
    }

    async function EstadoChofer() {
        var michofer = JSON.parse(localStorage.getItem('michofer'))
        var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
        if(chofer.data.estado){
            $("#estado_chofer").attr('checked', true);
        }
        else{
            $("#estado_chofer").attr('checked', false);
        }
    }
    async function CambiarEstado() {
        var michofer = JSON.parse(localStorage.getItem('michofer'))
        var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
        if(chofer.data.estado){
            var estado_actual=0
            var cambio= await axios("{{setting('admin.url_api')}}update_estado/chofer/"+chofer.data.id+"/"+estado_actual)
            M.toast({html:  'Cambió estado a Ocupado'});
        }
        else{
            var consulta_viaje_disponible= await axios("{{ setting('admin.url_api')}}chofer_viaje_consulta/"+chofer.data.id)
            //console.log(consulta_viaje_disponible)
            if(consulta_viaje_disponible.data){
                location.href="/mapa_chofer"
            }
            else{

                var estado_actual=1
                var cambio= await axios("{{setting('admin.url_api')}}update_estado/chofer/"+chofer.data.id+"/"+estado_actual)
                M.toast({html:  'Cambió estado a Libre'});
            }

        }
    }
    $('#estado_chofer').on('change', function() {
        CambiarEstado();
    });
</script>
@endsection
