<?php
if (App::environment('production')) {
    URL::forceScheme('https');
}
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->group(function(){
    Route::get('/', function () {
        return response()->json(['status'=>true,'mensaje'=>'Bienvenido a el servidor'],200);
    });
    Route::post('login', 'Api\AuthController@login');
    Route::post('register', 'Api\AuthController@register');
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('user', 'Api\AuthController@user');
        Route::resource('usuario/{id}/domain','DomainController', ['only'=>['show','store','update','destroy','index']]);
        Route::resource('usuario/{id}/domain/{domain_id}/infection','InfectionController', ['only'=>['show','store','update','destroy','index']]);
        Route::resource('usuario/{id}/domain/{domain_id}/black_list','BlackListController', ['only'=>['show','store','update','destroy','index']]);
        Route::get('usuario/{id}/only_infections', 'InfectionController@onlyInfections');
        Route::resource('usuario/{id}/domain/{domain_id}/action_taken','ActionTakenController', ['only'=>['store','index']]);
        Route::resource('usuario/{id}/domain/{domain_id}/action_taken_domain','ActionTakenDomainController', ['only'=>['store']]);
        Route::post('usuario/{id}/domain_file', 'DomainController@createByFile');
        Route::resource('usuario/{id}/report','ReportController', ['only'=>['index']]);
        Route::resource('canvas','CanvasController', ['only'=>['store','show','update']]);
        Route::resource('variables','VariablesController', ['only'=>['store','index','update']]);
        // Route::resource('domain/{id}/action_infection','InfectionController', ['only'=>['show','store','update','destroy','index']]);
        Route::put('usuario/{id}/config', 'ConfigController@update');
        Route::resource('user_admin','UserController', ['only'=>['show','store','update','destroy','index']]); // Usuarios controller
        Route::post('usuario/{id}/change_password', 'UserController@changePassword');
        Route::get('search', 'SearchController@index');
    });
});
