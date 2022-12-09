<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $msg = "Wrong Informations!";
        $success = false;
        $data = [];
        $errors = [];
        $http_code = 200;
        $input = $request->all();
        //validator
        $validator = Validator::make($input, [
            "name" => "required",
            "email" => "required|email|unique:App\Models\User,email",
            "password" => "required",
            "repass" => "required|same:password"
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $http_code = 400;
        } else {
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $data = [
                'token' => $user->createToken('MyApp')->plainTextToken,
                'name' => $user->name
            ];
            $msg = "Register Success!";
            $success = true;
        }

        $response = [
            'msg' => $msg,
            'success' => $success,
            'data' => $data,
            'errors' => $errors
        ];

        return response()->json($response, $http_code);
    }

    public function login(Request $request)
    {
        $msg = "Wrong Login Credentials";
        $success = false;
        $data = [];
        $http_code = 400;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            //$user = Auth::user();
            $user = $request->user();
            $data = [
                'token' => $user->createToken('MyApp')->plainTextToken,
                'name' => $user->name
            ];
            $success = true;
            $http_code = 200;
            $msg = "Login Successful. Redirecting..";
        }
        $response = [
            'msg' => $msg,
            'success' => $success,
            'data' => $data,
        ];
        return response()->json($response, $http_code);
    }
}
