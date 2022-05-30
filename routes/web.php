<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;

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



Route::get("certificate", [CertificateController::class, 'index'])->name("certificate.main");
Route::get("certificate/{certificate}", [CertificateController::class, 'show'])->name("certificate-detail");
Route::post("certificate", [CertificateController::class, 'store']);

Route::post("certificate/{certificate}/domain", [CertificateController::class, 'addDomains'])->name("certificate-add-domain");
