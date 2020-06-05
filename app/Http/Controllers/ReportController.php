<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
// use Barryvdh\DomPDF\Facade as PDF;
// use PDF;
// use Dompdf\Dompdf;
use PDF;
use App\User;
use App\Variables;
use App\Domain;
use Spipu\Html2Pdf\Html2Pdf;

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
        if( $user_detec->role === 'super_admin' ){
            $data['domains'] = Domain::has('infections')->with(['infections','user'])->with(['actions_takens' => function ($query) {
                $query->orderBy('created_at','DESC')->get();
            }])->with(['actions_takens_domain' => function ($query) {
                $query->orderBy('created_at','DESC')->get()->first();
            }])->get();
            $data['total_domains'] = Domain::get()->count();

        }else{
            $data['domains'] = Domain::has('infections')->where( 'user_id' , $usuario_id )->with(['infections', 'user'])->with(['actions_takens' => function ($query) {
                $query->orderBy('created_at','DESC')->get();
            }])->with(['actions_takens_domain' => function ($query) {
                $query->orderBy('created_at','DESC')->get()->first();
            }])->get();
            $data['total_domains'] = Domain::where( 'user_id' , $usuario_id )->get()->count();
        }
        // $var = Variables::get()->first();
        // $var = json_decode( $var->data );
        // $data['variables'] = $var;
        // $data['variables']->tld = $user->tld;
        $data['user'] = $user;
        $porc = (object)[];
        $porc->black_hat = 0;
        $porc->pharming = 0;
        $porc->malware = 0;
        $porc->phising = 0;
        $porc->seo_spam = 0;    
        $total = 0;
        for ($i=0; $i < count( $data['domains'] ) ; $i++) { 
            $total = $total + count($data['domains'][$i]->infections);
            for ($j=0; $j < count($data['domains'][$i]->infections) ; $j++) { 
                switch ( $data['domains'][$i]->infections[$j]->type ) {
                    case 'black_hat':
                        $porc->black_hat = $porc->black_hat + 1;
                    break;
                    case 'pharming':
                        $porc->pharming = $porc->pharming + 1;
                    break;
                    case 'malware':
                        $porc->malware = $porc->malware + 1;
                    break;
                    case 'phising':
                        $porc->phising = $porc->phising + 1;
                    break;
                    case 'seo_spam':
                        $porc->seo_spam = $porc->seo_spam + 1;
                    break;
                }
            }
        }
        if($total != 0){
            $porc->black_hat = round( ($porc->black_hat / $total) * 100, 2 ) ;
            $porc->pharming = round( ($porc->pharming / $total) * 100, 2 ) ;
            $porc->malware = round( ($porc->malware / $total) * 100, 2 );
            $porc->phising = round( ($porc->phising / $total) * 100, 2 );
            $porc->seo_spam = round( ($porc->seo_spam / $total) * 100, 2 );
        }
        $data['porcents'] = $porc;
        // return response()->json($porc,200);
        // $porc
        return \view('single_report', $data);
        // $pdf = PDF::loadView('single_report', $data);
        // return $pdf->download('Domain abuse.pdf');
        // try {
        //     $content = view('example1', $data);        
        //     $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        //     $html2pdf->writeHTML($content);
        //     return $html2pdf->output('my_doc.pdf', 'S');
        // } catch (Html2PdfException $e) {
        //     $html2pdf->clean();
        
        //     $formatter = new ExceptionFormatter($e);
        //     echo $formatter->getHtmlMessage();
        // }
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
