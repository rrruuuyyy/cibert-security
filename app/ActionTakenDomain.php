<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActionTakenDomain extends Model
{
    protected $table='action_taken_domains';
    protected $primaryKey = 'id';
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = array(
        'domain_id','type'
    );
    // Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
    protected $hidden = ['updated_at'];

    public function domain(){
        return $this->belongsTo('App\Domain','domain_id');
    }
}
