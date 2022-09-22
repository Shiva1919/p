<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\OrderConfirmations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderConfirmationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order_confirmation = OrderConfirmations::all();
        return response()->json($order_confirmation);
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
             'salestype'  => '',
            'eefOcfnocode' => '',
             'ocfno' => '',
            'initialusercount' => '',
            'fromdate' => '',
            'todate' => '',
            'purchasedate' => '',
            'validityperiodofinitialusers' => '',
            'customercode' => 'required',
            'concernperson' => 'required',
            'branchcode' => 'required',
            'packagetype'  => 'required',
            'packagesubtype' => 'required',
            'moduleid' => '',
            'narration'  => ''
        ]);  
       
        $order_confirmation = new OrderConfirmations();  
        $order_confirmation->salestype =  $request->get('salestype');  
        $order_confirmation->eefOcfnocode = $request->get('eefOcfnocode');  
        $order_confirmation->ocfno = $request->get('ocfno');  
        $order_confirmation->initialusercount = $request->get('initialusercount'); 
        $order_confirmation->fromdate = $request->get('fromdate');  
        $order_confirmation->todate = $request->get('todate');  
        $order_confirmation->purchasedate = $request->get('purchasedate');  
        $order_confirmation->validityperiodofinitialusers = $request->get('validityperiodofinitialusers');   
        $order_confirmation->customercode = $request->get('customercode');  
        $order_confirmation->concernperson = $request->get('concernperson');  
        $order_confirmation->branchcode = $request->get('branchcode');  
        $order_confirmation->packagetype = $request->get('packagetype');  
        $order_confirmation->packagesubtype = $request->get('packagesubtype');  
        $order_confirmation->moduleid = $request->get('moduleid');  
        $order_confirmation->narration = $request->get('narration');
        $order_confirmation->save();  
        
        return response()->json($order_confirmation);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getbyid = OrderConfirmations::find($id);
        if (is_null($getbyid)) 
        {
            return $this->sendError('Package not found.');
        }
        return response()->json($getbyid);
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
    public function update(Request $request,$id)
    {
        return $id;
        $ocf = OrderConfirmations::where('id', $id)->first();

        $orderconfirmationdata = [
            'salestype'  => $request->salestype,
            'eefocfnocode' => $request->eefocfnocode,
            'ocfno' =>  $request->ocfno,
            'initialusercount' => $request->initialusercount,
            'fromdate' =>  $request->fromdate,
            'todate' =>  $request->todate,
            'purchasedate' => $request->purchasedate,
            'validityperiodofinitialusers' => $request->validityperiodofinitialusers,
            'customercode' =>  $request->customercode,
            'concernperson' =>  $request->concernperson,
            'branchcode' => $request->branchcode,
            'packagetype'  => $request->packagetype,
            'packagesubtype' =>  $request->packagesubtype,
            'moduleid' =>  $request->moduleid,
            'narration'  =>  $request->narration
        ];
       
        $update_ocf = $ocf->update($orderconfirmationdata);
        return response()->json(['response' =>$update_ocf, 'status' => "success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $orderConfirmations = OrderConfirmations::where('id', $id)->delete();
        return response()->json([$orderConfirmations]);
    }

    public function getrefno($refno)
    {
        $data = OrderConfirmations::where('ocfno', $refno)->get();
        return $data;
    }
}
