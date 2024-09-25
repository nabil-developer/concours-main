<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\mainController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/enregistrement', [mainController::class, 'addCondidats']);
// Route::post('/valider', [mainController::class, 'validerCondidat']);
// Route::get('/nextCondidat', [mainController::class, 'nextCondidat']);

// Route::get('/send-test-mail', function () {
//     // Mail::raw('This is a test email.', function ($message) {
//     //     $message->to('elmeghits.zakarya@gmail.com')
//     //             ->subject('Test Email');
//     // });

//     Mail::to('elrya@gmail.com')->queue(new SuccessEmail("data"));

//     return 'Test email sent!';
// });

Route::get('/data' , [mainController::class, 'getData']);
Route::get('/wilayas', [mainController::class, 'getWilayas']);
Route::get('/communes', [mainController::class, 'getCommunes']);
Route::get('/situations', [mainController::class, 'getSituations']);
Route::get('/typediplomes', [mainController::class, 'getTypeDiplomes']);
Route::get('/specialitesdiplome', [mainController::class, 'getSpecialitesDiplome']);
Route::get('/universites', [mainController::class, 'getUniversites']);
Route::get('/centresExamen', [mainController::class, 'getCentresExamen']);
Route::get('/formationautorisees', [mainController::class, 'getFormationAutorisees']);


// Route::get('/condidats', [mainController::class, 'getCondidats']);


