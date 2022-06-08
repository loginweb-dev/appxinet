<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Categoria;
use App\Chofere;
use App\Ciudade;
use App\Cliente;
use App\Estado;
use App\Negociacione;
use App\Objeto;
use App\Pasarela;
use App\Ubicacione;
use App\Viaje;
use App\Notificacione;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//CLIENTE
Route::post('cliente/by', function (Request $request) {
    return Cliente::where('telefono', $request->telefono)->with('ciudad')->first();
});
Route::get('cliente/id/{id}', function ($id) {
    return Cliente::find($id);
});
Route::get('cliente/viajes/{id}', function ($id) {
    return Viaje::where('cliente_id', $id)->with('cliente', 'estado', 'categoria','origen','destino')->orderBy('created_at', 'desc')->get();
});
Route::get('cliente/viaje/negociaciones/{id}', function ($id) {
    return Negociacione::where('viaje_id', $id)->with('chofer')->get();
});

// TODOS LOS OBJETOS DEL VIAJE
Route::get('objetos', function(){
    return Objeto::all();
});
//BUSCAR OBJETO POR CRITERIO
Route::get('objeto_individual/{criterio}', function($criterio){
    $objeto= Objeto::where('name', 'like', '%'.$criterio.'%')->orderBy('name', 'desc')->get();
    return $objeto;
});
//TODOS LOS CLIENTES
Route::get('clientes', function(){
    return Cliente::all();
});

//CLIENTE POR ID
Route::get('cliente_por_id/{id}', function($id){
    return Cliente::find($id);
});
//BUSCAR CLIENTE POR CRITERIO
Route::get('cliente/name/{criterio}', function($criterio){
    $cliente= Cliente::where('name', 'like', '%'.$criterio.'%')->orderBy('name', 'desc')->get();
});
//TODOS LOS CHOFERES
Route::get('chofer/by/{telefono}', function($telefono){
    return Chofere::where('telefono', $telefono)->with('ciudad','categoria')->first();
});
//Choferes Libres
Route::post('choferes/libres', function(Request $request){
    //$midata2=json_decode($midata);

    return Chofere::where('estado', 1)->where('ciudad_id',$request->ciudad_id)->where('categoria_id',$request->categoria_id)->with('ciudad')->get();
});
Route::get('chofer/id/{id}', function($id){
    return Chofere::find($id);
});
Route::get('choferes', function(){
    return Chofere::all();
});

//CHOFER POR ID
Route::get('chofer_por_id/{id}', function($id){
    return Chofere::find($id);
});
//BUSCAR CHOFER POR CRITERIO
Route::get('chofer/name/{criterio}', function($criterio){
    return Chofere::where('name', 'like', '%'.$criterio.'%')->orderBy('name', 'desc')->get();
});
//TODAS LAS CIUDADES
Route::get('ciudades', function(){
    return Ciudade::all();
});
//CIUDAD POR ID
Route::get('ciudad/{id}', function($id){
    return Ciudade::find($id);
});
//TODAS LAS CATEGORIAS
Route::get('categorias', function(){
    return Categoria::all();
});
//CATEGORIA POR ID
Route::get('categoria/{id}', function($id){
    return Categoria::find($id);
});
//TODAS LOS ESTADOS
Route::get('estados', function(){
    return Estado::all();
});
//ESTADOS POR ID
Route::get('estado/{id}', function($id){
    return Estado::find($id);
});
//TODAS LAS PASARELAS
Route::get('pararelas', function(){
    return Pasarela::all();
});
//PASARELA POR ID
Route::get('pasarela/{id}', function($id){
    return Pasarela::find($id);
});
//TODAS LAS UBICACIONES
Route::get('ubicaciones', function(){
    return Ubicacione::all();
});
//UBICACIÓN POR ID
Route::get('ubicacion/{id}', function($id){
    return Ubicacione::find($id);
});

//TODAS LOS VIAJES----------------------
Route::get('viajes', function(){
    return Viaje::all();
});

Route::get('viaje/aprobado/{midata}', function($midata){
    $midata2=json_decode($midata);

    $viaje = Viaje::find($midata2->viaje_id);
    $viaje->status_id = 6;
    $viaje->chofer_id = $midata2->chofer_id;
    $viaje->precio_final = $midata2->precio_final;
    $viaje->save();

    $nego = Negociacione::find($midata2->nego_id);
    $nego->status = true;
    $nego->save();

    $chofer = Chofere::find($midata2->chofer_id);
    $chofer->estado = false;
    $chofer->save();

    $newviaje = Viaje::find($midata2->viaje_id);
    return $newviaje;
});

//VIAJE POR ID
Route::get('viaje/{id}', function($id){
    return Viaje::find($id);
});
//Viajes para chofer disponibles
Route::post('viajes_chofer', function(Request $request){
    //$midata2= json_decode($midata);
    return Viaje::where('ciudad_id',$request->ciudad_id)->where('categoria_id',$request->categoria_id)->where('status_id',2)->with('cliente','origen','destino','categoria', 'estado')->orderBy('created_at','desc')->get();
});
//Viajes hechos por chofer
Route::post('viajes_chofer_concluidos', function(Request $request){
    return Viaje::where('chofer_id',$request->id)->where('status_id',4)->with('cliente')->get();
});
//SAVE VIAJE
Route::post('viaje/save', function(Request $request){
    // return $miviaje;
    //$miviaje2=json_decode($miviaje);
    $viaje= Viaje::create([
        'cliente_id'=>$request->cliente_id,
        'chofer_id'=> null,
        'origen_location'=> $request->origen_location,
        'destino_location'=> $request->destino_location,
        'categoria_id'=> $request->categoria_id,
        'precio_inicial'=> $request->precio_ofertado,
        'precio_final'=> null,
        'cantidad_viajeros'=> $request->cantidad_viajeros,
        'cantidad_objetos'=> $request->cantidad_objetos,
        'tipo_objeto_id'=> $request->tipo_objeto_id,
        'detalles'=> null,
        'status_id'=> 2,
        'puntuacion'=> null,
        'tiempo'=>$request->tiempo,
        'distancia'=>$request->distancia,
        'pago_id'=> $request->pago_id,
        'ciudad_id' => $request->ciudad_id,
        'dt' =>$request->dt,
        'tt' =>$request->tt,
        'origen_g' =>$request->origen_g,
        'destino_g' =>$request->destino_g
    ]);

    $micliente = Cliente::find($request->cliente_id);
    $micliente->estado = true;
    $micliente->save();

    $newviaje = Viaje::where('id', $viaje->id)->with('cliente', 'estado', 'ciudad', 'categoria')->first();
    return $newviaje;
});

//SAVE UBICACION---------------
Route::post('location/save', function(Request $request){
    //$midata2=json_decode($midata);
    $ubicacion= Ubicacione::create([
        'latitud'=>$request->latitud,
        'longitud'=>$request->longitud,
        'descripcion'=>$request->detalle
    ]);
    return $ubicacion;
});


//notificaciones
Route::get('notificaciones', function () {
    $result = Notificacione::all();
    return $result;
});
Route::get('notificacione/save/{message}', function ($message) {
    // $midata2 = json_decode($message);
    $minoti = Notificacione::create([
        'message' => $message
    ]);
    return $minoti;
});

//PIN CLIENTE
Route::get('pin/save/{cliente_id}/{pin}', function ($cliente_id, $pin) {
    $cliente = Cliente::find($cliente_id);
    $cliente->pin = $pin;
    $cliente->save();
    return $cliente;
});
Route::post('pin/get', function (Request $request) {
    $cliente = Cliente::where('telefono', $request->telefono)->where('pin', $request->pin)->with('ciudad')->first();
    $cliente->verificado = true;
    $cliente->save();
    return $cliente;
});
Route::get('pin/update/{id}', function ($id) {
    $cliente = Cliente::find($id);
    $cliente->verificado = true;
    $cliente->save();
    $newcliente = Cliente::where('telefono', $cliente->telefono)->where('pin', $cliente->pin)->with('ciudad')->first();
    return $newcliente;
});

//PIN CHOFER
Route::get('chofer/pin/save/{chofer_id}/{pin}', function ($chofer_id, $pin) {
    $chofer = Chofere::find($chofer_id);
    $chofer->pin = $pin;
    $chofer->save();
    $newchofer = Chofere::where('telefono', $chofer->telefono)->where('pin', $chofer->pin)->with('ciudad', 'categoria')->first();

    return $chofer;
});
Route::post('chofer/pin/get', function (Request $request) {
    $chofer = Chofere::where('telefono', $request->telefono)->where('pin', $request->pin)->with('ciudad', 'categoria')->first();
    return $chofer;
});

Route::get('chofer/pin/update/{id}', function ($id) {
    $chofer = Chofere::find($id);
    $chofer->verificado = true;
    $chofer->save();
    $newchofer = Chofere::where('telefono', $chofer->telefono)->where('pin', $chofer->pin)->with('ciudad')->first();
    return $newchofer;
});

// monitor solicitudes
Route::get('chofer/verificado', function ($id) {
    $chofer = Chofer::where('estado_verificacion', true)->first();
    return $chofer;
});


//SAVE NECOCIACION
Route::get('save_negociaciones/{midata}',function($midata){
    $midata2= json_decode($midata);

    $negociacion= Negociacione::create([
        'cliente_id'=>$midata2->cliente_id,
        'chofer_id'=>$midata2->chofer_id,
        'viaje_id'=>$midata2->viaje_id,
        'precio_contraofertado'=>$midata2->precio_contraofertado,
        'status'=>$midata2->status
    ]);

    return $negociacion;
});
Route::get('delete_nego/{id}', function ($id) {
    $nego = Negociacione::find($id);
    $nego->delete();
    return $nego;
});


//Update Estado Chofer
Route::get('update_estado/chofer/{id}/{estado}',function($id,$estado){
    $chofer=Chofere::find($id);
    $chofer->estado=$estado;
    $chofer->save();
});


//Liberar Estado Cliente
Route::get('liberar_cliente/{id}',function($id){
    $viaje=Viaje::find($id);
    $cliente=Cliente::find($viaje->cliente_id);
    $cliente->estado=0;
    $cliente->save();
});

//Consultar Chofer Ocupado con Viaje(Recogiendo)
Route::get('chofer_viaje_consulta/{id}', function($id){
    $viajes= Viaje::where('chofer_id',$id)->where('status_id',6)->first();
    return $viajes;
});

//Consultar Chofer Ocupado con Viaje(Llevando a su destino)
Route::get('viaje_chofer_encurso/{id}', function($id){
    $viajes= Viaje::where('chofer_id',$id)->where('status_id',3)->with('cliente')->first();
    return $viajes;
});


//Concluir Viaje
Route::get('concluir_viaje/{id}',function($id){
    $viaje=Viaje::find($id);
    $viaje->status_id=4;
    $viaje->save();

    return true;
});


//Cancelar Viaje
Route::get('cancelar_viaje/{id}/{detalle}',function($id,$detalle){
    $viaje=Viaje::find($id);
    $viaje->status_id=5;
    $viaje->detalles=$detalle;
    $viaje->save();

    return true;
});

//Pasajero Recogido
Route::get('cliente_recogido/{id}',function($id){
    $viaje=Viaje::find($id);
    $viaje->status_id=3;
    $viaje->save();

    return true;
});

//////////////FILTROS///////////////

//Filtro Numero de Viaje Chofer

// Route::get('viaje_chofer_num/{id}/{num}', function($id,$num){
//     $viajes= Viaje::where('chofer_id',$id)->where('status_id',4)->get();

//     foreach($viajes as $item){

//     }
//     return $viajes;
// });


