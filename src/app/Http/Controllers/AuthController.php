<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "fname" => ["required","string"],
            "lname" => ["required","string"],
            "email" => ["required","string","email","unique:users,email"],
            "password" => ["required","string","min:8"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "err" => true,
                'msg' => $validator->messages()->get('*')
            ]);
        }

        $result = User::create([
            "fname" => $request->fname,
            "lname" => $request->lname,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);
        if(!$result){
            return response()->json([
                "err" => true,
                'msg' => 'can not register user'
            ]);
        }
        return response()->json([
            "err" => false,
            'msg' => 'success'
        ]);
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
