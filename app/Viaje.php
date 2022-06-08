<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Viaje extends Model
{
	use SoftDeletes;
    protected $fillable = [
    'cliente_id',
    'chofer_id',
    'origen_location',
    'destino_location',
    'categoria_id',
    'precio_inicial',
    'precio_final',
    'cantidad_viajeros',
    'cantidad_objetos',
    'tipo_objeto_id',
    'detalles',
    'status_id',
    'puntuacion',
    'tiempo',
    'distancia',
    'pago_id',
    'ciudad_id',
    'dt',
    'tt',
    'origen_g',
    'destino_g',
    'estado'
    ];

    protected $appends=['published'];
	public function getPublishedAttribute(){
		return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
	}

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'status_id');
    }
    public function ciudad()
    {
        return $this->belongsTo(Ciudade::class, 'ciudad_id');
    }
    public function chofer()
    {
        return $this->belongsTo(Chofere::class, 'chofer_id');
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    public function origen()
    {
        return $this->belongsTo(Ubicacione::class, 'origen_location');
    }
    public function destino()
    {
        return $this->belongsTo(Ubicacione::class, 'destino_location');
    }
}
