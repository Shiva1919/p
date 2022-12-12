<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Users::leftjoin('roles', 'users.role_id', '=', 'roles.id')->where('role_id', '!=', 10)
                        ->get( ['roles.name as rolename','users.*']);
        // $user = Users::where('active', 1)->orderBy('name', 'asc')->get();
        return $user;
    }

    public function getuserlogin(Request $request)
    {
        session_start();
        $data =  $_SESSION;
        return $data;
    }

    public function deactiveuserslist()
    {
        $user = Users::where('active', 0)->orderBy('name', 'asc')->where('role_id', '!=', 10)->get();
        return $user;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function duplicate_usermail($email){
        $dublicate = Users::where('email',$email)->first();
        if($dublicate){
            return response()->json([
                'status'=>1,
                'message'=>'This Mail Allready Available in Database',

            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(),[
            //put fields to be validated here
            ]);


        $user= new Users();
        $user->name = $request['fristname'];
        $user->last_name = $request['lastname'];
        $user->email = $request['email'];
        $user->phone = $request['mobile'];
        $user->active = $request['active'];
        $user->password = Hash::make($request['password']);
        $user->rowpassword= $request['password'];
        $user->role_id = $request['role_id'];
        $user->permission_id = $request['module'];
        $user->save();
            if ($user->id) {
                return response()->json([
                    'status'=>1,
                    'message'=>'User Added Successfully',
                    'data'=>$user
                ]);
            }
            else{
                return response()->json([
                    'status'=>0,
                    'message'=>'Something error'
                ]);

            }




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Users::find($id);
        if (is_null($user))
        {
            return $this->sendError('User not found.');
        }
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Users $user)
    {
        $request->all();


        $user->name = $request['fristname'];
        $user->last_name = $request['lastname'];
        $user->email = $request['email'];
        $user->phone = $request['mobile'];
        $user->active = $request['active'];
        if (!empty($request['password']) ) {
           $user->password = Hash::make($request['password']);
           $user->rowpassword= $request['password'];
         }
        $user->role_id = $request['role'];
        $user->permission_id = $request['module'];
        $user->save();
        // return $user;
        return response()->json([
            'status'=>200,
            'message'=>'User Updated Successfully',
            'data'=>$user
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getcustomer()
    {
        $getcustomer = Users::where('role_id', 10)->get();
        return response()->json($getcustomer);
    }

    public function customerlogin($tenantcode, $password, $token)
    {
        echo "login successfully";
    }

    public function userstatus($id, $active)
    {
        try
        {
            $update_user = Users::where('id', $id)->update([
                'active' => $active
            ]);
            if($update_user)
            {
                return response()->json(['message'=>'User Updated Successfully'], 200);
            }
            else
            {
                return response()->json(['message'=>'User Updated Unsuccessfully'], 404);
            }
        }
        catch (\Throwable $th)
        {
            throw $th;
        }
    }

    public function activeuser()
    {
        $users = Users::all()->where('active', 1);
        return response()->json(['users' => $users], 200);
    }

    public function deactiveuser()
    {
        $users = Users::all()->where('active', 0);
        return response()->json(['users' => $users], 200);
    }

    public function gettenant($tenant)
    {
        $user = Users::where('tenantcode', $tenant)->first();
        return response()->json(['users' => $user], 200);
    }

}
