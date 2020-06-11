<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Infection;
use App\BlackList;
use Validator;
use App\User;
use App\Domain;

class BlackListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $usuario_id, $domain_id )
    {
        $user_detec = $request->user();
        
        $domain = Domain::find($domain_id);
        $data = (object)[];
        $limit = ($request->limit) ? $request->limit : 15;
        if( $user_detec->role != 'super_admin' ){
            if( $usuario_id != $user_detec->id || $domain->user_id != $usuario_id && $domain->user_id != $user_detec->sub_id ){
                return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'Sin privilegios'],200);
            }
            $data = BlackList::where('domain_id',$domain_id)->paginate($limit);
        }else{
            $data = BlackList::where('domain_id',$domain_id)->paginate($limit);
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
        $domain = Domain::find($domain_id);
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $user_detec = $request->user();
        if( $user_detec->role != 'super_admin' ){
            if( $usuario_id != $user_detec->id || $domain->user_id != $usuario_id && $domain->user_id != $user->sub_id ){
                return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'Sin privilegios'],200);
            }
        }   
        $validator = Validator::make($request->all(), [ 
            'domain_id' => 'required',
            'type' => 'required',
        ]);  
        if ($validator->fails()) {
            return response()->json(['status'=>false,'mensaje'=>'Datos faltantes','error'=>$validator->errors()], 200);                        
        }
        $black = new BlackList( $request->all() );
        $black->save();
        return response()->json(['status'=>true,'mensaje'=>'Black list created','data'=>$black],200);
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
        $black = BlackList::find( $id );
        $user_detec = $request->user();        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
            }
            if( $black->usuario_id != $usuario_id ){            
                return response()->json(['status'=>false,'mensaje'=>'Sin permisos al dominio','error'=>'Without privileges'],200);
            }
        }
        return response()->json(['status'=>true,'mensaje'=>'Black list get','data'=>$black],200);
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
        $domain = Domain::find($domain_id);
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $black = BlackList::find( $id );
        $user_detec = $request->user();        
        if( $user_detec->role != "super_admin" ){
            if( $usuario_id != $user_detec->id || $domain->user_id != $user->id && $domain->user_id != $user->sub_id ){
                return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'Id bad'],200);
            }
        }
        $validator = Validator::make($request->all(), [ 
            'domain_id' => 'required',
            'type' => 'required',
        ]);    
        if ($validator->fails()) {
            return response()->json(['status'=>false,'mensaje'=>'Datos faltantes','error'=>$validator->errors()], 200);                        
        }
        $black->domain_id = $domain_id;
        $black->type = $request->type;
        $black->save();
        // $infection->domain();
        return response()->json(['status'=>true,'mensaje'=>'Black list updated','data'=>$black],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $usuario_id, $domain_id, $id)
    {
        $domain = Domain::find($domain_id);
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $black = BlackList::find( $id );
        $user_detec = $request->user();        
        if( $user_detec->role != "super_admin" ){
            if( $usuario_id != $user_detec->id || $domain->user_id != $user->id && $domain->user_id != $user->sub_id ){
                return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'Id bad'],200);
            }
        }
        $black->delete();
        return response()->json(['status'=>true,'mensaje'=>'Black list deleted','data'=>''],200);
    }
}
