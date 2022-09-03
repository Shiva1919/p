<?php

namespace App\Http\Controllers;

use App\Models\SubPackage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SubPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subpackages = SubPackage::where('packagetype',$request->id)->first();
        if($subpackages == null)
        {
            return view('master.package.subpackage.create', compact('subpackages', 'request') );
        }
        $subpackage = SubPackage::where('packagetype',$request->id)->get();
        return view('master.package.subpackage.index',compact('subpackages', 'subpackage'))->with('i', 1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('master.package.subpackage.create', compact('request'));
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
        SubPackage::create($request->all());
        return redirect()->route('subpackage.index', ['id' => $request->packagetype])
                        ->with('success','Sub Package created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SubPackage $subpackage)
    {
        return view('master.package.subpackage.edit',compact('subpackage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
       DB::table('acme_package_subtype_master')
              ->where('Owncode',$id)
              ->update(['name' => $request->name,'description'=> $request->description]);
      
        
        return redirect()->route('subpackage.index', ['id' => $request->packagetype])
                        ->with('success','Sub Package updated successfully');
    }

    public function destroy(SubPackage $subpackage)
    {
         DB::table('acme_package_subtype_master')->where('owncode',$subpackage->owncode)->delete();
        return redirect()->route('subpackage.index', ['id' => $subpackage->packagetype])
                        ->with('success','Sub Package deleted successfully');
    }
}
