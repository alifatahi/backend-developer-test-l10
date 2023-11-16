<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function create(Request $request): array|null
    {
        // attemp to login
        $credentials = [
            'email'=> $request->email,
            'password'=> $request->password,
        ];
        if (!Auth::attempt($credentials)) {
            abort(403);
        }
        // get the user after login attempt
        $user = Auth::user();
        // create the
        $token = $user->createToken('test01');
        return ['token' => $token->plainTextToken];
    }
}
