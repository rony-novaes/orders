<?php

use App\Http\Controllers\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

Route::post("/login", [ApiAuthController::class, "login"]);
Route::post("/login/create", [\App\Http\Controllers\CustomerController::class, "store"]);


Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name); 
    return ['token' => $token->plainTextToken];
});


Route::get("/auth", function (Request $request) { 
    return Response::json(["error" => "not-logged", "sucess" => false], HttpFoundationResponse::HTTP_UNAUTHORIZED);
})->name("login");

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post("/logout", [ApiAuthController::class, "logout"]);
    Route::apiResource("customer", \App\Http\Controllers\CustomerController::class);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
