<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Modules;
use App\Models\API\Packages;
use App\Models\API\SubPackages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $package = Packages::where('active', 1)->orderBy('name', 'asc')->get();
        return response()->json($package);
    }

    public function deactivepackageslist()
    {
        $package = Packages::where('active', 0)->orderBy('name', 'asc')->get();
        return response()->json($package);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required',
            'active' => ''
        ]);
        $insert_package = Packages::create($request->all());
        return response()->json($insert_package);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getbyid_package = Packages::find($id);
        if (is_null($getbyid_package))
        {
            return $this->sendError('Package not found.');
        }
        return response()->json($getbyid_package);
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
    public function update(Request $request, Packages $package)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'description'=> 'required',
            'active' => ''
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $package->name = $input['name'];
        $package->description = $input['description'];
        $package->active = $input['active'];
        $package->save();
        return response()->json([$package]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Packages $package)
    {
        $package->delete();
        return response()->json([$package]);
    }

    public function subpackageindex($packageid)
    {
        $subpackage = SubPackages::where('packagetype', $packageid)->where('active', 1)->orderBy('name', 'asc')->get();
        return response()->json($subpackage);
    }
    
    public function deactivesubpackageslist($packageid) 
    {
        $subpackage = SubPackages::where('packagetype', $packageid)->where('active', 0)->orderBy('name', 'asc')->get();
        return response()->json($subpackage);
    }

    public function subpackageshow($packageid, $id)
    {
        $subpackage = SubPackages::where('packagetype', $packageid)->find($id);
        return response()->json($subpackage);
    }

    public function subpackagestore(Request $request, $packageid)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'active' => ''
            // 'packagetype' => 'required'
        ]);

        $subpackage = new Subpackages();
        $subpackage->name = $request->name;
        $subpackage->description = $request->description;
        $subpackage->active = $request->active;
        $subpackage->packagetype =$packageid;
        $subpackage->save();
        return response()->json([$subpackage]);
    }

    public function subpackageupdate(Request $request, $packageid, $id)
    {
        $subpackage = SubPackages::where('packagetype', $packageid)->where('id', $id)->first();
        $subpackagedata = [
            'name' => $request->name,
            'description' => $request->description,
            'active' => $request->active
        ];
        $update_subpackage = $subpackage->update($subpackagedata);
        return response()->json([$update_subpackage]);
    }

    public function subpackagedelete($packageid, $id)
    {
        $subpackagedata = SubPackages::where('packagetype', $packageid)->where('id', $id)->first();
        $delete_subpackage = $subpackagedata->delete();
        return response()->json([$delete_subpackage]);
    }

    public function moduleindex($packageid)
    {
        $module = Modules::where('producttype', $packageid)->where('active', 1)->orderBy('name', 'asc')->get();
        return response()->json($module);
    }
    
    public function deactivemoduleslist($packageid)
    {
        $module = Modules::where('producttype', $packageid)->where('active', 0)->orderBy('name', 'asc')->get();
        return response()->json($module);
    }

    public function moduleshow($packageid, $id)
    {
        $module = Modules::where('producttype', $packageid)->find($id);
        return response()->json($module);
    }

    public function modulestore(Request $request, $packageid)
    {
        $request->validate([
            'productcode' => 'required',
            'name' => 'required',
            'description' => 'required',
            'active' => ''
            // 'producttype' => 'required'
        ]);

        $module = new Modules();
        $module->productcode = $request->productcode;
        $module->name = $request->name;
        $module->description = $request->description;
        $module->active = $request->active;
        $module->producttype =$packageid;
        $module->save();
        return response()->json([$module]);
    }

    public function moduleupdate(Request $request, $packageid, $id)
    {
        $module = Modules::where('producttype', $packageid)->where('id', $id)->first();
        $moduledata = [
            'productcode' => $request->productcode,
            'name' => $request->name,
            'description' => $request->description,
            'active' => $request->active
        ];
        $update_module = $module->update($moduledata);
        return response()->json([$update_module]);
    }

    public function moduledelete($packageid, $id)
    {
        $moduleedata = Modules::where('producttype', $packageid)->where('id', $id)->first();
        $delete_module = $moduleedata->delete();
        return response()->json([$delete_module]);
    }

}
