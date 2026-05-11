<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| USSD routes without the /api prefix (see RouteServiceProvider::mapUssdRoutes)
|--------------------------------------------------------------------------
|
| routes/ussd.php is loaded with prefix "api" → /api/ussd/africastalking (preferred).
| This file is loaded with NO prefix → /ussd/africastalking and POST / only here.
|
| If Africa's Talking is configured with callback = site root (no path), their
| servers POST to "/". Prefer configuring AT with /api/ussd/africastalking instead.
|
| Same handler: App\Http\Controllers\Ussd\AfricasTalkingUssdController@handle
|--------------------------------------------------------------------------
*/
Route::post('/', 'Ussd\AfricasTalkingUssdController@handle');

Route::match(['get', 'post'], 'ussd/africastalking', 'Ussd\AfricasTalkingUssdController@handle');