<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Variables;
use Validator;

class VariablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Variables::get()->first();
        $data->data = json_decode( $data->data );
        return response()->json(['status'=>true,'mensaje'=>'Variables','data'=>$data],200);        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_detec = $request->user();
        if( $user_detec->role != 'super_admin' ){
            return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'without privileges'],200);        
        }
        $validator = Validator::make($request->all(), [ 
            'data' => 'required', 
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);                        
        }
        $variable = new Variables( $request->all() );
        $variable->data = json_encode( $variable->data );
        $variable->save();
        $variable->data = json_decode( $variable->data );
        return response()->json(['status'=>true,'mensaje'=>'Variable created','data'=>$variable],200);        

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
    public function update(Request $request, $variable_id)
    {
        $user_detec = $request->user();
        if( $user_detec->role != 'super_admin' ){
            return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'without privileges'],200);        
        }
        $validator = Validator::make($request->all(), [ 
            'data' => 'required', 
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);                        
        }
        $variable = Variables::find($variable_id);
        if( !$variable ){
            return response()->json(['status'=>false,'mensaje'=>'Variable not found','error'=>'Not found'],200);        
        }
        $variable->data = json_encode( $request->data );
        $variable->save();
        return response()->json(['status'=>true,'mensaje'=>'Variable updated','data'=>$variable],200);        
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
