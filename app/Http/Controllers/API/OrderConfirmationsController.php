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
            'salestype'  => 'required',
            'eefOcfnocode' => '',
            'ocfno' => 'required',
            'initialusercount' => 'required|numeric',
            'fromdate' => 'date_format:d/m/Y',
            'todate' => 'date_format:d/m/Y',
            'validityperiodofinitialusers' => 'required',
            'customercode' => 'required',
            'concernperson' => 'required',
            'branchcode' => 'required',
            'packagetype'  => 'required',
            'packagesubtype' => 'required',
            'narration'  => 'required'
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
        
   
        $order_confirmation = OrderConfirmations::create($request->all());
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
        $ocf = orderConfirmations::where('id', $id)->first();

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
        $input = $request->all();
        $validator = Validator::make($input, [
            'salestype'  => 'required',
            'eefocfnocode' => 'required',
            'ocfno' => 'required',
            'initialusercount' => 'required|numeric',
            'fromdate' => 'date_format:d/m/Y',
            'todate' => 'date_format:d/m/Y',
            'validityperiodofinitialusers' => 'required',
            'customercode' => 'required',
            'concernperson' => 'required',
            'branchcode' => 'required',
            'packagetype'  => 'required',
            'packagesubtype' => 'required',
            'narration'  => 'required'
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $orderConfirmations=new orderConfirmations;
        $orderConfirmations->salestype = $input['salestype'];
        $orderConfirmations->eefocfnocode = $input['eefocfnocode'];
        $orderConfirmations->ocfno = $input['ocfno'];
        $orderConfirmations->initialusercount = $input['initialusercount'];
        $orderConfirmations->fromdate = $input['fromdate'];
        $orderConfirmations->todate = $input['todate'];
        $orderConfirmations->validityperiodofinitialusers = $input['validityperiodofinitialusers'];
        $orderConfirmations->customercode = $input['customercode'];
        $orderConfirmations->concernperson = $input['concernperson'];
        $orderConfirmations->branchcode = $input['branchcode'];
        $orderConfirmations->packagetype = $input['packagetype'];
        $orderConfirmations->packagesubtype = $input['packagesubtype'];
        $orderConfirmations->narration = $input['narration'];
        $orderConfirmations->save();
        return response()->json([$orderConfirmations]);

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
