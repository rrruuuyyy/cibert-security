<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Infection extends Model
{
    protected $table='infections';
    protected $primaryKey = 'id';
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = array(
        'domain_id','type', 'abuse_type', 'evidence', 'date_detected'
    );
    // Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
    protected $hidden = ['created_at','updated_at'];

    public function domain(){
        return $this->belongsTo('App\Domain','domain_id');
    }
}
