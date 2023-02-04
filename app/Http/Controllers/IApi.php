<?php

namespace App\Http\Controllers;

use App\Models\acme_einvoice_subscriptions;
use App\Models\Apilog;
use Illuminate\Http\Request;


class IApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return acme_einvoice_subscriptions::orderBy('OwnCode','desc')->get();
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

        $data=date('Y-m-d');
        $api = new acme_einvoice_subscriptions;
        $api->CustomerName = $request->CustomerName;
        $api->Address = $request->Address;
        $api->StartDate = $data;
        $api->ExpiryDate =date('Y-m-d', strtotime($data. ' + 1 years'));
        $api->Gstin = $request->Gstin;
        $api->CreationDateTime = date('Y-m-d H:i:s');
        $api->PaymentReceived = $request->PaymentReceived;
         //api log
        // $apilog = new Apilog;
        // $apilog->GSTIN = $request->Gstin;
        // $apilog->apitype = "Post";
        // $apilog->apidata = $api;
        // $apilog->apiresponse = "['status'=>200,'message'=>'Added Successfully']";
        // $apilog->ApiTimeStamp=date('Y-m-d H:i:s');
// return $apilog;
        // $log=$api;
        $api->save();
        // $apilog->save();
        return response()->json([
            'status'=>200,
            'message'=>'Added Successfully'
        ]);



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
public function activation($owncode,$id){

    $data= acme_einvoice_subscriptions::find($owncode);
    if ($data) {
        if ($id) {
            $data->IsActive = 0;
        }
        else{
            $data->IsActive = 1;
        }


       $data->save();
        // $apilog = new Apilog;
        // $apilog->apitype = "GET";
        // $apilog->apidata = $data;
        // $apilog->apiresponse = "['status'=>200,'message'=>'Update Successfully']";
        // $apilog->ApiTimeStamp=date('Y-m-d H:i:s');
        // $apilog->save();
        return response()->json([
          'status'=>200,
          'message'=>'Update Successfully'
      ]);
    }
    else{
        // $apilog = new Apilog;
        // $apilog->apitype = "GET";
        // $apilog->apidata = "";
        // $apilog->apiresponse = "['status'=>404,'message'=>'data Not Found']";
        // $apilog->ApiTimeStamp=date('Y-m-d H:i:s');
        // $apilog->save();
        return response()->json([
            'status'=>404,
            'message'=>'data Not Found'
        ]);

    }
}
public function Payment($owncode,$id){

    $data= acme_einvoice_subscriptions::find($owncode);
    // return $data;

    if ($data) {
        if ($id==1) {
            $data->PaymentReceived = 0;
        }
        if ($id==0){

            $data->PaymentReceived = 1;
        }

      $data->save();
        // $apilog = new Apilog;
        //  $apilog->apitype = "GET";
        // $apilog->apidata = $data;
        // $apilog->apiresponse = "['status'=>200,'message'=>'Update Successfully']";
        // $apilog->ApiTimeStamp=date('Y-m-d H:i:s');
        // $apilog->save();
        return response()->json([
          'status'=>200,
          'message'=>'Update Successfully'
      ]);
    }
    else{
        // $apilog = new Apilog;
        // $apilog->GSTIN = "";
        // $apilog->apitype = "GET";
        // $apilog->apidata = "";
        // $apilog->apiresponse = "['status'=>404,'message'=>'data Not Found']";
        // $apilog->ApiTimeStamp=date('Y-m-d H:i:s');
        // $apilog->save();
        return response()->json([
            'status'=>404,
            'message'=>'data Not Found'
        ]);

    }
}

    public function update(Request $request, $id)
    {

        $data= acme_einvoice_subscriptions::find($id);
        if ($data) {
    //     $data->CustomerName = $request->CustomerName;
    //     $data->Address = $request->Address;
    //    $data->Gstin = $request->Gstin;
    //     $data->CreationDateTime = date('Y-m-d H:i:s');
        $data->IsActive = $request->IsActive;
        $data->PaymentReceived = $request->PaymentReceived;


            $data->save();
    //         $apilog = new Apilog;
    //         $apilog->GSTIN = $request->Gstin;
    //         $apilog->apitype = "Put";
    //         $apilog->apidata = $data;
    //         $apilog->apiresponse = "['status'=>200,'message'=>'Update Successfully']";
    //         $apilog->ApiTimeStamp=date('Y-m-d H:i:s');
    // // return $apilog;
    //         // $log=$api;

    //         $apilog->save();
            return response()->json([
              'status'=>200,
              'message'=>'Update Successfully'
          ]);
        }
        else{
            // $apilog = new Apilog;
            // $apilog->GSTIN = "";
            // $apilog->apitype = "Put";
            // $apilog->apidata = "";
            // $apilog->apiresponse = "['status'=>404,'message'=>'data Not Found']";
            // $apilog->ApiTimeStamp=date('Y-m-d H:i:s');
    // return $apilog;
            // $log=$api;
            // $apilog->save();
            return response()->json([
                'status'=>404,
                'message'=>'data Not Found'
            ]);

        }
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
