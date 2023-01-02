<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\BroadcastMessage;
class BroadcoastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Broad = BroadcastMessage::where('Active',1)->orderBy('id','desc')->Tosql();
        return $Broad;
    }

    public function broadCast_deactive()
    {
        $Broada = BroadcastMessage::where('Active',0)->orderBy('id','desc')->get();
        return $Broada;
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

    public function activation($owncode,$id){

        $data= BroadcastMessage::find($owncode);
        if ($data) {
            if ($id) {
                $data->Active = 0;
            }
            else{
                $data->Active = 1;
            }
           $data->save();
           return $data;
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {


            $broadcast_message = new BroadcastMessage();
            $broadcast_message->MessageTarget = $request->target;
            $broadcast_message->CustomerCode = $request->customer;
            $broadcast_message->PackageType = $request->package;
            $broadcast_message->PackageSubType = $request->sub_package;
            $broadcast_message->CompanyCode = $request->company;
            $broadcast_message->GstType = $request->gst_type;
            $broadcast_message->DateFrom = $request->fromdate;
            $broadcast_message->ToDate = $request->todate;
            $broadcast_message->MessageTitle = $request->msgtitle;
            $broadcast_message->UrlButtonText = $request->buttonurl;
            $broadcast_message->HowManyDaysToDisplay = $request->howmanydaytodisplay;
            $broadcast_message->AllowToMarkAsRead = $request->allowtomarkasread;
            $broadcast_message->RoleCode = $request->role;
            $broadcast_message->URLString = $request->url;
            $broadcast_message->MessageDesc = $request->msgdescription;
            $broadcast_message->MessageDescMarathi = $request->desc_marathi;
            $broadcast_message->MessageDescHindi = $request->desc_hindi;
            $broadcast_message->MessageDescKannada = $request->desc_kanad;
            $broadcast_message->MessageDescGujarathi = $request->buttonurl;
            $broadcast_message->save();

            if ($broadcast_message->id) {
                return response()->json([
                    'status'=>1,
                    'message'=>'BoardCasting Message Added Successfully',
                    'data'=>$broadcast_message
                ]);
            }
            else{
                return response()->json([
                    'status'=>0,
                    'message'=>'Something error'
                ]);

            }





    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Broad = BroadcastMessage::find($id);
        if (is_null($Broad))
        {
            return $this->sendError('User not found.');
        }
        return response()->json($Broad);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $broadcast_message = BroadcastMessage::find($id);
        $broadcast_message->MessageTarget = $request->target;
        $broadcast_message->CustomerCode = $request->customer;
        $broadcast_message->PackageType = $request->package;
        $broadcast_message->PackageSubType = $request->sub_package;
        $broadcast_message->CompanyCode = $request->company;
        $broadcast_message->GstType = $request->gst_type;
        $broadcast_message->DateFrom = $request->fromdate;
        $broadcast_message->ToDate = $request->todate;
        $broadcast_message->MessageTitle = $request->msgtitle;
        $broadcast_message->UrlButtonText = $request->buttonurl;
        $broadcast_message->HowManyDaysToDisplay = $request->howmanydaytodisplay;
        $broadcast_message->AllowToMarkAsRead = $request->allowtomarkasread;
        $broadcast_message->RoleCode = $request->role;
        $broadcast_message->URLString = $request->url;
        $broadcast_message->MessageDesc = $request->msgdescription;
        $broadcast_message->MessageDescMarathi = $request->desc_marathi;
        $broadcast_message->MessageDescHindi = $request->desc_hindi;
        $broadcast_message->MessageDescKannada = $request->desc_kanad;
        $broadcast_message->MessageDescGujarathi = $request->buttonurl;
        $broadcast_message->save();
        // return $user;
        return response()->json([
            'status'=>200,
            'message'=>'BoardCasting Message Updated Successfully',
            'data'=>$broadcast_message
        ]);
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
