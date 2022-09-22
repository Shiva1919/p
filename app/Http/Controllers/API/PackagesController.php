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
        $package = Packages::all();
        return response()->json($package);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
<<<<<<< HEAD

=======
        
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
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
        ]);
        $insert_package = Packages::create($request->all());
<<<<<<< HEAD
        return response()->json($insert_package);
=======
        return response()->json([$insert_package]);
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
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
<<<<<<< HEAD
        if (is_null($getbyid_package))
=======
        if (is_null($getbyid_package)) 
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
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
        ]);
        if($validator->fails())
        {
<<<<<<< HEAD
            return $this->sendError('Validation Error.', $validator->errors());
=======
            return $this->sendError('Validation Error.', $validator->errors());       
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
        }
        $package->name = $input['name'];
        $package->description = $input['description'];
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

<<<<<<< HEAD
    public function subpackageindex($packageid)
=======
    public function subpackageindex($packageid) 
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
    {
        $subpackage = SubPackages::where('packagetype', $packageid)->get();
        return response()->json($subpackage);
    }

<<<<<<< HEAD
    public function subpackageshow($packageid, $id)
=======
    public function subpackageshow($packageid, $id) 
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
    {
        $subpackage = SubPackages::where('packagetype', $packageid)->find($id);
        return response()->json($subpackage);
    }

    public function subpackagestore(Request $request, $packageid)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            // 'packagetype' => 'required'
        ]);

        $subpackage = new Subpackages();
        $subpackage->name = $request->name;
        $subpackage->description = $request->description;
        $subpackage->packagetype =$packageid;
        $subpackage->save();
        return response()->json([$subpackage]);
    }

    public function subpackageupdate(Request $request, $packageid, $id)
    {
        $subpackage = SubPackages::where('packagetype', $packageid)->where('id', $id)->first();
        $subpackagedata = [
            'name' => $request->name,
            'description' => $request->description
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
        $module = Modules::where('producttype', $packageid)->get();
        return response()->json($module);
    }

<<<<<<< HEAD
    public function moduleshow($packageid, $id)
=======
    public function moduleshow($packageid, $id) 
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
    {
        $module = Modules::where('producttype', $packageid)->find($id);
        return response()->json($module);
    }

    public function modulestore(Request $request, $packageid)
    {
        $request->validate([
<<<<<<< HEAD
            'productcode' => 'required',
=======
            'productcode' => '',
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
            'name' => 'required',
            'description' => 'required',
            // 'producttype' => 'required'
        ]);

        $module = new Modules();
        $module->productcode = $request->productcode;
        $module->name = $request->name;
        $module->description = $request->description;
        $module->producttype =$packageid;
        $module->save();
        return response()->json([$module]);
    }

<<<<<<< HEAD
    public function moduleupdate(Request $request, $packageid, $id)
=======
    public function moduleupdate(Request $request, $packageid, $id) 
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
    {
        $module = Modules::where('producttype', $packageid)->where('id', $id)->first();
        $moduledata = [
            'productcode' => $request->productcode,
            'name' => $request->name,
            'description' => $request->description
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

<<<<<<< HEAD
=======
    

>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
}
