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
            "fname" => "required|string",
            "lname" => "required|string",
            "email" => "required|string|email",
            "password" => "required|string",
        ]);
 
        if ($validator->fails()) {
            return response()->json([
                'msg' => 'failed'
            ]);
        }

        $data = new User;
        $data->fname = $request->fname;
        $data->lname = $request->lname;
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $result = $data->save();
        if(!$result){
            return response()->json([
                'msg' => 'failed'
            ]);
        }
        return response()->json([
            'msg' => 'success'
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(["message" => "Successfully logged out"]);
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
