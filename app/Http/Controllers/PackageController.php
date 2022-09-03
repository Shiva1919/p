<?php

namespace App\Http\Controllers;

use App\Models\API\Packages;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $package = Package::simplePaginate(10);
        // return $package;
        return view('master.package.index',compact('package'))->with('i', 1);
    }

    public function create()
    {
        return view('master.package.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required',
        ]);
        Package::create($request->all());
        return redirect()->route('package.index')
                        ->with('success','Package created successfully.');
    }

    public function show($id)
    {
       
    }

    public function edit(Package $package)
    {
        return view('master.package.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required',
        ]);
        $package->update($request->all());
        return redirect()->route('package.index')
                        ->with('success','Package updated successfully');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('package.index')
                        ->with('success','Package deleted successfully');
    }
}
