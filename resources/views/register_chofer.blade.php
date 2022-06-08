@extends('master')

@section('css')


@endsection
@section('content')

<div class="container-fluid">
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

    <form action="{{route('registro_chofer')}}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <img class="responsive-img" src="{{ url('storage').'/'.setting('site.banner_bienvenida') }}" alt="Perfil">
        <div class="row">
            <h5 class="center-align">Registro de Conductor(a)</h5>
            <label for="">Tipo de vehiculo que manejas ?</label>
            <table class="responsive-table">
                <tbody>
                    @php
                        $categorias = App\Categoria::all();
                    @endphp
                    @foreach ($categorias as $item)
                        <tr>
                            <td>
                                <label>
                                    <img src="{{ setting('admin.url_storage').'/'.$item->logo }}" alt="" class="responsive-img circle" width="80">
                                        <br>
                                    <input style="background-color: #0C2746;" name="categoria_id" type="radio" value="{{ $item->id }}" />
                                    <span>{{ $item->name }}</span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <label for="">Ciudad de trabajo ?</label>
            <table class="responsive-table">
                <tbody>
                    @php
                        $ciudades = App\Ciudade::all();
                    @endphp
                    @foreach ($ciudades as $item)
                        <tr>
                            <td>
                                <label>
                                    <img src="{{ setting('admin.url_storage').'/'.$item->logo }}" alt="" class="responsive-img circle" width="80">
                                        <br>
                                    <input style="background-color: #0C2746;" name="ciudad_id" type="radio" value="{{ $item->id }}" />
                                    <span>{{ $item->name }}</span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="input-field col s3">
                <label for="phone">Codigo</label>
                <input type="number" class="validate" id="codigo" name="codigo" placeholder="Número de Celular" value="+591" required readonly>
            </div>
            <div class="input-field col s9">
                <label for="phone">Teléfono</label>
                <input type="number" class="validate" id="phone" name="phone" placeholder="Número de Celular" value="{{ old('phone') }}" required>
            </div>
            <div class="input-field col s6">
                <label for="firstname">Nombres</label>
                <input type="text" class="validate" id="firstname" name="firstname" placeholder="Ingrese sus nombres" value="{{ old('firstname') }}" required>
            </div>
            <div class="input-field col s6">
                <label for="lastname">Apellidos</label>
                <input type="text" class="validate" id="lastname" name="lastname" placeholder="Ingrese sus apellidos" value="{{ old('lastname') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="file-field input-field">
                <div class="btn">
                    <input id="imgchofer" name="imgchofer" type="file" required>
                    <i class="material-icons">photo_library</i>
                </div>
                <div class="file-path-wrapper">
                <input class="file-path validate" name="imgchofer" type="text" placeholder="Foto del Conductor(a)" >
                </div>
            </div>

            <div class="file-field input-field">
                <div class="btn">
                    <input id="imgfotosdelvehiculo" name="imgfotosdelvehiculo" type="file" required>
                    <i class="material-icons">photo_size_select_actual</i>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path" name="imgfotosdelvehiculo" type="text" placeholder="Foto del Vehículo" >
                </div>
            </div>

            <div class="file-field input-field">
                <div class="btn">
                    <input id="imgcarnet" name="imgcarnet[]" type="file" multiple>
                    <i class="material-icons">photo_size_select_actual</i>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path" name="imgcarnet[]"  type="text" placeholder="Fotos de la Cédula de Identidad" >
                </div>
            </div>

            <div class="file-field input-field">
                <div class="btn">
                    <input id="imglicencia" name="imglicencia[]"  type="file" multiple>
                    <i class="material-icons">photo_size_select_actual</i>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path" name="imglicencia[]" type="text" placeholder="Fotos de la Licencia de Conducir" >
                </div>
            </div>
        </div>

        <center>
            <button  style="background-color: #0C2746;" class="btn waves-effect waves-light" type="submit" name="action">Enviar a Verificacion
                <i class="material-icons right">save</i>
            </button>
        </center>
    </form>
</div>

@endsection

@section('javascript')
    <script>

        $('document').ready(function () {
            $('select').formSelect();
            Categorias();
            Ciudades();
        });


        async function Categorias() {
            $('#categoria_select').find('option').remove().end();
            var table = await axios.get("{{ setting('admin.url_api') }}categorias");
            $('#categoria_select').append($('<option>', {
                value: null,
                text: 'Elige una Categoria'
            }));
            for (let index = 0; index < table.data.length; index++) {
                $('#categoria_select').append($('<option>', {
                    value: table.data[index].id,
                    text: table.data[index].name
                }));
            }
        }

        async function Ciudades() {
            $('#ciudad_select').find('option').remove().end();
            var table = await axios.get("{{setting('admin.url_api')}}ciudades");
            $('#ciudad_select').append($('<option>', {
                value: null,
                text: 'Elige una Ciudad'
            }));
            for (let index = 0; index < table.data.length; index++) {
                $('#ciudad_select').append($('<option>', {
                    value: table.data[index].id,
                    text: table.data[index].name
                }));
            }
        }

    </script>

@endsection
