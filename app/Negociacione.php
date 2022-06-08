<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Negociacione extends Model
{
    use SoftDeletes;
	protected $fillable = [
        'cliente_id',
        'chofer_id',
        'viaje_id',
        'precio_contraofertado',
        'status'
    ];

    protected $appends=['published'];
	public function getPublishedAttribute(){
		return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
	}

    public function chofer()
    {
        return $this->belongsTo(Chofere::class, 'chofer_id');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function viaje()
    {
        return $this->belongsTo(Viaje::class, 'viaje_id');
    }
}
