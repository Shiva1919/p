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
<<<<<<< HEAD
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
=======
            'salestype'  => '',
            'eefOcfnocode' => '',
            'ocfno' => '',
            'initialusercount' => '',
            'fromdate' => 'date_format:d/m/Y',
            'todate' => 'date_format:d/m/Y',
            'validityperiodofinitialusers' => '',
            'customercode' => '',
            'concernperson' => '',
            'branchcode' => '',
            'packagetype'  => '',
            'packagesubtype' => '',
            'narration'  => ''
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
        ]);    
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
    public function update(Request $request, OrderConfirmations $orderConfirmations)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
<<<<<<< HEAD
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
=======
            'salestype'  => '',
            'eefocfnocode' => '',
            'ocfno' => '',
            'initialusercount' => '',
            'fromdate' => 'date_format:d/m/Y',
            'todate' => 'date_format:d/m/Y',
            'validityperiodofinitialusers' => '',
            'customercode' => '',
            'concernperson' => '',
            'branchcode' => '',
            'packagetype'  => '',
            'packagesubtype' => '',
            'narration'  => ''
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());       
        }
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
}
