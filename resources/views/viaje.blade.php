@extends('master')


@section('content')
<div class="container-fluid">
    <div class="row">
        <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="Perfil">
        <div class="col s12">
            <center>
                <h4>Gracias por tu preferencia</h4>
                <p>Hola, gracias por tu preferencia, APPXI esta buscando un taxi ideal para ti, espera una notificacion por whatsapp cuando este listo tu viaje</p>
            </center>
            <table>
                <tbody>
                    {{-- <tr>
                        <td>Viaje #</td>
                        <td><div id="miid"></div></td>
                    </tr> --}}
                    <tr>
                        <td>Fecha</td>
                        <td><div id="fecha"></div></td>
                        <td>Estado</td>
                        <td><div id="estado"></div></td>
                    </tr>
                    {{-- <tr>
                        <td>Cliente</td>
                        <td><div id="cliente"></div></td>
                    </tr> --}}
                    {{-- <tr>
                        <td>Estado</td>
                        <td><div id="estado"></div></td>
                    </tr> --}}
                    <tr>
                        <td>Precio Ofertado</td>
                        <td><div id="p_ofertado"></div></td>
                        <td>Distancia</td>
                        <td><div id="distancia"></div></td>
                    </tr>
                    {{-- <tr>
                        <td>Distancia</td>
                        <td><div id="distancia"></div></td>
                    </tr> --}}
                    <tr>
                        <td>Tiempo</td>
                        <td><div id="tiempo"></div></td>
                        <td>Categoría</td>
                        <td><div id="categoria"></div></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col s12">
            <br>
            <center>
            <a href="/historial/cliente" onclick="save()"style="background-color: #0C2746;" class="waves-effect waves-light btn pulse" id="btn_save"><i class="material-icons">history</i> Mi Historial</a>
            </center>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script>
        $('document').ready(function () {
            var viaje = JSON.parse(localStorage.getItem('viaje'))
            var mifecha = new Date(viaje.created_at);
            // $("#miid").html("<span class='badge'>"+mifecha+"</span>")
            $("#fecha").html("<span class=''>"+mifecha+"</span>")
            $("#cliente").html("<p>"+viaje.cliente.nombres+' '+viaje.cliente.apellidos+"</p>")
            $("#estado").html("<span class='new badge blue'>"+viaje.estado.name+"</span>")
            $("#p_ofertado").html("<span class='new badge blue'>"+viaje.precio_inicial+" Bs.</span>")
            $("#distancia").html("<span class='new badge blue'>"+viaje.dt+"</span>")
            $("#tiempo").html("<span class='new badge blue'>"+viaje.tt+"</span>")
            $("#categoria").html("<span class='new badge blue'>"+viaje.categoria.name+"</span>")

            socket.on('controferta', (obj) =>{
                location.href = "/historial/cliente"
            })
        });

        function save() {
            document.getElementById('btn_save').style.visibility='hidden';
        }
    </script>

@endsection
