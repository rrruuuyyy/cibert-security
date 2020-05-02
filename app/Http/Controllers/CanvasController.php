<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Canvas;
use Validator;

class CanvasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_detec = $request->user();
        if( $user_detec->role != 'super_admin' ){
            return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'Without privileges'],200);            
        }
        if( !$request->section ){
            return response()->json(['status'=>false,'mensaje'=>'Section not send','error'=>'Without section'],200);            
        }
        $data = Canvas::all();
        for ($i=0; $i < count($data) ; $i++) { 
            $data[$i]->data = json_decode($data[$i]->data);
        }
        return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'Without privileges'],200);
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
            return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'Without privileges'],200);            
        }
        $validator = Validator::make($request->all(), [ 
            'section' => 'required',
            // 'data' => 'required',
        ]);  
        if ($validator->fails()) {
            return response()->json(['status'=>false,'mensaje'=>'Datos faltantes','error'=>$validator->errors()], 200);                        
        }
        $canvas = new Canvas($request->all());
        $canvas->data = json_encode($canvas->data);
        $canvas->save();
        return response()->json(['status'=>true,'mensaje'=>'Canvas created','data'=>$canvas],200);            
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $section)
    {
        if(!$section){
            return response()->json(['status'=>false,'mensaje'=>'Undeclared section','error'=>'undeclared section'],200);            
        }
        $user_detec = $request->user();
        if( $user_detec->role != 'super_admin' ){
            return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'Without privileges'],200);            
        }
        $data = Canvas::where('section',$section)->get()->first();
        $data->data = json_decode($data->data);
        return response()->json(['status'=>true,'mensaje'=>'Canvas','data'=>$data],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $section)
    {
        if(!$section){
            return response()->json(['status'=>false,'mensaje'=>'Undeclared section','error'=>'undeclared section'],200);            
        }
        $user_detec = $request->user();
        if( $user_detec->role != 'super_admin' ){
            return response()->json(['status'=>false,'mensaje'=>'Without privileges','error'=>'Without privileges'],200);            
        }
        $data = Canvas::where('section',$section)->get()->first();
        $data->data = json_encode($request->data);
        $data->save();
        $data->data = json_decode($data->data);
        return response()->json(['status'=>true,'mensaje'=>'Canvas','data'=>$data],200);
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
