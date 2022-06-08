@extends('master')


@section('content')
<div id="modal_carnet" class="modal">
    <div class="modal-content">
        <h5>Seleccona el Anverso de tu Carnet</h5>
        <div id="anverso_ejemplo"></div>
        <h5>Seleccona el Reverso de tu Carnet</h5>
        <div id="reverso_ejemplo"></div>
    </div>
</div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function(){
            $('.modal').modal();
            $('#modal_carnet').modal('open');
            $('#anverso_ejemplo').html(" <img src='https://www.google.com/url?sa=i&url=https%3A%2F%2Fes.123rf.com%2Fphoto_95108503_dise%25C3%25B1o-de-ilustraci%25C3%25B3n-de-vector-de-icono-de-tarjeta-de-documento-de-identificaci%25C3%25B3n.html&psig=AOvVaw2DMEX8Ph5Wv0zRRBCZM7t0&ust=1652191831904000&source=images&cd=vfe&ved=0CAwQjRxqFwoTCLi35rTM0vcCFQAAAAAdAAAAABAK' alt='' class='responsive-img'>");
            $('#reverso_ejemplo').html(" <img src='https://www.google.com/url?sa=i&url=https%3A%2F%2Fes.123rf.com%2Fphoto_95108503_dise%25C3%25B1o-de-ilustraci%25C3%25B3n-de-vector-de-icono-de-tarjeta-de-documento-de-identificaci%25C3%25B3n.html&psig=AOvVaw2DMEX8Ph5Wv0zRRBCZM7t0&ust=1652191831904000&source=images&cd=vfe&ved=0CAwQjRxqFwoTCLi35rTM0vcCFQAAAAAdAAAAABAK' alt='' class='responsive-img'>");
        });
    </script>
@endsection
