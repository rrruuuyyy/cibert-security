<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Infection;
use App\Domain;
use Validator;
use App\User;

class InfectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, $usuario_id, $domain_id )
    {
        $user_detec = $request->user();
        if( $usuario_id != $user_detec->id ){
            return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
        }
        $data = (object)[];
        $limit = ($request->limit) ? $request->limit : 15;
        if( $user_detec->role === 'admin' ){
            // $data = DB::table('domains')->with(['user'])->paginate($limit);
            // $data = Infection::paginate($limit);
            $data = Infection::where('domain_id',$domain_id)->paginate($limit);

        }else{
            $data = Infection::where('domain_id',$domain_id)->where( 'user_id' , $usuario_id )->paginate($limit);
            // $data = Infection::where( 'user_id' , $usuario_id )->paginate($limit);
        }
        return response()->json(['status'=>true,'mensaje'=>'Domains cargados','data'=>$data],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $usuario_id, $domain_id)
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
            'domain_id' => 'required',
            'name' => 'required',
            'quantity' => 'required',
        ]);  
        if ($validator->fails()) {
            return response()->json(['status'=>false,'mensaje'=>'Datos faltantes','error'=>$validator->errors()], 200);                        
        }
        $infection = new Infection( $request->all() );
        $infection->save();
        return response()->json(['status'=>true,'mensaje'=>'Infection created','data'=>$infection],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $usuario_id, $domain_id , $id)
    {
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $infection = Infection::find( $id );
        $user_detec = $request->user();        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
            }
            if( $infection->usuario_id != $usuario_id ){            
                return response()->json(['status'=>false,'mensaje'=>'Sin permisos al dominio','error'=>'Without privileges'],200);
            }
        }
        return response()->json(['status'=>true,'mensaje'=>'Infection get','data'=>$infection],200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $usuario_id, $domain_id  ,$id)
    {
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $infection = Infection::find( $id );
        $user_detec = $request->user();        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
            }
            if( $infection->usuario_id != $usuario_id ){            
                return response()->json(['status'=>false,'mensaje'=>'Sin permisos al dominio','error'=>'Without privileges'],200);
            }
        }
        $validator = Validator::make($request->all(), [ 
            'domain_id' => 'required',
            'name' => 'required',
            'quantity' => 'required',
        ]);    
        if ($validator->fails()) {
            return response()->json(['status'=>false,'mensaje'=>'Datos faltantes','error'=>$validator->errors()], 200);                        
        }
        $infection->domain_id = $request->domain_id;
        $infection->name = $request->name;
        $infection->quantity = $request->quantity;
        $infection->save();
        // $infection->domain();
        return response()->json(['status'=>true,'mensaje'=>'Infection updated','data'=>$infection],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $usuario_id, $domain_id  ,$id)
    {
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $infection = Infection::find( $id );
        $user_detec = $request->user();        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
            }
            if( $infection->usuario_id != $usuario_id ){            
                return response()->json(['status'=>false,'mensaje'=>'Sin permisos al dominio','error'=>'Without privileges'],200);
            }
        }
        $infection->delete();
        return response()->json(['status'=>true,'mensaje'=>'Infection deleted','data'=>''],200);
    }

    public function onlyInfections( Request $request, $usuario_id ){
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $data = (object)[];
        $data_array = [];
        $limit = ($request->limit) ? $request->limit : 15;
        $user_detec = $request->user();        
        if( $user_detec->role === 'admin' ){
            $data = Domain::has('infections')->with(['infections','user'])->paginate($limit);
            // for ($i=0; $i < count($domains); $i++) { 
            //     if( count($domains[$i]->infections) != 0 ){
            //         array_push( $data_array, $domains[$i] );
            //     }
            // }
            // $data_array->paginate(15);
            // var_dump( $data );
            // return;
        }else{
            $data = Domain::has('infections')->where( 'user_id' , $usuario_id )->with(['infections', 'user'])->paginate($limit);
        }
        return response()->json(['status'=>true,'mensaje'=>'Domains infected','data'=>$data],200);
    }
}