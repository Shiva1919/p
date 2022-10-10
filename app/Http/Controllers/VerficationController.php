<?php

namespace App\Http\Controllers;

use App\Models\API\Customers;
use App\Models\API\Serialno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
   function Customer_verfication($serialnumber,$userid,$id)
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
      $InfoUpdate = DB::table('acme_ocf_change')->where('ocfno',$serialnumber)->where('id',$id)->get();
      if(!empty($InfoUpdate))
      {  
        $ocfnumber = DB::table('acme_product_ocf')->where('ocfno',$serialnumber)->first();
//update information 
    $user= DB::table('users')
            ->where('id',$ocfnumber->customercode)
            ->update(['gstno' => $InfoUpdate[0]->gstno,'panno' => $InfoUpdate[0]->panno,'company_name' => $InfoUpdate[0]->company_name]);
//only admin delelte the recored 
     if ($userid=='1') {
    DB::table('acme_ocf_change')->where('ocfno',$serialnumber)->where('id',$id)->delete();
    return response()->json([
        'Message'=>'done',
        // 'status'=>'200',
        'status'=>'0',
    ]);
   }
   else{
    $user= DB::table('acme_ocf_change')
            ->where('ocfno',$serialnumber)
            ->update(['passedby' => $userid]);
     return response()->json([
        'Message'=>'Pass by user',
                'status'=>'0',
                //'status'=>'200',
            ]);
    
   }
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
        'todate' => 'required',
       
    ]);
  
    $InfoUpdate= DB::table('serialno')->where('serialno',$request->serialno)->where('serialno_validity_Encrypt',$request->todate)->first();
    if (!empty($InfoUpdate)) {
         $cusromer_details= DB::table('acme_product_ocf')->where('ocfno',$InfoUpdate->ocfno)->first(); 
         $serialno_customers = Customers::where('id', $cusromer_details->customercode)->first();
         //new generated the serial number
         $update_serialnumber = md5($serialno_customers->company_name.$serialno_customers->panno.$serialno_customers->gstno.($InfoUpdate->serialno_generated_count+1));
        //update the only serial number and generated count
       Serialno::where('serialno',$request->serialno)->where('serialno_validity_Encrypt',$request->todate)->update(['serialno' => $update_serialnumber,'serialno_generated_count'=>$InfoUpdate->serialno_generated_count+1]);
        return response()->json([
            'message'=>'New Serial Number generated',
            'status'=> 0,
            'data'=>['serial_number'=> $update_serialnumber],
        ]);
    }
    else{
        return response()->json([
            'message'=>'Serial Number not found',
            'status'=> 1,
        ]);
    }
}
}
