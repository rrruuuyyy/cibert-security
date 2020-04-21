<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller
{
    //
    public $successStatus = 200;
    public function register(Request $request) {    
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'role' => 'required',
            'email' => 'required|email', 
            'password' => 'required',  
            'c_password' => 'required|same:password',
        ]);
        // return response()->json(['status'=>true,'data'=>$request->all()], $this->successStatus); 

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);                        
        }    
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = '';
        try {
            $user = User::create($input); 
        } catch (\Throwable $th) {
            return response()->json(['status'=>false,'mensaje'=>'Usuario no creado','error'=>$th->getMessage()], 200);
        }
        return response()->json(['status'=>true,'mensaje'=>'Usuario creado correctamente'], $this->successStatus); 
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status'=>false,
                'mensaje' => 'Credenciales incorrectas',
                'error'=>''
            ], 200);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        $user->token = $tokenResult->accessToken;
        return response()->json([
            'status' => true,
            'data' => $user,
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
        ]);
    }
    // public function getUser() {
    //     $user = Auth::user();
    //     return response()->json(['success' => $user], $this->successStatus); 
    // }
    public function user(Request $request)
    {
        $user = $request->user();
        if( $user->contador_id != null ){
            $user->contador = Contador::find($user->contador_id);
        }
        return response()->json(['status'=>true,'data'=>$user],200);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 
            'Successfully logged out']);
    }
    
}
