@extends('master')


@section('css')
    <style>
    #map {
        width: 100%;
        height: 650px;
    }
    </style>
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="Perfil">
        <div class="col s12">
            {{-- <center>
                <h4>Mapa del Viaje</h4>
            </center> --}}
            <div id="map"></div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
    <script>
        var map;
        var marker;
        socket.on('traking', (obj) =>{
                console.log(obj)
                marker.setPosition(new google.maps.LatLng(parseFloat(obj.lat), parseFloat(obj.lng)))
                map.panTo(new google.maps.LatLng( parseFloat(obj.lat), parseFloat(obj.lng)))
            })
            socket.on('final_viaje', (obj) =>{
                location.reload()
                console.log("Entro al Socket")
            })
        $('document').ready(function () {
            get_viaje()
            set_origen()

            var miuser = JSON.parse(localStorage.getItem('miuser'))
            var myLatLng = { lat: parseFloat(-14.8350387349957), lng: parseFloat(-64.9041263226692) }
            map = new google.maps.Map(document.getElementById("map"), {
                center: myLatLng,
                zoom: 16,
            });
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: 'https://appxi.net//storage/iconpng.png'
            });

        });

        async function get_viaje() {
            var miviaje = JSON.parse(localStorage.getItem('viaje'))
            var viaje = await axios("{{ setting('admin.url_api') }}viaje/"+miviaje.id)
            console.log(viaje.data)
            if (viaje.data.status_id==4 ||viaje.data.status_id==5) {
                location.href='/welcome'
            }
        }

        function set_origen() {
            var miuser = JSON.parse(localStorage.getItem('miuser'))
            var myLatLng = { lat: parseFloat(miuser.ciudad.latitud), lng: parseFloat(miuser.ciudad.longitud) }
            var map = new google.maps.Map(document.getElementById("map"), {
                center: myLatLng,
                zoom: 13,
            });
        }

        function error(err) {
            alert(err.message+" - Habilita tu Sensor GPS")
            console.warn('ERROR(' + err.code + '): ' + err.message)
        };
    </script>

@endsection
