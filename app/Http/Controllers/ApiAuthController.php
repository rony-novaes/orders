<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiAuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ApiAuthController extends Controller
{
    public function login(ApiAuthRequest $request)
    {
        if (Auth::attempt($request->only(["email", "password"]))) {
            $user = Auth::user();
            $token = $user->createToken("DYSYS");
            return Response::json(["token"=>$token->plainTextToken], HttpFoundationResponse::HTTP_OK);
        }        
        return Response::json(["error"=>"unauthorized"], HttpFoundationResponse::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        Auth::user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logout successfully'], HttpFoundationResponse::HTTP_UNAUTHORIZED);
}
}
