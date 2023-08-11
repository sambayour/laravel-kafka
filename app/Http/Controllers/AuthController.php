<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "first_name" => ['string'],
                "last_name" => ['string'],
                "password" => ['string'],
                "email" => ['required', 'email:dns,rfc', 'unique:users'],

            ]);

            if ($validator->fails()) {
                return response([
                    "status" => 'failed',
                    "success" => false,
                    "message" => $validator->errors()->all(),
                ], 422);
            }

            $user = User::create($request->all());

            return response([
                "token" => $user->createToken($request->email)->plainTextToken,
                "data" => User::find($user->id),
                "status" => 'ok',
                "success" => true,
                "message" => "Registration Successful",
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }

    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required_without:phone', 'email'],
            'phone' => ['string'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response([
                "status" => 'failed',
                "success" => false,
                "message" => $validator->errors()->all(),
            ], 422);
        }

        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = request([$field, 'password']);

        if (!Auth::attempt($credentials)) {
            return response([
                "status" => 'failed',
                "success" => false,
                "message" => "Unauthorized",
            ], 401);
        }

        $user = auth()->user();

        return response([
            "token" => $user->createToken($request->email)->plainTextToken,
            "status" => 'ok',
            "success" => true,
            "message" => "Logged in Successfully",
        ], Response::HTTP_OK);
    }
}
