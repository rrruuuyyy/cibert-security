<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    private $models = [
        'users' => 'App\User', // no de la table
        // 'shops' => 'App\shop',
        // 'products' => 'App\products',
        // 'orders' => 'App\orders',
        // 'purchase_orders' => 'App\purchase_order',
      ];
    
      private function checkModel($model_name=''){
        try{
          if(array_key_exists($model_name, $this->models)){ //users -> User 
              $model = $this->models[$model_name];
              return $model;
          }       
        }catch(Exepection $e){
          return null;
        }
      }
      public function index(Request $request)
      {
        $limit = ($request->limit) ? $request->limit : 15;
        $model = $this->checkModel($request->model);// User or Producto or ETC
        $searchFields = ($request->fields)? json_decode($request->fields) : null;
        //$filters = json_decode($request->model);
        //return response()->json($filters);
        $query = $model::query();
        $query->where(function($query) use($request, $searchFields){
            $searchWildcard = '%' . $request->search . '%';
            foreach($searchFields as $field){
            $query->orWhere($field, 'LIKE', $searchWildcard);
            }
        });
    //   switch ($request->model) {
    //       case 'shops':
    //           $query->with(['header','cover']);
    //           break;
    //       case 'products':
    //           $query->with(['product_attacheds.attached']);
    //           break;
    //       case 'orders':
    //           $query->with(['usuario','platform','purchase']);
    //           break;
    //       default:
    //           break;
    //   }
        // Busqueda de eliminados
        if($request->trash == true){
            $query->onlyTrashed();
        }
        $data = $query->paginate($limit);
        return response()->json(['status'=>true,'mensaje'=>'Busqueda realizada','data'=>$data],200);

      }
}
