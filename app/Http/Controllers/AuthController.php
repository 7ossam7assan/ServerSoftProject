<?php

namespace App\Http\Controllers;

use App\Lock;
use App\User;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
        'remember_me' => 'boolean',

    ]);

    $credentials = request(['email', 'password','num']);
    $user=User::where('email',$request->get('email'))->first();
//    dd($user);
    $locks=Lock::where('num',$request->get('num'))->get();
    foreach ($locks as $lock){
        if ($lock->user_id == $user->id){
            $id=$lock->user_id;
            break;
        }
    }
    if(! isset($id)){
        return response()->json(['message' => 'lock  num incorrect','status' => 401],401);
    }
    $user=User::where('email',$request->get('email'))->where('password',$request->get('password'))->where('id',$id)->first();
//    dd($user);
//    if(!Auth::attempt(['email'=>$request->get('email'),'password' => $request->get('password'),'id'=>$id]))
       if(empty($user))
        return response()->json([
            'status' => 'Fail',

            'message' => 'Invalid Email Or Password',

        ], 401);

//    $user = $request->user();

    $tokenResult = $user->createToken('Personal Access Token');
    $token = $tokenResult->token;

    if ($request->remember_me)
        $token->expires_at = Carbon::now()->addWeeks(8);

    $token->save();

    return response()->json([
        'status' => 'success',
        'access_token' => $tokenResult->accessToken,
        'token_type' => 'Bearer',
        'expires_at' => Carbon::parse(
            $tokenResult->token->expires_at
        )->toDateTimeString()
    ]);
}

    private function getUserToken(User $user)
    {
        return $user->createToken('dddd')->accessToken;
    }
}
