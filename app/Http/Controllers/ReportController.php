<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Barryvdh\DomPDF\Facade as PDF;
// use PDF;
// use Dompdf\Dompdf;
use PDF;
use App\User;
use App\Domain;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request, $usuario_id )
    {
        $user = User::find($usuario_id);
        if(!$user){
            return response()->json(['status'=>false,'mensaje'=>'No hay un usuario con ese codigo','error'=>'User not found'],200);
        }
        $data = [
            "domains" => null,
        ];
        $user_detec = $request->user();        
        if( $user_detec->role === 'admin' ){
            $data['domains'] = Domain::has('infections')->with(['infections','user'])->with(['actions_takens' => function ($query) {
                $query->orderBy('created_at','DESC')->get();
            }])->get();
            // for ($i=0; $i < count($domains); $i++) { 
            //     if( count($domains[$i]->infections) != 0 ){
            //         array_push( $data_array, $domains[$i] );
            //     }
            // }
            // $data_array->paginate(15);
            // var_dump( $data );
            // return;
        }else{
            $data['domains'] = Domain::has('infections')->where( 'user_id' , $usuario_id )->with(['infections', 'user'])->with(['actions_takens' => function ($query) {
                $query->orderBy('created_at','DESC')->get();
            }])->get();
        }
        $pdf = PDF::loadView('single_report', $data);
        return $pdf->download('Single_report.pdf');
        // $dompdf = new Dompdf();
        // $dompdf->loadHtml('hello world');
        // $dompdf->setPaper('A4', 'landscape');
        // $dompdf->render();
        // $dompdf->stream();
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
    public function store(Request $request)
    {
        //
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
