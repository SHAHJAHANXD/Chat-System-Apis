<?php

use App\Http\Controllers\ChatController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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





Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('sendMessage');
    Route::get('/chat/{userId}', [ChatController::class, 'getMessages'])->name('getMessages');
});

Route::post('/register', [ChatController::class, 'Register'])->name('Register');

Route::post('/login', function (Request $request) {
    $credentials = $request->only(['email', 'password']);

    if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $user = User::where('email', $request->email)->first();
    $token = $user->createToken('mytoken')->plainTextToken;
    return response()->json([
        'token' => $token,
        'type' => 'bearer'
    ]);
});
