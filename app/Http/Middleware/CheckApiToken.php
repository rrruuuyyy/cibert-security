<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
{
    $user = $request;
    return response()->json($user);
    if( !$user ){
    return response()->json(['status'=>false,'mensaje'=>'Sin permisos','data'=>''],200);

    }
    return $next($request);

    // if(!empty(trim($request->input('api_token')))){

    //     $is_exists = User::where('id' , Auth::guard('api')->id())->exists();
    //     if($is_exists){
    //         return $next($request);
    //     }
    // }
    // return response()->json(['status'=>false,'mensaje'=>'Sin permisos','data'=>''],200);
    

}
}
