<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Config;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = ($request->limit) ? $request->limit : 15;
        $user = $request->user();
        if( $user->role === 'super_admin' ){
            $users = User::with(['domains'])->paginate($limit);
            return response()->json(['status'=>true,'mensaje'=>'Usuarios encontrados','data'=>$users],200);
        }
        $users = User::where('role','!=','super_admin')->with(['domains'])->paginate($limit);
        return response()->json(['status'=>true,'mensaje'=>'Usuarios encontrados','data'=>$users],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if( $user->role != 'super_admin' ){
            return response()->json(['status'=>false,'mensaje'=>'Sin privilegios','error'=>'Without privilagion'],200);
        }
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);
        $user = new User( $request->all() );
        $user->password = bcrypt('123asd');
        $user->save();
        // Al crear el usuario le asignamos una configuracion predeterminada;
        $config = new Config();
        $config->dashboard = (object)[];
        $config->dashboard->information_panel = true;
        $config->dashboard = json_encode( $config->dashboard );
        $config->user_id = $user->id;
        $config->save();
        return response()->json(['status'=>true,'mensaje'=>'Usuarios creado','data'=>$user],200);
    }

    public function changePassword(Request $request, $usuario_id){
        $user_detec = $request->user();
        $user = User::find($usuario_id);
        if( $user_detec->id != $user->id ){
            if( $user_detec->role != 'super_admin' ){
                return response()->json(['status'=>false,'mensaje'=>'Sin privilegios','error'=>'Without privilagion'],200);
            }
        }
        if( !$user ){
            return response()->json(['status'=>false,'mensaje'=>'User not found','error'=>'Usuario no encontrado'],200);
        }
        $validator = Validator::make($request->all(), [ 
            'password' => 'required',  
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);                        
        }
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['status'=>true,'mensaje'=>'Usuarios actualziado','data'=>''],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $usuario_id)
    {
        $user_detec = $request->user();
        $user = User::find( $usuario_id );
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'Sin privilegios','error'=>'Without privilagion'],200);
            }            
        }
        return response()->json(['status'=>true,'mensaje'=>'Usuarios creado','data'=>$user],200);
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
        $user_detec = $request->user();
        $user = User::find( $usuario_id );
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }        
        if( $user_detec->role != "admin" ){
            if( $usuario_id != $user_detec->id ){
                return response()->json(['status'=>false,'mensaje'=>'Sin privilegios','error'=>'Without privilagion'],200);
            }            
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();
        return response()->json(['status'=>true,'mensaje'=>'Usuarios actualizado','data'=>$user],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $usuario_id )
    {
        $user_detec = $request->user();
        $user = User::find( $usuario_id );
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }   
        if( $user_detec->id === $user->id ){
            return response()->json(['status'=>false,'mensaje'=>'Auto borrandos','error'=>'No te puedes auto borrar'],200);
        }     
        if( $user_detec->role != "super_admin" ){
            return response()->json(['status'=>false,'mensaje'=>'Sin privilegios','error'=>'Without privilagion'],200);
            // if( $usuario_id != $user_detec->id ){ //Esto hace que si el usuario intenta borrar uno que no sea el no podra
            // }            
        }
        $user->delete();
        return response()->json(['status'=>true,'mensaje'=>'Usuarios borrado','data'=>''],200);
    }
}
