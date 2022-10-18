<?php

namespace App\Http\Controllers;

use App\Models\API\Customers;
use App\Models\API\Serialno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class VerficationController extends Controller
{

    function details_changes(){
        $InfoUpdate= DB::table('acme_ocf_change')->get();
        return $InfoUpdate;

    }
 //verifcation display view
    function Check_verfication($company,$pan,$gst)
    {
    $user= DB::table('users')->where('panno',$pan)
                             ->where('gstno',$gst)
                             ->where('company_name',$company)
                             ->get();
         return $user;
    $InfoUpdate= DB::table('acme_ocf_change')->where('ocfno',$user[0]->ocfno)->get();

    if(!$InfoUpdate) {
        return response()->json([
            'status'=>'Not Verifed',
            'data'=>$InfoUpdate
        ]);
      
    }
    else{
        return response()->json([
            'status'=>'Verifed',
        ]);

    }
   }
//user and emplyee actions on click button
   function Customer_verfication(Request $request)
    {         
    //   $InfoUpdate = DB::table('serialno')->where('serialno',$serialnumber)->get();
    //   if (!empty($InfoUpdate)) {
    //     $ocfnumber = DB::table('acme_ocf_change')->where('ocfno',$InfoUpdate->ocfno)->where('id',$id)->get();
    //     if(!empty($ocfnumber))
    //           {  
    //             $ocfnumber = DB::table('acme_product_ocf')->where('ocfno',$InfoUpdate->ocfno)->first();
    //     //update information 
    //         $user= DB::table('users')
    //                 ->where('id',$ocfnumber->customercode)
    //                 ->update(['gstno' => $InfoUpdate[0]->gstno,'panno' => $InfoUpdate[0]->panno,'company_name' => $InfoUpdate[0]->company_name]);
    //     //only admin delelte the recored 
    //          if ($userid=='1') {
    //         DB::table('acme_ocf_change')->where('ocfno',$InfoUpdate->ocfno)->where('id',$id)->delete();
    //         return response()->json([
    //             'Message'=>'done',
    //             // 'status'=>'200',
    //             'status'=>'0',
    //         ]);
    //        }
    //        else{
    //         $user= DB::table('acme_ocf_change')
    //                     ->where('ocfno',$InfoUpdate->ocfno)
    //                     ->update(['passedby' => $userid]);
    //              return response()->json([
    //                 'Message'=>'Pass by user',
    //                         'status'=>'0',
    //                         //'status'=>'200',
    //                     ]);
    //        }

       
    //   }
    //   else{
    //     return response()->json([
    //                 'message'=>'Not Avilable Record',
    //                 'status'=>0
    //             ]); 


    //   }

    

    // }
    // else{
    //     return response()->json([
    //         'message'=>'Not Avilable Serial number',
    //         'status'=>0
    //     ]); 
    // }
      $InfoUpdate = DB::table('acme_ocf_change')->where('ocfno',$request->ocfno)->where('id',$request->id)->get();
      return $InfoUpdate;
      if(!empty($InfoUpdate))
      {  
        $ocfnumber = DB::table('acme_product_ocf')->where('ocfno',$request->ocfno)->first();
//update information 
    $user= DB::table('users')
            ->where('id',$ocfnumber->customercode)
            ->update(['gstno' => $InfoUpdate[0]->gstno,'panno' => $InfoUpdate[0]->panno,'company_name' => $InfoUpdate[0]->company_name]);
//only admin delelte the recored 
     
    //  if ($userid=='1') {
    DB::table('acme_ocf_change')->where('ocfno',$request->ocfno)->where('id',$request->id)->delete();
    return response()->json([
        'Message'=>'done',
        // 'status'=>'200',
        'status'=>'0',
    ]);
//    }
//    else{
    $user= DB::table('acme_ocf_change')
            ->where('ocfno',$request->ocfno);
            // ->update(['passedby' => $userid]);
     return response()->json([
        'Message'=>'Pass by user',
                'status'=>'0',
                //'status'=>'200',
            ]);
    
//    }
    }
    else
    {
        return response()->json([
            'message'=>'check the Tenet code',
        ]); 
    }
}




//serial number checked expired date
   function sr_validation($serialnumber,$expiring){
   

    $serial = DB::table('serialno')->where('serialno',$serialnumber)->first();
      if (!empty($serial)) {
    $date = date('Y-m-d',strtotime("$expiring days"));
    $datenow = date('Y-m-d');
    $InfoUpdate= DB::table('serialno')->where('ocfno',$serial->ocfno)
                                      ->where('serialno_validity','<=',$date) 
                                      ->get();
    if (count($InfoUpdate) == 0) {
        $InfoUpdate2= DB::table('serialno')->where('ocfno',$serial->ocfno)
                                      ->where('serialno_validity',$datenow) 
                                      ->get();
        if(count($InfoUpdate2) == 0){
            return response()->json([
                'message'=>'expired',
                'status'=>1
            ]); 
        }
        else{
            return response()->json([
                'message'=>'popup',
                'status'=>0,
                'data'=>$InfoUpdate
            ]);  
        }
    }
    else{
        return response()->json([
            'message'=>'not popup',
            'status'=> 1,
          ]); 
    }
}
else{
    return response()->json([
        'message'=>'Serial number not found',
        'status'=> 0,
      ]); 

}
}
//check the old serial number and date and new genreated only serial number and incress the count 
   function sr_validation_date(Request $request){

    $request->validate([
        'serialno' => 'required',
        'fromdate' => 'required',
       
    ]);
  
    $InfoUpdate= DB::table('serialno')->where('serialno',$request->serialno)->where('serialno_issue_date',$request->fromdate)->orderBy('id','desc')->first();
    // $date = Carbon::now();
    $time = date('Y-m-d H:i:s');
    $expirydate = date('Y-m-d H:i:s', strtotime($time . " +1 year") );
      return $InfoUpdate->ocfno;
    if (!empty($InfoUpdate)) {
         $cusromer_details= DB::table('acme_product_ocf')->where('ocfno',$InfoUpdate->ocfno)->first(); 
        //  $serialno_customers = Customers::where('id', $cusromer_details->customercode)->first();
        $serial_parameters = Serialno::where('ocfno', $request->ocfno)->orderBy('id','desc')->first();
        return $serial_parameters->comp_name;
        // return $parameters;
         //new generated the serial number
        $update_serialnumber = md5($InfoUpdate->comp_name.$InfoUpdate->pan.$InfoUpdate->gst);
        // return $update_serialnumber;
         //update the only serial number and generated count
    //    Serialno::where('serialno',$request->serialno)->where('serialno_issue_date',$request->fromdate)->save(['serialno' => $update_serialnumber,'serialno_issue_date' => $request->fromdate]);
        $ocf = Serialno::where('serialno',$request->serialno)->where('serialno_issue_date',$request->fromdate)->orderBy('id','desc')->first();
        $ocf->id;
        $ocf->ocfno = $request->ocfno;
        $ocf->comp_name = $request->comp_name;
        $ocf->pan = $request->pan;
        $ocf->gst = $request->gst;
        $ocf->transaction_datetime = $time;
        $ocf->serialno_issue_date = $time;
        $ocf->serialno_validity = $expirydate;
        $ocf->serialno_parameters = $update_serialnumber;
        $ocf->serialno = md5($update_serialnumber);
        $ocf->save();
        return $ocf;
    return response()->json([
            'message'=>'New Serial Number generated',
            'status'=> '0',
            'data'=>['serial_number'=> $update_serialnumber, 'Issue_Date' => $request->fromdate],
        ]);
    }
    else{
        return response()->json([
            'message'=>'Serial Number not found',
            'status'=> '1',
        ]);
    }
}
}
