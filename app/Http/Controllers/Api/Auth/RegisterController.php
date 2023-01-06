<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:api', ['except' => ['login', 'register','sellerEmailVerify']]);
        $this->middleware('guest');
    }

    public function userRegister(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|confirmed|min:8',
            'phone'=> ['required','regex:/^[0-9]{10}/','max:10']
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    [
                        'password' => Hash::make($request->password),
                        'role_id' => 3
                    ]
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function sellerEmailVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users,email,NULL,id,deleted_at,NULL',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $email = Crypt::encryptString($request->email);

        $mail = Mail::send('emails.sellerEmailVerification', 
        [
            'email' => $email,
            'url' => env('UI_DOMAIN'),
        ],
        function($message) use($request){
            $message->to($request->email);
            $message->subject('Email Verification Mail');
        });

        if($mail){
            return response()->json([
                'message' => 'Mail successfully sented',
                'email' => $email
            ], 201);
        }else{
            return response()->json([
                'error' => 'mail not send'
            ], 400);
        }
    }

    public function sellerRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $addseller = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => 2,
            'status' => 'inactive',
            'email_verified_at' => now()
        ]);

        return response()->json([
            'message' => 'Seller successfully registered',
            'seller' => $addseller
        ], 201);
    }
}
