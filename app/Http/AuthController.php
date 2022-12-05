<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\OCFCustomer;
use App\Models\API\TokenData;
use App\Models\token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => '',
            'email' => '',
            'phone' => '',
            'role_id' => '',
            'password' => '',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'role_id' => $data['role_id'],
            'password' => Hash::make($data['password']),
        ]);

          $token = $user->createToken('SerialNoToken')->plainTextToken;

        $response = [
              'token' => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->where('active', 1)->first();

        if(!$user || !Hash::check($data['password'], $user->password))
        {
            return response(['message' => 'Invalid Credentials'], 401);
        }
        else
        {
              $token = $user->createToken('LoginSerialNoToken')->plainTextToken;

            $response = [
                 'token' => $token,
            ];
            return response($response, 200);
        }
    }

    public function getlogin(Request $request)
    {
        $getcustomer = User::where('role_id', 10)->get();
        //  return $getcustomer;
        $user = User::where('id', $request->companycode)->where('active', 1)->first();
        if(!$user || $request->password !=$user->password)
        {
            return response(['message' => 'Invalid Credentials', 'status' => '1']);
        }
        else
        {
            $token = $user->createToken('LoginSerialNoToken')->plainTextToken;

            $response = [

                 'token' => $token,
                 'status' => '0'
        ];

            //  return response($response, 200);
            return response()->json($response);
        }
    }

    public function getcustomerlogin($login, $token)
    {

        $user = DB::table('customer_master')->where('id', $login)->where('active', 1)->first();

        $checktoken = DB::table('personal_access_tokens')->where('token', $token)->first();

        $token = DB::table('personal_access_tokens')->where('created_at', '<', Carbon::now()->subMinutes(30))->delete();

        if ($checktoken == null ) {
            return response()->json(['message' => 'Invalid Login Credentials', 'status' => '1']);
        }
        if($user == null){
            return response()->json(['message' => 'Invalid Login Credentials', 'status' => '1']);

        }
       return response()->json(['message' => 'Login Successful', 'status' => '0', 'token' => $checktoken,'userData'=>$user]);
    }


    public function token(Request $request)
    {
        if (!Auth::attempt($request->only(['id', 'password']))) {
            abort(403);
        }
        $user = new User();

        $token = $user->createToken('Our Token');
        return response()->json([
            'token' => $token->plainTextToken,
            'expired_at' => $token->accessToken->expired_at
        ]);
    }

    public function gettoken()
    {
        $gettoken = TokenData::all();
        return $gettoken;
    }

    public function logout()
    {
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });
        return response(['message' => 'Logged Out Successfully']);
    }

}
