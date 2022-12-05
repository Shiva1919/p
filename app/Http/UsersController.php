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
            $user->name= $request['name'];
            $user->last_name= $request['last_name'];
            $user->email= $request['email'];
            $user->phone= $request['phone'];
            $user->password= Hash::make($request['password']);
            $user->active= $request['active'];
            $user->role_id= $request['role_id'];
            $user->permission_id= $request['permission_id'];
        $user->save();
        return response()->json([$user]);


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
        $input = $request->all();
        $validator = Validator::make($input, [
            // 'tenantcode' =>'',
            'name' => 'required',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'password' => '',
            'active' => '',
            'role_id' => '',
            'permission_id' => '',
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        // $user->tenantcode = $input['tenantcode'];
        $user->name = $input['name'];
        $user->last_name = $input['last_name'];
        $user->email = $input['email'];
        $user->phone = $input['phone'];
        $user->active = $input['active'];
        $user->password = Hash::make($input['password']);
        $user->role_id = $input['role_id'];
        $user->permission_id = $input['permission_id'];
        $user->save();
        // return $user;
        return response()->json($user);
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
