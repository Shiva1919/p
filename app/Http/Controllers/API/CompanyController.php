<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::leftjoin('users', 'company_master.customercode', '=', 'users.id')->get( ['users.name as companyname','company_master.*']);
        return response()->json($company);
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
        $request->validate([
            'customercode' => 'required',
            'company_name' => 'required',
            'pan_no' => '',
            'gst_no' => ''
        ]);
        $insert_package = Company::create($request->all());

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
        $company = Company::where('customercode', $id)->get();
        return response()->json($company);
    }

    public function customer_wise_company($id)
    {
        $companys = Company::where('customercode', $id)->get();
        return response()->json($companys);
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'customercode' => 'required',
                'company_name'=> 'required',
                'pan_no' => '',
                'gst_no' => ''
            ]);
            $company = Company::find($id);
            $company->customercode = $request->customercode;
            $company->company_name = $request->company_name;
            $company->pan_no = $request->pan_no;
            $company->gst_no = $request->gst_no;
            $company->save();
        return response()->json([$company]);
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

    public function getcompanyID($id)
    {
        $company = Company::where('id', $id)->get();
        return response()->json($company);
    }
}
