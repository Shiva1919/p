<?php

namespace App\Http\Controllers;

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
    $InfoUpdate= DB::table('acme_ocf_change')->where('ocfno',$user[0]->tenantcode)->get();

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
   function Customer_verfication($tenetcode,$userid,$id)
    {
      $InfoUpdate = DB::table('acme_ocf_change')->where('ocfno',$tenetcode)->where('id',$id)->get();
      
      if(!empty($InfoUpdate))
      {  
       
//update information 
    $user= DB::table('users')
            ->where('tenantcode',$tenetcode)
            ->update(['gstno' => $InfoUpdate[0]->gstno,'panno' => $InfoUpdate[0]->panno,'company_name' => $InfoUpdate[0]->company_name]);
//only admin delelte the recored 
     if ($userid=='admin') {
    DB::table('acme_ocf_change')->where('ocfno',$tenetcode)->where('id',$id)->delete();
    return response()->json([
        'status'=>'verified',
    ]);
   }
   else{
    $user= DB::table('acme_ocf_change')
            ->where('ocfno',$tenetcode)
            ->update(['passedby' => $userid]);
     return response()->json([
                'status'=>'Pass by user',
            ]);
    
   }
   
    // $InfoUpdate2= DB::table('acme_ocf_change')->where('ocfno',$tenetcode)->where('id',$id)->get();

    // if (!$InfoUpdate2) {
    //     return response()->json([
    //         'status'=>'Not Verifed',
    //         'data'=>$InfoUpdate2
    //     ]);
    // }
    // else{
    //     return response()->json([
    //         'status'=>'Verifed',
    //     ]);

    // }
}
else
{
    return response()->json([
        'message'=>'check the Tenet code',
    ]); 
}
   }
//serial number checked expired date
   function sr_validation($tenetcode,$expiring){
    $date = date('Y-m-d',strtotime("$expiring days"));
   
    $datenow = date('Y-m-d');
    $InfoUpdate= DB::table('serialno')->where('ocfno',$tenetcode)
                                      ->where('serialno_validity','<=',$date) 
                                      ->get();
    
 
    if (!empty($InfoUpdate)) {
        $InfoUpdate2= DB::table('serialno')->where('ocfno',$tenetcode)
                                      ->where('serialno_validity',$datenow) 
                                      ->get();
        if(!$InfoUpdate2){
            return response()->json([
                'status'=>'expired'
                
            ]); 
        }
        else{
            return response()->json([
                'status'=>'popup',
                'data'=>$InfoUpdate
            ]);  

        }
   
    }
    else{
        return response()->json([
            'status'=>'not popup',
          ]); 

    }

   
   }


}
