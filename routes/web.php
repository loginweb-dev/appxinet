<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('landingpage');
});

Route::get('/mapa/cliente', function () {
    return view('mapa_cliente');
});

Route::get('/privacidad', function () {
    return view('privacidad');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('perfil/cliente', function () {
    return view('perfil_cliente');
});

Route::get('viaje/crear', function () {
    return view('viajes');
})->name('viaje_crear');

Route::get('chofer/crear', function () {
    return view('register_chofer');
});

Route::get('cliente/crear', function () {
    return view('register_cliente');
});

Route::get('viaje/{id}', function () {

    return view('viaje');
})->name('viaje');

Route::get('misviajes', function () {
    return view('misviajes');
})->name('misviaje');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('viajes/monitor', function () {
    return view('viajes_monitor');
})->name('viajes_monitor');

Route::post('chofer/nuevo', function  (Request $request) {
    // return $request;
    $validated = $request->validate([
        'categoria_id' => 'required',
        'ciudad_id' => 'required'
    ]);
    // $validated = $request->validate([
    //     'ciudad_id' => 'required'
    // ]);

    $perfil = $request->file('imgchofer');
    $newperfil =  Storage::disk('public')->put('choferes', $perfil);
    $vehiculo= $request->file('imgfotosdelvehiculo');
    $newvehiculo=Storage::disk('public')->put('choferes', $vehiculo);

    $ci= $request->file('imgcarnet');
    $newcarnet=[];
    if ($ci) {
        $indexcarnet=0;
        foreach($ci as $item){
            $newcarnet[$indexcarnet]=Storage::disk('public')->put('choferes', $item);
            $indexcarnet=$indexcarnet+1;
        }
    }

    $licencia= $request->file('imglicencia');
    $newlicencia=[];
    if ($licencia) {
        $indexlicencia=0;
        foreach($licencia as $item){
            $newlicencia[$indexlicencia]=Storage::disk('public')->put('choferes', $item);
            $indexlicencia=$indexlicencia+1;
        }
    }


    $chofer= App\Chofere::create([
        'nombres'=> $request->firstname,
        'apellidos'=> $request->lastname,
        'email'=>$request->email,
        'telefono'=> $request->phone,
        'ciudad_id'=> $request->ciudad_id,
        'perfil'=> $newperfil,
        'breve'=> $newlicencia ? json_encode($newlicencia) : null,
        'vehiculo'=> $newvehiculo,
        'carnet'=> $newcarnet ? json_encode($newcarnet) : null,
        'estado'=>false,
        'categoria_id'=>$request->categoria_id,
        'estado_verificacion'=>false,
        'creditos'=>500
    ]);
return view('welcome_chofer', compact('chofer'));
})->name('registro_chofer');


Route::post('chofer_update', function  (Request $request) {

    $chofer=App\Chofere::where('telefono',$request->phone)->first();
    $verificacion=1;


    $perfil = $request->file('imgchofer') ? $request->file('imgchofer'):null;
    if($perfil!=null){
        $newperfil =  Storage::disk('public')->put('choferes', $perfil);
        // if(($chofer->perfil)!=null){
        //     $oldperfil= Storage::disk('public')->delete('choferes',$chofer->perfil);
        // }
        $chofer->perfil=$newperfil;
    }

    $ci= $request->file('imgcarnet') ? $request->file('imgcarnet') :null ;
    if($ci!=null){
        $newcarnet=[];
        $indexcarnet=0;
        // $indexoldcarnet=0;
        // $arreglocarnet[]=$chofer->carnet;
        // if($arreglocarnet!=null){
        //     foreach ($arreglocarnet as $itemold) {
        //         $oldcarnet= Storage::disk('public')->delete('choferes',$itemold[$indexoldcarnet]);
        //         $indexoldcarnet=$indexoldcarnet+1;
        //     }
        // }
        foreach($ci as $item){
            $newcarnet[$indexcarnet]=Storage::disk('public')->put('choferes', $item);
            $indexcarnet=$indexcarnet+1;
        }
        $chofer->carnet=$newcarnet;
        $verificacion=0;

    }


    $licencia= $request->file('imglicencia') ? $request->file('imglicencia'):null;
    if($licencia!=null){
        $newlicencia=[];
        $indexlicencia=0;
        foreach($licencia as $item){
            $newlicencia[$indexlicencia]=Storage::disk('public')->put('choferes', $item);
            $indexlicencia=$indexlicencia+1;
        }
        $chofer->breve=$newlicencia;
        $verificacion=0;

    }


    $vehiculo= $request->file('imgfotosdelvehiculo') ? $request->file('imgfotosdelvehiculo'):null;
    if($vehiculo!=null){
        $newvehiculo=Storage::disk('public')->put('choferes', $vehiculo);
        $chofer->vehiculo=$newvehiculo;
        $verificacion=0;
    }

    if($request->firstname!=$chofer->nombres){
        $verificacion=0;
    }
    if($request->lastname!=$chofer->apellidos){
        $verificacion=0;
    }

    if($request->ciudad_id!=$chofer->ciudad_id){
        $verificacion=0;
    }
    if($request->categoria_id!=$chofer->categoria_id){
        $verificacion=0;
    }


    $nombres=$request->firstname ? $request->firstname:null;
    $apellidos=$request->lastname ? $request->lastname:null;
    $email=$request->email ? $request->email:null;
    $categoria=$request->categoria_id;
    $ciudad=$request->ciudad_id;

    $chofer->nombres=$nombres;
    $chofer->apellidos=$apellidos;
    $chofer->email=$email;
    $chofer->categoria_id=$categoria;
    $chofer->ciudad_id=$ciudad;

    if($verificacion!=1){
        $chofer->estado_verificacion=$verificacion;
    }

    $chofer->save();


    return view('perfil_chofer', compact('chofer'));




})->name('update_chofer');


Route::post('cliente/nuevo', function  (Request $request) {
    $validated = $request->validate([
        'ciudad_id' => 'required'
    ]);
    $perfil = $request->file('imgcliente');
    $newperfil = null;
    if ($perfil) {
        $newperfil =  Storage::disk('public')->put('clientes', $perfil);
    }
    $cliente= App\Cliente::create([
        'nombres'=> $request->firstname,
        'apellidos'=> $request->lastname,
        'telefono'=> $request->phone,
        'ciudad_id'=> $request->ciudad_id,
        'perfil'=> $newperfil ? $newperfil : null,
        'verificado'=> false,
        'estado' => false

    ]);
    return view('welcome_cliente', compact('cliente'));
})->name('registro_cliente');

Route::get('cliente/welcome', function () {
    return view('welcome_cliente');
})->name('welcome_cliente');

Route::get('welcomes/chofer', function () {
    return view('welcome_chofer');
});

Route::get('perfil/chofer', function () {
    return view('perfil_chofer');
});

Route::get('historial/chofer', function () {
    return view('historial_viajes_chofer');
});

Route::get('historial/cliente', function () {
    return view('historial_viajes_cliente');
});

Route::get('soporte', function () {
    return view('soporte');
});

Route::get('creditos/chofer', function () {
    return view('creditos_chofer');
});

Route::get('notificacion/banipay', function () {
    return view('notificacion_banipay');
});

Route::get('verification/chofer', function () {
    return view('verification');
});
Route::get('detalle_viaje_chofer', function () {
    return view('detalle_viaje_chofer');
});
Route::get('mapa_chofer', function () {
    return view('mapa_chofer');
});
