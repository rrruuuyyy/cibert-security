<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain;
use App\ActionTaken;
use Validator;

class ActionTakenController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $usuario_id ,$domain_id)
    {
        // Detectamos si el usuario es un administrador
        $validator = Validator::make($request->all(), [ 
            'type' => 'required',
        ]);  
        if ($validator->fails()) {
            return response()->json(['status'=>false,'mensaje'=>'Datos faltantes','error'=>$validator->errors()], 200);                        
        }
        $action = null;
        $user_token = $request->user();
        $domain = Domain::find($domain_id);
        if( !$domain ) return response()->json(['status'=>false,'mensaje'=>'No existe un dominio con ese id','error'=>'Id bad'],200);
        if( $user_token->role === "admin" ){
            $action = new ActionTaken( $request->all() );
            $action->domain_id = $domain_id;
            $action->save();            
        }else{
            $user = User::find($usuario_id);
            if( !$user ) return response()->json(['status'=>false,'mensaje'=>'No existe un usuario con ese id','error'=>'Id bad'],200);
            if( $user->id != $user_token->id ) return response()->json(['status'=>false,'mensaje'=>'Sin privilegios','error'=>'No privilegios'],200);
            $action = new ActionTaken( $request->all() );
            $action->domain_id = $domain_id;
            $action->save();
        }
        return response()->json(['status'=>true,'mensaje'=>'Action taken created','data'=>$action],200);
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
    public function update(Request $request, $id)
    {
        //
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
