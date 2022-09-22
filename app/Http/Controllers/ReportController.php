<?php

namespace App\Http\Controllers;
use App\Models\Msg_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function Smshistroy(){
        return view('admin/report/sms_histroy');
    }

    Public function fetchalldata(){
        $Allsms=DB::table('msg_historys')
        ->join('sendmsgs', 'msg_historys.cust_id', '=', 'sendmsgs.id')
        ->join('users', 'msg_historys.emp_id', '=', 'users.id')
        ->select('msg_historys.id','sendmsgs.cust_name', 'users.name','msg_historys.url','msg_historys.state_id','msg_historys.district_id','msg_historys.taluka_id','msg_historys.created_at')
        ->get();
        return response()->json($Allsms);
    }
    public function datewise($to,$from){
        $Allsms=DB::table('msg_historys')
        ->join('sendmsgs', 'msg_historys.cust_id', '=', 'sendmsgs.id')
        ->join('users', 'msg_historys.emp_id', '=', 'users.id')
        ->select('msg_historys.id','sendmsgs.cust_name', 'users.name','msg_historys.url','msg_historys.state_id','msg_historys.district_id','msg_historys.taluka_id','msg_historys.created_at')
        ->whereBetween('msg_historys.created_at',[$to,$from])
        ->get();
         return response()->json($Allsms);
    }

    Public function fetchalldata_state($state,$to,$from){

        $Allsms=DB::table('msg_historys')
        ->join('sendmsgs', 'msg_historys.cust_id', '=', 'sendmsgs.id')
        // ->join('users', 'msg_historys.emp_id', '=', 'users.id')
        // ->select('msg_historys.id','sendmsgs.cust_name', 'users.name','msg_historys.url','msg_historys.state_id','msg_historys.district_id','msg_historys.taluka_id','msg_historys.created_at')
        ->select('msg_historys.id','sendmsgs.cust_name','msg_historys.url','msg_historys.state_id','msg_historys.district_id','msg_historys.taluka_id','msg_historys.created_at')
        ->where('msg_historys.state_id',$state)
        ->whereBetween('msg_historys.created_at',[$to,$from])
        ->get();
         return response()->json($Allsms);
    }
    Public function fetchalldata_district($state,$district,$to,$from){
        $Allsms=DB::table('msg_historys')
        ->join('sendmsgs', 'msg_historys.cust_id', '=', 'sendmsgs.id')
        // ->join('users', 'msg_historys.emp_id', '=', 'users.id')
        // ->select('msg_historys.id','sendmsgs.cust_name', 'users.name','msg_historys.url','msg_historys.state_id','msg_historys.district_id','msg_historys.taluka_id','msg_historys.created_at')
        ->select('msg_historys.id','sendmsgs.cust_name','msg_historys.url','msg_historys.state_id','msg_historys.district_id','msg_historys.taluka_id','msg_historys.created_at')
        ->where('msg_historys.state_id',$state)
        ->where('msg_historys.district_id',$district)
        ->whereBetween('msg_historys.created_at',[$to,$from])
        ->get();
         return response()->json($Allsms);
    }
    Public function fetchalldata_taluka($state,$district,$taluka,$to,$from){
        $Allsms=DB::table('msg_historys')
        ->join('sendmsgs', 'msg_historys.cust_id', '=', 'sendmsgs.id')
        ->join('users', 'msg_historys.emp_id', '=', 'users.id')
        ->select('msg_historys.id','sendmsgs.cust_name', 'users.name','msg_historys.url','msg_historys.state_id','msg_historys.district_id','msg_historys.taluka_id','msg_historys.created_at')
        ->where('msg_historys.state_id',$state)
        ->where('msg_historys.district_id',$district)
        ->where('msg_historys.taluka_id',$taluka)
        ->whereBetween('msg_historys.created_at',[$to,$from])
        ->get();
         return response()->json($Allsms);
    }
    public function Pdfhistroy(){
        $Allsms=DB::table('msg_historys')
        ->join('sendmsgs', 'msg_historys.cust_id', '=', 'sendmsgs.id')
        ->join('users', 'msg_historys.emp_id', '=', 'users.id')
        ->select('msg_historys.id','sendmsgs.cust_name', 'users.name','msg_historys.url','msg_historys.created_at')
        ->whereBetween('msg_historys.created_at',[$request->to,$request->from])
        ->get();
         $pdf = \App::make('dompdf.wrapper');
         $pdf->loadHTML($this->convert_customer_data_to_html($Allsms))->setPaper('A3', 'landscape')->setWarnings(false)->save('myfile.pdf');
         return $pdf->stream();


     }

//state wise
     public function Pdfhistroy_state(Request $request){

        $Allsms=DB::table('msg_historys')
        ->join('sendmsgs', 'msg_historys.cust_id', '=', 'sendmsgs.id')
        ->join('users', 'msg_historys.emp_id', '=', 'users.id')
        ->join('State', 'msg_historys.state_id', '=', 'State.owncode')
        ->select('msg_historys.id','sendmsgs.cust_name', 'users.name','msg_historys.url','msg_historys.state_id','msg_historys.district_id','msg_historys.taluka_id','msg_historys.created_at','State.statename')
        ->where('msg_historys.state_id',$request->state)
        ->whereBetween('msg_historys.created_at',[$request->Todate,$request->Fromdate])
        ->get();
         $pdf = \App::make('dompdf.wrapper');


         $pdf->loadHTML($this->convert_customer_State_data_to_html($Allsms))->setPaper('A3', 'landscape')->setWarnings(false)->save('myfile.pdf');
         return $pdf->stream();


     }
     //district wise
     public function Pdfhistroy_district(Request $request){
        $Allsms=DB::table('msg_historys')
        ->join('sendmsgs', 'msg_historys.cust_id', '=', 'sendmsgs.id')
        ->join('users', 'msg_historys.emp_id', '=', 'users.id')
        ->join('State', 'msg_historys.state_id', '=', 'State.owncode')
        ->join('District', 'msg_historys.district_id', '=', 'District.owncode')
        ->select('msg_historys.id','sendmsgs.cust_name', 'users.name','msg_historys.url','msg_historys.state_id','msg_historys.district_id','msg_historys.taluka_id','msg_historys.created_at','State.statename','District.DistrictName')
        ->where('msg_historys.state_id',$request->state)
        ->where('msg_historys.district_id',$request->district)
        ->whereBetween('msg_historys.created_at',[$request->Todate,$request->Fromdate])
        ->get();
         $pdf = \App::make('dompdf.wrapper');


         $pdf->loadHTML($this->convert_customer_district_data_to_html($Allsms))->setPaper('A3', 'landscape')->setWarnings(false)->save('myfile.pdf');
         return $pdf->stream();


     }

      //taluka wise
      public function Pdfhistroy_taluka(Request $request){

        $Allsms=DB::table('msg_historys')
        ->join('sendmsgs', 'msg_historys.cust_id', '=', 'sendmsgs.id')
        ->join('users', 'msg_historys.emp_id', '=', 'users.id')
        ->join('State', 'msg_historys.state_id', '=', 'State.owncode')
        ->join('District', 'msg_historys.district_id', '=', 'District.owncode')
        ->join('Taluka', 'msg_historys.taluka_id', '=', 'Taluka.owncode')
        ->select('msg_historys.id','sendmsgs.cust_name', 'users.name','msg_historys.url','msg_historys.state_id','msg_historys.district_id','msg_historys.taluka_id','msg_historys.created_at','State.statename','District.DistrictName','Taluka.talukaname')
        ->where('msg_historys.state_id',$request->state)
        ->where('msg_historys.district_id',$request->district)
        ->where('msg_historys.taluka_id',$request->$taluka)
        ->whereBetween('msg_historys.created_at',[$request->Todate,$request->Fromdate])
        ->get();
         $pdf = \App::make('dompdf.wrapper');


         $pdf->loadHTML($this->convert_customer_taluka_data_to_html($Allsms))->setPaper('A3', 'landscape')->setWarnings(false)->save('myfile.pdf');
         return $pdf->stream();


     }


     function convert_customer_data_to_html($Allsms)
     {
    //  $Allsms = Msg_history::all();
      $output = '
      <h3 align="center">All Customer Data</h3>
          <table width="100%" style="border-collapse: collapse; border: 0px;">
       <tr>
     <th style="border: 1px solid;  width="02%">Sr</th>
     <th style="border: 1px solid; width="18%">Employee</th>
     <th style="border: 1px solid;  width="20%">Customer</th>
       <th style="border: 1px solid;  width="3%" >Url</th>
     <th style="border: 1px solid; width="40%">Date & Time</th>
    </tr>
      ';
      foreach($Allsms as $sms)
      {
       $output .= '
       <tr>
        <td style="border: 1px solid; padding:10px;">'.$sms->id.'</td>
        <td style="border: 1px solid;  padding:10px;">'.$sms->name.'</td>
        <td style="border: 1px solid; padding:10px;">'.$sms->cust_name.'</td>

        <td style="border: 1px solid;  padding:2px;">'.$sms->url.'</td>
        <td style="border: 1px solid;  padding:10px;">'.date("d-m-Y H:i:s", strtotime($sms->created_at)).'</td>
       </tr>
       ';
      }
      $output .= '</table>';

      return $output;
     }
     function convert_customer_State_data_to_html($Allsms)
     {
    //  $Allsms = Msg_history::all();
      $output = '
      <h3 align="center">Customer Data</h3>
      <h3>state:'.$Allsms[0]->statename.'</h3>
      <table width="100%" style="border-collapse: collapse; border: 0px;">
       <tr>
     <th style="border: 1px solid;  width="02%">Sr</th>
     <th style="border: 1px solid; width="18%">Employee</th>
     <th style="border: 1px solid;  width="20%">Customer</th>
       <th style="border: 1px solid;  width="3%" >Url</th>
     <th style="border: 1px solid; width="40%">Date & Time</th>
    </tr>
      ';
      foreach($Allsms as $sms)
      {
       $output .= '
       <tr>
        <td style="border: 1px solid; padding:10px;">'.$sms->id.'</td>
        <td style="border: 1px solid;  padding:10px;">'.$sms->name.'</td>
        <td style="border: 1px solid; padding:10px;">'.$sms->cust_name.'</td>

        <td style="border: 1px solid;  padding:2px;">'.$sms->url.'</td>
        <td style="border: 1px solid;  padding:10px;">'.date("d-m-Y H:i:s", strtotime($sms->created_at)).'</td>
       </tr>
       ';
      }
      $output .= '</table>';

      return $output;
     }

     function convert_customer_district_data_to_html($Allsms)
     {
    //  $Allsms = Msg_history::all();
      $output = '
      <h3 align="center">Customer Data</h3>
      <h3>state:'.$Allsms[0]->statename.'</h3>
      <h3>District:'.$Allsms[0]->DistrictName.'</h3>

      <table width="100%" style="border-collapse: collapse; border: 0px;">
       <tr>
     <th style="border: 1px solid;  width="02%">Sr</th>
     <th style="border: 1px solid; width="18%">Employee</th>
     <th style="border: 1px solid;  width="20%">Customer</th>
       <th style="border: 1px solid;  width="3%" >Url</th>
     <th style="border: 1px solid; width="40%">Date & Time</th>
    </tr>
      ';
      foreach($Allsms as $sms)
      {
       $output .= '
       <tr>
        <td style="border: 1px solid; padding:10px;">'.$sms->id.'</td>
        <td style="border: 1px solid;  padding:10px;">'.$sms->name.'</td>
        <td style="border: 1px solid; padding:10px;">'.$sms->cust_name.'</td>

        <td style="border: 1px solid;  padding:2px;">'.$sms->url.'</td>
        <td style="border: 1px solid;  padding:10px;">'.date("d-m-Y H:i:s", strtotime($sms->created_at)).'</td>
       </tr>
       ';
      }
      $output .= '</table>';

      return $output;
     }

     function convert_customer_taluka_data_to_html($Allsms)
     {
    //  $Allsms = Msg_history::all();
      $output = '
      <h3 align="center">Customer Data</h3>
      <h3>state:'.$Allsms[0]->statename.'</h3>
      <h3>state:'.$Allsms[0]->DistrictName.'</h3>
      <h3>Taluka:'.$Allsms[0]->talukaname.'</h3>
      <table width="100%" style="border-collapse: collapse; border: 0px;">
       <tr>
     <th style="border: 1px solid;  width="02%">Sr</th>
     <th style="border: 1px solid; width="18%">Employee</th>
     <th style="border: 1px solid;  width="20%">Customer</th>
       <th style="border: 1px solid;  width="3%" >Url</th>
     <th style="border: 1px solid; width="40%">Date & Time</th>
    </tr>
      ';
      foreach($Allsms as $sms)
      {
       $output .= '
       <tr>
        <td style="border: 1px solid; padding:10px;">'.$sms->id.'</td>
        <td style="border: 1px solid;  padding:10px;">'.$sms->name.'</td>
        <td style="border: 1px solid; padding:10px;">'.$sms->cust_name.'</td>

        <td style="border: 1px solid;  padding:2px;">'.$sms->url.'</td>
        <td style="border: 1px solid;  padding:10px;">'.date("d-m-Y H:i:s", strtotime($sms->created_at)).'</td>
       </tr>
       ';
      }
      $output .= '</table>';

      return $output;
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
