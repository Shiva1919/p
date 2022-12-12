<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::where('active', 1)->orderBy('name', 'asc')->get();
        return response()->json( $roles);
    }

    public function rolesgetexcept()
    {
        $rolesdata = Role::where('active', 1)->where('id', '!=' ,10)->get();
        return $rolesdata;
    }

    public function deactiverolesslist()
    {
        $role = Role::where('active', 0)->orderBy('name', 'asc')->get();
        return $role;
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

        $input = $request->all();
        // $validator = Validator::make($input, [
        //     'name' => 'required',
        //     'permission_id'=> '',
        // ]);
        // if($validator->fails())
        // {
        //     return $this->sendError('Validation Error.', $validator->errors());
        // }
        $role = Role::create($input);

        return response()->json([
            'status'=>200,
            'message'=>'Role Added Successfully',
            'data'=>$role
        ]);
        // return response()->json($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        if (is_null($role))
        {
            return $this->sendError('Role not found.');
        }
        return response()->json( $role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        // return $request->permission_id;
        $input = $request->all();
        $role->name = $input['name'];
        $role->permission_id = $input['permission_id'];
        $role->active = $input['active'];
        $role->save();
        return response()->json([
            'status'=>200,
            'message'=>'Role Updated Successfully',
            'data'=>$role
        ]);
        // return response()->json($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json($role);
    }

    public function rolestatus($id, $active)
    {
        try
        {
            $update_role = Role::where('id', $id)->update([
                'active' => $active
            ]);
            if($update_role)
            {
                return response()->json(['message'=>'Role Updated Successfully'], 200);
            }
            else
            {
                return response()->json(['message'=>'Role Updated Unsuccessfully'], 404);
            }
        }
        catch (\Throwable $th)
        {
            throw $th;
        }
    }

    public function activerole()
    {
        $role = Role::where('active', 1)->get();
        return $role;
        return response()->json(['message' => $role], 200);
    }

    public function deactiverole()
    {
        $role = Role::all()->where('active', 0);
        return response()->json(['message' => $role], 200);
    }
}
