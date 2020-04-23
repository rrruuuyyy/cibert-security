<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Domain;
use Validator;
use App\User;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $usuario_id)
    {
        // $user = User::find($usuario_id);
        // if(!$user){
        //     return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        // }
        //Detectamos que el usuario sea el mismo y sus permisos
        $user_detec = $request->user();
        if( $usuario_id != $user_detec->id ){
            return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
        }
        $data = (object)[];
        $limit = ($request->limit) ? $request->limit : 15;
        if( $user_detec->role === 'admin' ){
            // $data = DB::table('domains')->with(['user'])->paginate($limit);
            $data = Domain::with(['user','infections'])->with(['actions_takens' => function ($query) {
                $query->orderBy('created_at','DESC')->first();
            }])->paginate($limit);
        }else{
            $data = Domain::where( 'user_id' , $usuario_id )->with(['user','infections'])->with(['actions_takens' => function ($query) {
                $query->orderBy('created_at','DESC')->fist();
            }])->paginate($limit);
        }
        return response()->json(['status'=>true,'mensaje'=>'Domains cargados','data'=>$data],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $usuario_id )
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
            'url' => 'required',
        ]);  
        if ($validator->fails()) {
            return response()->json(['status'=>false,'mensaje'=>'Datos faltantes','error'=>$validator->errors()], 200);                        
        }
        $domain = new Domain( $request->all() );
        $domain->save();
        $domain->user();
        return response()->json(['status'=>true,'mensaje'=>'Domains created','data'=>$domain],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $usuario_id, $id)
    {
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $domain = Domain::find( $id );
        $user_detec = $request->user();        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
            }
            if( $domain->usuario_id != $usuario_id ){            
                return response()->json(['status'=>false,'mensaje'=>'Sin permisos al dominio','error'=>'Without privileges'],200);
            }
        }
        return response()->json(['status'=>true,'mensaje'=>'Domains get','data'=>$domain],200);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $usuario_id ,$id)
    {
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $domain = Domain::find( $id );
        $user_detec = $request->user();        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
            }
            if( $domain->user_id != $usuario_id ){            
                return response()->json(['status'=>false,'mensaje'=>'Sin permisos al dominio','error'=>'Without privileges'],200);
            }
        }
        $validator = Validator::make($request->all(), [ 
            'url' => 'required',
        ]);  
        if ($validator->fails()) {
            return response()->json(['status'=>false,'mensaje'=>'Datos faltantes','error'=>$validator->errors()], 200);                        
        }
        $domain->user_id = $request->user_id;
        $domain->name = $request->name;
        $domain->url = $request->url;
        $domain->type = $request->type;
        $domain->status = $request->status;
        $domain->save();
        $domain->user();
        return response()->json(['status'=>true,'mensaje'=>'Domains get','data'=>$domain],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $usuario_id ,$id)
    {
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $domain = Domain::find( $id );
        $user_detec = $request->user();        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'El id del usuario no corresponde con el del url','error'=>'Id bad'],200);
            }
            if( $domain->usuario_id != $usuario_id ){            
                return response()->json(['status'=>false,'mensaje'=>'Sin permisos al dominio','error'=>'Without privileges'],200);
            }
        }
        $domain->delete();
        return response()->json(['status'=>true,'mensaje'=>'Domains deleted','data'=>''],200);
    }

    public function createByFile( Request $request, $usuario_id ){
        $usuario = User::find( $usuario_id );        
        $user_detec = $request->user();
        if(!$usuario){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        if( $user_detec->role != 'admin' ){
            if( $user_detec->id != $usuario->id ){
                return response()->json(['status'=>false,'mensaje'=>'Sin permisos al dominio','error'=>'Without privileges'],200);
            }
        }
        $file = $request->file('file_xlsx');        
        if( !$file ) return response()->json(['status'=>false,'mensaje'=>'File not found','error'=>'Without file'],200);
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load( $file->path() );
        $data_array = $spreadsheet->getActiveSheet()->toArray();
        // var_dump( count($data_array) );
        // return;

        for ($i=0; $i < count( $data_array ) ; $i++) { 
            $url = $data_array[$i][0];
            if ( $url = 'Dominios' || $url = 'dominios' || $url = '' ) continue;
            $url = str_replace( '/', '', $url ); 
            $url = str_replace( 'https:', '', $url ); 
            $url = str_replace( 'www.', '', $url );
            $domain = Domain::where( 'url', $url )->get()->first();
            if( !$domain ){
                $new_domain = new Domain();
                $new_domain->url = $url;
                $new_domain->save();
            }
        }
        return response()->json(['status'=>true,'mensaje'=>'Xlsx loaded','data'=>$data_array],200);

    }
}
