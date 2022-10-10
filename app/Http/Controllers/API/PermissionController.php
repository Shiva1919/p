<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::where('active', 1)->get();
        return response()->json( $permissions);
    }

    public function deactivepermissionslist()
    {
        $permission = Permission::where('active', 0)->orderBy('name', 'asc')->get();
        return $permission;
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
        $validator = Validator::make($input, [
            'name' => 'required',
            'active' => '',
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $permission = Permission::create($input);
        return response()->json($permission);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::find($id);
        if (is_null($permission)) 
        {
            return $this->sendError('Permission not found.');
        }
        return response()->json($permission);
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
    public function update(Request $request, Permission $permission)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'active' => '',
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $permission->name = $input['name'];
        $permission->active = $input['active'];
        $permission->save();
        return response()->json($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json($permission);
    }

    public function permissionstatus($id, $active)
    {
        try 
        {
            $update_permission = Permission::where('id', $id)->update([
                'active' => $active
            ]);   
            if($update_permission)
            {
                return response()->json(['message'=>'Permission Updated Successfully'], 200); 
            } 
            else
            {
                return response()->json(['message'=>'Permission Updated Unsuccessfully'], 404); 
            }
        } 
        catch (\Throwable $th) 
        {
            throw $th;
        }
    }

    public function activepermission()
    {
        $permission = Permission::all()->where('active', 1);
        return response()->json(['message' => $permission], 200);
    }

    public function deactivepermission()
    {
        $permission = Permission::all()->where('active', 0);
        return response()->json(['message' => $permission], 200);
    }
}
