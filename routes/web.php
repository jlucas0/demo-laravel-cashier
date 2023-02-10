<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    Auth::loginUsingId(1);
    return view('welcome');
});

Route::get('/comprar', function(Request $request){
    return $request->user()->checkoutCharge(500, 'Demo', 1,[
        'success_url' => route('pago-bien').'?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('pago-mal')
    ]);
});

Route::get('/pago-bien',function(Request $request){
    $checkoutSession = $request->user()->stripe()->checkout->sessions->retrieve($request->get('session_id'));
    dd($checkoutSession);
    echo "<h2>Se ha efectuado el pago correctamente</h2>";
    echo "<p>Se te han cobrado ".($checkoutSession->amount_total/100)." ".$checkoutSession->currency."</p>";
})->name('pago-bien');
Route::get('/pago-mal',function(Request $request){
    return response("El pago ha fallado");
})->name('pago-mal');