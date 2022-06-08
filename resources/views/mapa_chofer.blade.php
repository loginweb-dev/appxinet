@extends('master')

@section('css')

<style>
    .mimapa {
        width: 100%;
        height: 500px;
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

    <div class="container-fluid">
        <div class="row">
            {{-- <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="Perfil"> --}}
            {{-- <h5 class="center-align">Mapa del Viaje en Proceso</h5> --}}

            <div class="col s12">
                <center>
                    <p>Debe ir a recoger al cliente en el lugar indicado (A), para posteriormente llevarlo a su destino (B).</p>
                </center>
                <div id="map1" >
                    <div id="mimapa" class="mimapa"></div>
                    <div id="distancia" class="col s3"></div>
                    <div id="tiempo" class="col s3"></div>
                    <div id="recoger_cliente" class="col s6"></div>
                    <div class="col s12">
                        <br>
                        <hr>
                    </div>
                    <div id="text_cancelar" class="col s7"></div>
                    <div id="boton_cancelar" class="col s5"></div>
                </div>

                <div id="map2" hidden>
                    <div id="mimapa2" class="mimapa"></div>
                    <div class="col s6"></div>
                    <div id="llamada_usuario" class="col s6"></div>
                    <div id="terminar_viaje" class="col s6" ></div>
                    <div id="distancia2" class="col s3"></div>
                    <div id="tiempo2" class="col s3"></div>
                    <div class="col s12">
                        <hr>
                    </div>
                    <div id="text_cancelar2" class="col s6"></div>
                    <div id="boton_cancelar2" class="col s6"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        var map;
        var marker;
        $('document').ready(function () {
            cargar();
        });

        async function mostrar_recogiendo() {
            var michofer = JSON.parse(localStorage.getItem('michofer'))
            var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
            var consulta_viaje_disponible= await axios("{{ setting('admin.url_api')}}chofer_viaje_consulta/"+chofer.data.id)

            var distancia=''
            distancia=distancia+"<label for='text_distancia1'>Distancia Aproximada</label><input id='text_distancia1' type='text'  class='validate' readonly>"
            $("#distancia").html(distancia)

            var tiempo=''
            tiempo=tiempo+"<label for='text_tiempo1'>Tiempo Aproximado</label><input id='text_tiempo1' type='text'  class='validate' readonly>"
            $("#tiempo").html(tiempo)

            var recoger_cliente=''
            recoger_cliente=recoger_cliente+"<small>Dale clicnk cuando llegues a la ubicacion del pasajero<small><br>"
            recoger_cliente=recoger_cliente+"<br><button style='background-color: #0C2746;' class='btn waves-effect waves-light' type='submit' onclick='cliente_recogido("+consulta_viaje_disponible.data.id+")' name='action'>Recoger<i class='material-icons right'>send</i></button>"
            $('#recoger_cliente').html(recoger_cliente)

            var text_cancelar=''
            text_cancelar=text_cancelar+"<label for='text_detalle'>Detalle Cancelación</label><input placeholder='Ingrese referencia' id='text_detalle' type='text'  class='validate'></div><div class='col s5'>"
            $("#text_cancelar").html(text_cancelar)

            var boton_cancelar=''
            boton_cancelar=boton_cancelar+"<br><a class=' waves-effect waves-light btn red' onclick='cancelar_viaje("+consulta_viaje_disponible.data.id+")'>Cancelar</a> "
            $("#boton_cancelar").html(boton_cancelar)

        }

        async function mostrar_llevandoadestino() {
            var michofer = JSON.parse(localStorage.getItem('michofer'))
            var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
            var viaje_encurso= await axios("{{ setting('admin.url_api')}}viaje_chofer_encurso/"+chofer.data.id)
            console.log(viaje_encurso.data)
            var llamada_usuario=''
            llamada_usuario=llamada_usuario+"<br><button class='btn waves-effect waves-light' type='submit' onclick='llamar_wpp("+viaje_encurso.data.cliente.telefono+")' name='action'>Llamar Wpp<i class='material-icons right'>call</i></button>"
            llamada_usuario=llamada_usuario+"<br>Pasajero: "+viaje_encurso.data.cliente.nombres+" "+ viaje_encurso.data.cliente.apellidos
            $('#llamada_usuario').html(llamada_usuario)

            var distancia2=''
            distancia2=distancia2+"<label for='text_distancia2'>Distancia Aproximada</label><input id='text_distancia2' type='text'  class='validate' readonly>"
            $("#distancia2").html(distancia2)

            var tiempo2=''
            tiempo2=tiempo2+"<label for='text_tiempo2'>Tiempo Aproximado</label><input id='text_tiempo2' type='text'  class='validate' readonly>"
            $("#tiempo2").html(tiempo2)

            var terminar_viaje=''
            terminar_viaje= terminar_viaje+"<br><button style='background-color: #0C2746;' class='btn waves-effect waves-light' type='submit' onclick='conluir_viaje("+viaje_encurso.data.id+")' name='action'>Finalizar<i class='material-icons right'>send</i></button>"
            $("#terminar_viaje").html(terminar_viaje)

            var text_cancelar2=''
            text_cancelar2=text_cancelar2+"<label for='text_detalle'>Detalle Cancelación</label><input placeholder='Ingrese referencia' id='text_detalle' type='text'  class='validate'></div><div class='col s5'>"
            $("#text_cancelar2").html(text_cancelar2)

            var boton_cancelar2=''
            boton_cancelar2=boton_cancelar2+"<br><a class=' waves-effect waves-light btn red' onclick='cancelar_viaje("+viaje_encurso.data.id+")'>Cancelar</a> "
            $("#boton_cancelar2").html(boton_cancelar2)

        }

        async function cargar() {
            var michofer = JSON.parse(localStorage.getItem('michofer'))
            var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
            var viaje_encurso=  await axios("{{ setting('admin.url_api')}}viaje_chofer_encurso/"+chofer.data.id)
            var consulta_viaje_disponible= await axios("{{ setting('admin.url_api')}}chofer_viaje_consulta/"+chofer.data.id)
            if(consulta_viaje_disponible.data){
                mostrar_recogiendo();
                console.log('estoy aqui')
                var options = {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                };
                navigator.geolocation.getCurrentPosition(set_origen, error, options);
                mostrar_llevandoadestino();
                set_origen2(consulta_viaje_disponible.data.id);
            }
            else if(viaje_encurso.data){
                $("#map2").attr('hidden', false);
                $("#map1").attr('hidden', true);
                mostrar_llevandoadestino();
                set_origen2(viaje_encurso.data.id);
            }
            else{
                location.href='/viajes/monitor'
            }
        }

        async function llamar_wpp(telefono) {
            window.open('https://wa.me/591'+telefono,'_blank')
        }

        async function cliente_recogido(id) {
            var recogido= await axios("{{setting('admin.url_api')}}cliente_recogido/"+id)
            var viaje=await axios("{{setting('admin.url_api')}}viaje/"+id)
            var cliente=await axios("{{setting('admin.url_api')}}cliente_por_id/"+viaje.data.cliente_id)
            var mensaje_cliente="Su chofer ya llegó, porfavor salga para ir a su destino correspondiente."
            var wpp_mensaje_cliente=await axios("https://chatbot.appxi.net/?type=text&phone="+cliente.data.telefono+"&message="+mensaje_cliente)
            cargar();
        }


        async function conluir_viaje(id) {
            document.getElementById('terminar_viaje').style.visibility='hidden';
            var viaje_concluido=await axios("{{ setting('admin.url_api')}}concluir_viaje/"+id)
            if(viaje_concluido){
                var michofer = JSON.parse(localStorage.getItem('michofer'))
                var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
                var liberar_cliente= await axios("{{setting('admin.url_api')}}liberar_cliente/"+id)
                var estado= await axios("{{setting('admin.url_api')}}update_estado/chofer/"+chofer.data.id+"/"+1)

                var viaje=await axios("{{setting('admin.url_api')}}viaje/"+id)
                var cliente=await axios("{{setting('admin.url_api')}}cliente_por_id/"+viaje.data.cliente_id)
                var mensaje_cliente="Su viaje fue conlcuido con éxito, esperamos que el servicio haya sido de su agrado, puede puntuar la atención del chofer en la aplicación"
                var wpp_mensaje_cliente=await axios("https://chatbot.appxi.net/?type=text&phone="+cliente.data.telefono+"&message="+mensaje_cliente)

                var mensaje_chofer="Su viaje fue concluido con éxito, ya se encuentra disponible para realizar otros, recuerde que el cliente puede calificar la experiencia del viaje."
                var wpp_mensaje_chofer= await axios("https://chatbot.appxi.net/?type=text&phone="+michofer.telefono+"&message="+mensaje_chofer)

                socket.emit('final_viaje', 'finalizar')
                location.href='/viajes/monitor'


            }
        }

        async function cancelar_viaje(id) {

            var detalle=$('#text_detalle').val() ? $('#text_detalle').val():null

            if(detalle){
                var viaje_cancelado= await axios("{{setting('admin.url_api')}}cancelar_viaje/"+id+"/"+detalle)
                if(viaje_cancelado){
                    var michofer = JSON.parse(localStorage.getItem('michofer'))
                    var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)

                    var liberar_cliente= await axios("{{setting('admin.url_api')}}liberar_cliente/"+id)
                    var estado= await axios("{{setting('admin.url_api')}}update_estado/chofer/"+chofer.data.id+"/"+1)

                    var viaje=await axios("{{setting('admin.url_api')}}viaje/"+id)
                    var cliente=await axios("{{setting('admin.url_api')}}cliente_por_id/"+viaje.data.cliente_id)
                    var mensaje_cliente="Su viaje fue cancelado por el chofer por el siguiente motivo: "+detalle+" nos disculpamos por las molestias, puede solicitar otro viaje inmediatamente."
                    var wpp_mensaje_cliente=await axios("https://chatbot.appxi.net/?type=text&phone="+cliente.data.telefono+"&message="+mensaje_cliente)

                    var mensaje_chofer="Su viaje fue cancelado por usted por el siguiente motivo: "+detalle+" %0A Esperamos que se resuelvan sus inconvenientes, recordarle que se encuentra otra vez habilitado para realizar otro viaje."
                    var wpp_mensaje_chofer= await axios("https://chatbot.appxi.net/?type=text&phone="+michofer.telefono+"&message="+mensaje_chofer)

                    socket.emit('final_viaje', 'finalizar')
                    location.href='/viajes/monitor'

                }
            }
            else{
                M.toast({html:'Tiene que escribir el motivo de su cancelación'})
            }
        }



        function error(err) {
            alert(err.message+" "+err.code+" - Habilita tu Sensor GPS")
            console.warn('ERROR(' + err.code + '): ' + err.message)
            location.reload()
        }

        function error2(err) {
            console.warn('ERROR(' + err.code + '): ' + err.message)
            // location.reload()
        }

        function set_origen(pos) {
            var crd = pos.coords
            var radio = pos.accuracy
            var myLatLng = { lat: pos.coords.latitude, lng: pos.coords.longitude }
            map = new google.maps.Map(document.getElementById("mimapa"), {
                center: myLatLng,
                zoom: 15,
            });
            marker = new google.maps.Marker({
                animation: google.maps.Animation.DROP,
                position: myLatLng,
                map,
                icon: 'https://appxi.net//storage/iconpng.png'
            });

            var options = {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
            };
            navigator.geolocation.watchPosition(success, error2, options);

            socket.emit('traking', {lat: pos.coords.latitude, lng: pos.coords.longitude})
            ruta1(myLatLng, map)
        }

        function success(pos) {
            var crd = pos.coords;
            console.log(crd)
            socket.emit('traking', {lat: pos.coords.latitude, lng: pos.coords.longitude})
            var myLatLng = { lat: pos.coords.latitude, lng: pos.coords.longitude }
            // map = new google.maps.Map(document.getElementById("mimapa"), {
            //     center: myLatLng,
            //     zoom: 15,
            // });
            // marker = new google.maps.Marker({
            //     animation: google.maps.Animation.DROP,
            //     position: myLatLng,
            //     map,
            //     icon: 'https://appxi.net//storage/iconpng.png'

            // });
            marker.setPosition(new google.maps.LatLng( pos.coords.latitude, pos.coords.longitude ) )
            map.panTo( new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude ) )
        };


        async function set_origen2(data){
            var table= await axios("{{setting('admin.url_api')}}viaje/"+data)
            var origen= await axios("{{setting('admin.url_api')}}ubicacion/"+table.data.origen_location)
            var destino= await axios("{{setting('admin.url_api')}}ubicacion/"+table.data.destino_location)
            var myLatLng = { lat: parseFloat(origen.data.latitud), lng: parseFloat(origen.data.longitud) }
            var destinoLatLong= { lat: parseFloat(destino.data.latitud), lng:parseFloat(destino.data.longitud)}
            map = new google.maps.Map(document.getElementById("mimapa2"), {
                center: myLatLng,
                zoom: 15,
            });
            marker = new google.maps.Marker({
                animation: google.maps.Animation.DROP,
                position: myLatLng,
                map,
                icon: 'https://appxi.net//storage/iconpng.png'
            });

            var options = {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
            };
            navigator.geolocation.watchPosition(success, error2, options);

            socket.emit('traking', {lat: parseFloat(origen.data.latitud), lng: parseFloat(origen.data.longitud)})
            ruta2(myLatLng, destinoLatLong, map)
        }

        async function consulta_viaje_disponible(){
            var michofer = JSON.parse(localStorage.getItem('michofer'))
            var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
            var consulta_viaje_disponible= await axios("{{ setting('admin.url_api')}}chofer_viaje_consulta/"+chofer.data.id)
            if(consulta_viaje_disponible.data){
                return true;
            }
            else{
                return false;
            }
        }

        async function viaje_encurso() {
            var michofer = JSON.parse(localStorage.getItem('michofer'))
            var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
            var viaje_encurso=await  await axios("{{ setting('admin.url_api')}}viaje_chofer_encurso/"+chofer.data.id)
            return viaje_encurso;
        }

        async function ruta1(myLatLng,map) {
            var michofer = JSON.parse(localStorage.getItem('michofer'))
            var chofer = await axios("{{ setting('admin.url_api') }}chofer/by/"+michofer.telefono)
            var consulta_viaje_disponible= await axios("{{ setting('admin.url_api')}}chofer_viaje_consulta/"+chofer.data.id)
            var data=consulta_viaje_disponible.data.id
            var table= await axios("{{setting('admin.url_api')}}viaje/"+data)
            var destino= await axios("{{setting('admin.url_api')}}ubicacion/"+table.data.origen_location)
            var destinoLatLong= { lat: parseFloat(destino.data.latitud), lng:parseFloat(destino.data.longitud)}
            var viaje = {
                origin: myLatLng,
                destination: destinoLatLong,
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            };
            var directionsDisplay = new google.maps.DirectionsRenderer();
            directionsDisplay.suppressMarkers = true;
            var directionsService = new google.maps.DirectionsService();
            directionsDisplay.setMap(map);
            marker = new google.maps.Marker({
                animation: google.maps.Animation.DROP,
                position: destinoLatLong,
                map,
                icon: 'https://appxi.net//storage/iconpng.png'
            });
            directionsService.route(viaje, async function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response)
                    $("#text_distancia1").val(response.routes[0].legs[0].distance.text);
                    $("#text_tiempo1").val(response.routes[0].legs[0].duration.text);
                }
            });
        }

        async function ruta2(myLatLng, destinoLatLong,map) {
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
                    $("#text_distancia2").val(response.routes[0].legs[0].distance.text);
                    $("#text_tiempo2").val(response.routes[0].legs[0].duration.text);
                }
            });
        }

    </script>

@endsection
