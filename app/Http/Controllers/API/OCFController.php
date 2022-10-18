<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\OCF;
use Illuminate\Http\Request;

class OCFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ocf = OCF::all();
        return response()->json([$ocf]);
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
            'customercode' => '',
            'companycode' => '',
            'ocfno' => '',
            'ocf_date' => '',
        ]);
        $insert_ocf = new OCF();
        // $insert_customers->tenantcode = $request->tenantcode;
        $insert_ocf->customercode = $request->customercode;
        $insert_ocf->companycode = $request->companycode;
        $insert_ocf->ocfno = $request->ocfno;
        $insert_ocf->ocf_date = $request->ocf_date;
        $insert_ocf->save();
        return response()->json([$insert_ocf ]);
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
        //
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
}
