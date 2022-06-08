@extends('master')

@section('css')
<style>
    .waves-effect.waves-brown .waves-ripple {/
    background-color: rgba(121, 85, 72, 0.65);
  }
</style>
@endsection

@section('content')


  <div class="slider fullscreen">
    <ul class="slides">
      {{-- <li>
        <img src="https://appxi.loginweb.dev//storage/splash/splash1.jpg"> <!-- random image -->
        <div class="caption center-align">
          <h3>Viaja Segur@</h3>
          <h5 class="light grey-text text-lighten-3">Viaja con APPXI</h5>
          <img src="https://appxi.loginweb.dev//storage/settings/April2022/2nmFNrEQQurLXzrDXA1d.png" style="margin: 10px; width: 120px;" />
        </div>
      </li> --}}
      <li>
        <img src="https://appxi.net//storage/splash/splash3.jpg"> <!-- random image -->
        <div class="caption center-align">
          <h3>Viaja al mejor Precio</h3>
          <h5 class="light grey-text text-lighten-3">Viaja con APPXI</h5>
          <img src="https://appxi.net//storage/settings/April2022/2nmFNrEQQurLXzrDXA1d.png" style="margin: 10px; width: 120px;" />
        </div>
      </li>
      {{-- <li>
        <img src="https://appxi.loginweb.dev//storage/splash/splash2.png"> <!-- random image -->
        <div class="caption center-align" style="background-color:black;opacity:0.5;">
          <h3>Left Aligned Caption</h3>
          <h5 class="light grey-text text-lighten-3">Here's our small slogan.</h5>
        </div>
      </li> --}}

    </ul>
  </div>



@endsection

@section('javascript')
<script>
      $(document).ready(function(){
        $('.slider').slider();

        socket.on('limpiar_localstorage', (obj) =>{
            localStorage.removeItem('micategoria');
            localStorage.removeItem('origen');
            localStorage.removeItem('destino');
            localStorage.removeItem('michofer');
            localStorage.removeItem('miuser');
            localStorage.removeItem('viaje');
            location.reload();
	    })
    });
</script>
@endsection
