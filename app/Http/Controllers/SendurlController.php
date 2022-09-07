<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sendmsg;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Msg_history;
use App\Models\Sendurl;

class SendurlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "Asdf";
    }
    public function getState(Request $request){
        $data = DB::table('state')->orderBy('statename','asc')->get();
        return response()->json($data);

    }
    public function send_msg(Request $request){
   

         $contact=Sendmsg::select('*')->where('contact_number','=',$request->contact)->get();

         $url=Sendurl::orderBy('id', 'DESC')->take(1)->get();
         if ($url->isEmpty()) {
            return response()->json([
                'status'=>404,
                'message'=>'Url Not Avilable'
            ]);

         }
         $urla=$url[0]->url;
         $finelurl= str_replace ('$mobile',$request->contact,$urla);
        //  Http::get($finelurl);
        
        if ($contact->isEmpty()){
         
            $Sendmsg= new Sendmsg;
            $Sendmsg->contact_number = $request->contact;
            $Sendmsg->cust_name = $request->cust_name;
            $Sendmsg->cust_email = $request->cust_email;
            $Sendmsg->cust_address = $request->cust_address;
            $Sendmsg->state_id = $request->state;
            $Sendmsg->district_id = $request->district;
            $Sendmsg->taluka_id = $request->taluka;
            $Sendmsg->city_id = $request->city;
            $Sendmsg->post_code = $request->post_code;
            $Sendmsg->save();
            $lastid = $Sendmsg->id;
            $Msg_history= new Msg_history;
            $Msg_history->url =  $finelurl;
            $Msg_history->cust_id = $lastid;
            // $Msg_history->emp_id= auth()->user()->id;
            $Msg_history->emp_id= 0;
            $Msg_history->state_id = $request->state;
            $Msg_history->district_id = $request->district;
            $Msg_history->taluka_id = $request->taluka;
            $Msg_history->city_id = $request->city;
            $Msg_history->save();
            $savenumber=$request->contact;
            return response()->json([
                'status'=>201,
                'message'=>'Message Send Successfully'
            ]);

        }
        else{
           
            $id=$contact[0]->id;
            $Sendmsg = Sendmsg::find($id);
            $Sendmsg->cust_name = $request->cust_name;
            $Sendmsg->cust_email = $request->cust_email;
            $Sendmsg->cust_address = $request->cust_address;
            $Sendmsg->state_id = $request->state;
            $Sendmsg->district_id = $request->district;
            $Sendmsg->taluka_id = $request->taluka;
            $Sendmsg->city_id = $request->city;
            $Sendmsg->post_code = $request->post_code;
            $Sendmsg->update();
            $Msg_history= new Msg_history;
            $Msg_history->url =  $finelurl;
            $Msg_history->cust_id = $contact[0]->id;
            // $Msg_history->emp_id= auth()->user()->id;
            $Msg_history->emp_id= 0;
            $Msg_history->state_id = $request->state;
            $Msg_history->district_id = $request->district;
            $Msg_history->taluka_id = $request->taluka;
            $Msg_history->city_id = $request->city;
            $Msg_history->save();
            return response()->json([
                'status'=>200,
                'message'=>'Message Send Successfully'
            ]);

        }
    // $contact=Sendmsg::select('*')->where('contact_number','=',$request->contact)->get();



    }
    public function getDistrict($request)
    {
        $data =DB::table('district')->where("stateid",$request)->orderBy('districtname','asc')->get();
        return response()->json($data);
    }

    public function getTaluka($request)
    {
        
        $data =DB::table('taluka')->where("districtid",$request)->orderBy('talukaname','asc')->get();
        return response()->json($data);
    }

    public function getCity($request)
    {
        $data =DB::table('city')->where("talukaid",$request)->orderBy('cityname','asc')->get();
        return response()->json($data);
    }
    public function customername($num){

        $contact=Sendmsg::select('*')->where('contact_number','=',$num)->get();
        if (!$contact->isEmpty()){
            return [
                'status'=>200,
                'url'=>$contact
            ];
        }
        else
        {
            return [
                'status'=>404,
                'url'=>'Data Not Avilable'
            ];


        }
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
        //
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
