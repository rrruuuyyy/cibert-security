<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table='domains';
    protected $primaryKey = 'id';
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = array(
        'user_id','url', 'name', 'type', 'status'
    );
    // Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
    protected $hidden = ['created_at','updated_at'];
    public function infections(){
        return $this->hasMany('App\Infection','domain_id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
