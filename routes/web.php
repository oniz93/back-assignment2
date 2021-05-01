<?php

use Illuminate\Support\Facades\Route;
use AvtoDev\JsonRpc\RpcRouter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/rpc', 'AvtoDev\\JsonRpc\\Http\\Controllers\\RpcController');

RpcRouter::on('SearchNearestPharmacy', 'App\\Http\\Controllers\\SearchController@getNearestPharmacy');
