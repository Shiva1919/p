<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $modules = Module::where('ProductType',$request->id)->first();
        if($modules == null)
        {
            return view('master.package.module.create', compact('modules', 'request') );
        }
        $module = Module::where('ProductType',$request->id)->get();
        return view('master.package.module.index',compact('modules', 'module'))->with('i', 1);
    }

    public function create(Request $request)
    {
        return view('master.package.module.create', compact('request'));
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'ProductType' => 'required',
            'ProductName' => 'required|string',
            'ProductDescription' => 'required',
        ]);
        Module::create($request->all());
        return redirect()->route('module.index', ['id' => $request->ProductType])
                        ->with('success','Module created successfully.');
    }

    public function show($id)
    {
        //
    }

    public function edit(Module $module)
    {
        return view('master.package.module.edit',compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        $request->validate([
            'ProductName' => 'required|string',
            'ProductDescription' => 'required',
        ]);
        $module->update($request->all());
        return redirect()->route('module.index', ['id' => $request->ProductType])
                        ->with('success','Module updated successfully');
    }

    public function destroy(Module $module)
    {
        $module->delete();
        return redirect()->route('module.index', ['id' => $module->ProductType])
                        ->with('success','Module deleted successfully');
    }
}