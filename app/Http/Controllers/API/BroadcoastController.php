<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\BroadcastMessage;
use Illuminate\Support\Facades\DB;

class BroadcoastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key = config('global.key');
        $Broad = BroadcastMessage::select('id','MessageTarget', 'CustomerCode', 'CompanyCode', 'PackageType', 'RoleCode', 'GstType', 'DateFrom', 'ToDate',
                    DB::raw('CAST(AES_DECRYPT(UNHEX(MessageTitle),"'.$key.'") AS CHAR) AS MessageTitle'),
                    'AllPreferredLanguages', DB::raw('CAST(AES_DECRYPT(UNHEX(MessageDesc),"'.$key.'") AS CHAR) AS MessageDesc'), 'Active', 'HowManyDaysToDisplay', 'AllowToMarkAsRead', 'UrlButtonText',
                    'URLString', 'SpecialKeyToClose', DB::raw('CAST(AES_DECRYPT(UNHEX(MessageDescMarathi),"'.$key.'") AS CHAR) AS MessageDescMarathi'),
                    DB::raw('CAST(AES_DECRYPT(UNHEX(MessageDescHindi),"'.$key.'") AS CHAR) AS MessageDescHindi'),
                    DB::raw('CAST(AES_DECRYPT(UNHEX(MessageDescKannada),"'.$key.'") AS CHAR) AS MessageDescKannada'),
                    DB::raw('CAST(AES_DECRYPT(UNHEX(MessageDescGujarathi),"'.$key.'") AS CHAR) AS MessageDescGujarathi'))
                    ->where('Active',1)->orderBy('id','desc')
                    ->get();
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

        $key = config('global.key');
            $broadcast_message = new BroadcastMessage();
            $broadcast_message->MessageTarget = $request->target;
            $broadcast_message->CustomerCode = $request->customer;
            $broadcast_message->PackageType = $request->package;
            $broadcast_message->PackageSubType = $request->sub_package;
            $broadcast_message->CompanyCode = $request->company;
            $broadcast_message->GstType = $request->gst_type;
            $broadcast_message->DateFrom = $request->fromdate;
            $broadcast_message->ToDate = $request->todate;
            $broadcast_message->MessageTitle = DB::raw("HEX(AES_ENCRYPT('$request->msgtitle' , '$key'))");
            $broadcast_message->UrlButtonText = $request->buttonurl;
            $broadcast_message->HowManyDaysToDisplay = $request->howmanydaytodisplay;
            $broadcast_message->AllowToMarkAsRead = $request->allowtomarkasread;
            $broadcast_message->RoleCode = $request->role;
            $broadcast_message->URLString = $request->url;
            $broadcast_message->MessageDesc = DB::raw("HEX(AES_ENCRYPT('$request->msgdescription' , '$key'))");
            $broadcast_message->MessageDescMarathi = DB::raw("HEX(AES_ENCRYPT('$request->desc_marathi' , '$key'))");
            $broadcast_message->MessageDescHindi = DB::raw("HEX(AES_ENCRYPT('$request->desc_hindi' , '$key'))");
            $broadcast_message->MessageDescKannada = DB::raw("HEX(AES_ENCRYPT('$request->desc_kanad' , '$key'))");
            $broadcast_message->MessageDescGujarathi = DB::raw("HEX(AES_ENCRYPT('$request->desc_gujrati' , '$key'))");
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
        $key = config('global.key');
        $Broad =  BroadcastMessage::select('MessageTarget', 'CustomerCode', 'CompanyCode', 'PackageType', 'RoleCode', 'GstType', 'DateFrom', 'ToDate',
                    DB::raw('CAST(AES_DECRYPT(UNHEX(MessageTitle),"'.$key.'") AS CHAR) AS MessageTitle'),
                    'AllPreferredLanguages', DB::raw('CAST(AES_DECRYPT(UNHEX(MessageDesc),"'.$key.'") AS CHAR) AS MessageDesc'), 'Active', 'HowManyDaysToDisplay', 'AllowToMarkAsRead', 'UrlButtonText',
                    'URLString', 'SpecialKeyToClose', DB::raw('CAST(AES_DECRYPT(UNHEX(MessageDescMarathi),"'.$key.'") AS CHAR) AS MessageDescMarathi'),
                    DB::raw('CAST(AES_DECRYPT(UNHEX(MessageDescHindi),"'.$key.'") AS CHAR) AS MessageDescHindi'),
                    DB::raw('CAST(AES_DECRYPT(UNHEX(MessageDescKannada),"'.$key.'") AS CHAR) AS MessageDescKannada'),
                    DB::raw('CAST(AES_DECRYPT(UNHEX(MessageDescGujarathi),"'.$key.'") AS CHAR) AS MessageDescGujarathi'))
                    ->where('id', $id)
                    ->first();
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
        $key = config('global.key');
        $broadcast_message = BroadcastMessage::find($id);
        $broadcast_message->MessageTarget = $request->target;
        $broadcast_message->CustomerCode = $request->customer;
        $broadcast_message->PackageType = $request->package;
        $broadcast_message->PackageSubType = $request->sub_package;
        $broadcast_message->CompanyCode = $request->company;
        $broadcast_message->GstType = $request->gst_type;
        $broadcast_message->DateFrom = $request->fromdate;
        $broadcast_message->ToDate = $request->todate;
        $broadcast_message->MessageTitle = DB::raw("HEX(AES_ENCRYPT('$request->msgtitle' , '$key'))");
        $broadcast_message->UrlButtonText = $request->buttonurl;
        $broadcast_message->HowManyDaysToDisplay = $request->howmanydaytodisplay;
        $broadcast_message->AllowToMarkAsRead = $request->allowtomarkasread;
        $broadcast_message->RoleCode = $request->role;
        $broadcast_message->URLString = $request->url;
        $broadcast_message->MessageDesc = DB::raw("HEX(AES_ENCRYPT('$request->msgdescription' , '$key'))");
        $broadcast_message->MessageDescMarathi = DB::raw("HEX(AES_ENCRYPT('$request->desc_marathi' , '$key'))");
        $broadcast_message->MessageDescHindi = DB::raw("HEX(AES_ENCRYPT('$request->desc_hindi' , '$key'))");
        $broadcast_message->MessageDescKannada = DB::raw("HEX(AES_ENCRYPT('$request->desc_kanad' , '$key'))");
        $broadcast_message->MessageDescGujarathi = DB::raw("HEX(AES_ENCRYPT('$request->desc_gujrati' , '$key'))");
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
