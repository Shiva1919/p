<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\OCF;
use App\Models\API\OCFCustomer;
use App\Models\API\OCFModule;
use Illuminate\Http\Request;

class OCFModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexs($packageid)
    {
        $ocfmodule = OCFModule::where('ocfcode', $packageid)->first();
        $ocf = OCF::where('id', $ocfmodule->ocfcode)->first();
        $customer = OCFCustomer::where('id', $ocf->customercode)->first();
        return response()->json($customer);
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
            'ocfcode' => '',
            'modulecode' => '',
            'modulename' => '',
            'quantity' => '',
            'unit' => '',
            'expirydate' => '',
            'amount' => '',
            'total' => '',
        ]);
        $insert_ocfmodule = new OCFModule();
        $insert_ocfmodule->ocfcode = $request->ocfcode;
        $insert_ocfmodule->modulecode = $request->modulecode;
        $insert_ocfmodule->modulename = $request->modulename;
        // $insert_customers->address2 = $request->address2;
        $insert_ocfmodule->quantity = $request->quantity;
        $insert_ocfmodule->unit = $request->unit;
        $insert_ocfmodule->expirydate = $request->expirydate;
        $insert_ocfmodule->amount = $request->amount;
        $insert_ocfmodule->total = $request->total;
        $insert_ocfmodule->save();
        return response()->json([$insert_ocfmodule]);
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

    public function getocfmodalno($ocfcod)
    {
         $data = OCFModule::where('modulecode', $ocfcod)->get();
        return $data;
    }
}
