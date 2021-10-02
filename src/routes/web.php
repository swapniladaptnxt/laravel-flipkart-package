<?php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return view('welcome');
});

Route::get('contact',function(){
    return "hii";
});
Route::get('/order/info', [App\Http\Controllers\Api\OrderController::class, 'Info']);


Route::get('/cache', function () {
    // return "hii";
    return Cache::get('key');
});


// Route::get('/users', function () {
//     return Response::json([
//         "hwllo"
//     ]);
// });