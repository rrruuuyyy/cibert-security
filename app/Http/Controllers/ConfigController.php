<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Config;
use App\User;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $usuario_id)
    {
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $user_detec = $request->user();        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
            }
        }
        $validator = Validator::make($request->all(), [ 
            'dashboard' => 'required',
        ]);  
        if ($validator->fails()) {
            return response()->json(['status'=>false,'mensaje'=>'Datos faltantes','error'=>$validator->errors()], 200);                        
        }
        $config = new Config( $request->all() );
        $config->dashboard = json_encode( $config->dashboard );
        $config->save();
        return response()->json(['status'=>true,'mensaje'=>'Domains created','data'=>$config],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $usuario_id)
    {
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $config = Config::where('user_id', $usuario_id )->get()->first();
        $user_detec = $request->user();        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
            }
            if( $config->user_id != $usuario_id ){            
                return response()->json(['status'=>false,'mensaje'=>'Sin permisos al dominio','error'=>'Without privileges'],200);
            }
        }
        $validator = Validator::make($request->all(), [ 
            'dashboard' => 'required',
        ]);  
        if ($validator->fails()) {
            return response()->json(['status'=>false,'mensaje'=>'Datos faltantes','error'=>$validator->errors()], 200);                        
        }
        $config->dashboard = json_encode( $request->dashboard );
        $config->save();
        $config->dashboard = json_decode(  $config->dashboard );
        return response()->json(['status'=>true,'mensaje'=>'Config update','data'=>$config],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
