<?php
Route::get('/', function () {
    return view('welcome');
});

Route::get('contact',function(){
    return "hii";
});
Route::get('/order/info', [App\Http\Controllers\Api\OrderController::class, 'index']);
