<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;
	protected $fillable = [
		'nombres',
		'apellidos',
		'telefono',
		'ciudad_id',
		'email',
		'perfil',
        'verficado',
        'estado'
	];

    public function ciudad()
    {
        return $this->belongsTo(Ciudade::class, 'ciudad_id');
    }
}
