<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Customers;
use App\Models\API\OCF;
use App\Models\API\OCFchange;
use App\Models\API\OCFCustomer;
use App\Models\API\OrderConfirmations;
use App\Models\API\Serialno;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class SerialnoController extends Controller
{
    public function serialnosendotp(Request $request)
    {
        // $ocf = OCF::leftjoin('ocf_modules', 'ocf_master.id', '=', 'ocf_modules.ocfcode')->where('companycode', $company->id)->get(['ocf_modules.modulename', 'ocf_modules.quantity',  'ocf_modules.expirydate']);
        $Mobile = $request->input('phone');
        $checkmobile =  OCFCustomer::where('phone', $request->input('phone'))->first();
        $serialno_ocfno =  OCF::where('ocfno', $request->ocfno)->first();
        // $checkmobile1 =  OCFCustomer::where('phone', $Mobile)->first('phone');
        if($serialno_ocfno == null && $checkmobile == null)
        {
            return response()->json(['Message' => 'Invalid OCF or Mobile No', 'status' => '1']);
        }
        if($checkmobile == null )
        {
            return response()->json(['Message' => 'Mobile No invalid', 'status' => '1']);
        }
        if($serialno_ocfno == null)
        {
            return response()->json(['Message' => 'Invalid OCF ', 'status' => '1']);
        }
       
        $otp =   Str::random(4);
        // return $otp;
        // $verify = OCFCustomerwhere('phone', $Mobile)->first();
        $phone =  OCFCustomer::where('id', $serialno_ocfno->customercode)->first();
      
        $verifyotp = [
            'otp' => $otp,
        ];
        $update_verifyotp = $phone->update($verifyotp); 
        $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(1);
       
        Log::info("otp = ".$otp);
        Log::info("otp_expires_time = ".$otp_expires_time);
        Cache::put('otp_expires_time', $otp_expires_time);
        // $user = OCFCustomerwhere('phone','=',$request->phone)->update(['otp' => $otp]);
        $users = OCFCustomer::where('phone','=',$request->phone)->update(['otp_expires_time' => $otp_expires_time]);
        
        // return $update_verifyotp;
        // DB::table('personal_access_tokens')->where('created_at', '<', Carbon::now()->subMinutes(30))->delete();
       
        $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkmobile->phone&message=Your%20otp%20for%20Acme%20catalogue%20is%20$otp";
 
        $params = ["to" => ["type" => "whatsapp", "number" => $request->input('number')],
        "from" => ["type" => "whatsapp", "number" => "9139465257"],
        "message" => [
        "content" => [
        "type" => "text",
        "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
        ]
        ]
        ];
        //  return $params;
        $headers = ["Authorization" => "Basic " . base64_encode(env('60ab9945c306cdffb00cf0c2') . ":" . env('60ab9945c306cdffb00cf0c2'))];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
        $data = $response->getBody();
        Log::Info($data);
        // $data1 = $this->verifyOtp($request,$Mobile);
        return response()->json(['OTP' => $otp , 'status' => '0', 'message' => 'OTP Generated']);
    }

    public function serialnoverifyotp(Request $request)
    {
        $serialno_ocfno =  OCF::where('ocfno', $request->ocfno)->first();
        $phone =  OCFCustomer::where('id', $serialno_ocfno->customercode)->first();
        //  $phone->otp_expires_time;
        // $verify = OCFCustomerwhere('phone', $request->phone)->first();
        if($phone == null)
        {
            return response()->json(['Message' => 'Invalid Mobile No ', 'status' => '1']);
        }
        $time = date('Y-m-d H:i:s');
        if($time >= $phone->otp_expires_time)
        {
            return response()->json(['status' => '1', 'message' => 'OTP Expired']);
        }
        else if($request->otp == $phone->otp)
        {
            $verifyotp = [
                'isverified' => 1
            ]; 
            $phone->update($verifyotp);  
            return response()->json(['status' => '0', 'message' => 'Verified']);
        }
        else
        {
            return response()->json(['status' => '1' , 'message' => 'Invalid OTP']);
        }
       
    }

    public function ocfchange(Request $request)
    {
         $serialno_ocfno = OCF::where('ocfno', $request->ocfno)->first();
         if($serialno_ocfno->ocfno == null)
         {
             return response()->json(['Message' => 'Invalid OCF', 'status' => '1']);
         }
        // $serialno_customer = OCFCustomerwhere('id', $serialno_ocfno->customercode)->first();
      
        $request->validate([
            'ocfno' => 'required',
            'companyname' => 'required',
            'panno' => 'required',
            'gstno' => 'required'
        ]);
            $ocfchange = new OCFchange();
            $ocfchange->ocfno = $serialno_ocfno->ocfno;
            $ocfchange->companyname = $request->company_name;
            $ocfchange->panno = $request->pan_no;
            $ocfchange->gstno = $request->gst_no;
            $ocfchange->save();
           
            if($ocfchange)
            {
                return response()->json([ 'message' => 'Wait For Verification', 'status' => '0']);
            }
            else
            {
                return response()->json(['message' => 'Verification Failed', 'status' => '1']);
            }
            
    }

    public function serialnogenerate(Request $request)
    {
         // serialno
                        
                        // $company = Company::where('id', $request->companycode)->first();  
                        // $serialnoparameters = $company->companyname.$company->panno.$company->gstno;
                        // $expirydate = date('Y-m-d H:i:s', strtotime($request->ocf_date . " +1 year") );
                        // $insert_serialno = new Serialno();
                        // $insert_serialno->ocfno = ('OCF').($ocflastid->id+1).($series->series+1);
                        // $insert_serialno->comp_name = $company->companyname;
                        // $insert_serialno->pan = $company->panno;
                        // $insert_serialno->gst = $company->gstno;
                        // $insert_serialno->serialno_issue_date = $request->ocf_date;
                        // $insert_serialno->serialno_validity = $expirydate;
                        // $insert_serialno->serialno_parameters = $serialnoparameters;
                        // $insert_serialno->serialno = md5($serialnoparameters);
                        // $insert_serialno->save();
        $serialno_ocfno = OCF::where('ocfno', $request->ocfno)->first();
        if($serialno_ocfno == null)
        {
            return response()->json(['Message' => 'Invalid OCF ', 'status' => '1']);
        }
        $serialno_customers = OCFCustomer::where('id', $serialno_ocfno->customercode)->first();   
        return $serialno_customers;
        $time = date('Y-m-d H:i:s');
        $expirydate = date('Y-m-d H:i:s', strtotime($time . " +1 year") );
        if( $serialno_customers->isverified ==1)
        {
            $serialno_customer = OCFCustomer::where('id', $serialno_ocfno->customercode)->first();

            // $ocf = new Serialno();
            // $ocf->comp_name = $request->comp_name;
            // $ocf->pan = $request->pan;
            // $ocf->gst = $request->gst;
            // $ocf->transaction_datetime = $time;
            // $ocf->serialno_issue_date = $time;
            // $ocf->serialno_validity = $expirydate;
            // $ocf->save();

            $serialcheck =  Serialno::where('ocfno', $request->ocfno)->first();
          
           if ($serialcheck->ocfno) {
           
            // if($parameters['companyname'] == $serialno_customer->companyname && $parameters['panno'] == $serialno_customer->panno && $parameters['gstno'] == $serialno_customer->gstno)
            // {
                $serialno_parameter = $request->comp_name. $request->pan.$request->gst;

            //    $data=[
            //     "transaction_datetime" => $request['transaction_datetime'],
            //     "serialno_issue_date" => $request['serialno_issue_date'],
            //     "serialno_validity" => $request['serialno_validity'],
            //     "serialno_parameters" => $serialno_parameter,
            //     "serialno" => md5($serialno_parameter),
            //    ];
               $ocf = new Serialno();
               $ocf->id;
               $ocf->ocfno = $request->ocfno;
               $ocf->comp_name = $request->comp_name;
               $ocf->pan = $request->pan;
               $ocf->gst = $request->gst;
               $ocf->transaction_datetime = $time;
               $ocf->serialno_issue_date = $time;
               $ocf->serialno_validity = $expirydate;
               $ocf->serialno_parameters = $serialno_parameter;
               $ocf->serialno = md5($serialno_parameter);
               $ocf->save();
            //    return $ocf;
                // $serialno = Serialno::where('ocfno', $request->ocfno)->update($data);
                
                if($ocf){
                    return response()->json(['data' => md5($serialno_parameter), 'status' => '0', 'message' => 'Serial No Generated']);
                }
                else
                {
                    return response()->json(['status' => '1', 'message' => 'Invalid Data']);
                }
                
            // }
            // else 
            // {
            //     return response()->json(['message' => 'Invalid Credentials', 'status' => '1' ]);
            // }
           
           }
        //    else{
        //     if($parameters['companyname'] == $serialno_customer->companyname && $parameters['panno'] == $serialno_customer->panno && $parameters['gstno'] == $serialno_customer->gstno)
        //     {
        //         $serialno_parameter = $serialno_customer->companyname. $serialno_customer->panno.$serialno_customer->gstno;
        //         $serialno = new Serialno();
        //         $serialno->ocfno = $request->ocfno;
        //         $serialno->transaction_datetime = $parameters['transaction_datetime'];
        //         $serialno->serialno_issue_date = $parameters['serialno_issue_date'];
        //         $serialno->serialno_validity = $parameters['serialno_validity'];
        //         // $serialno->serialno_validity_Encrypt = md5($parameters['serialno_validity_Encrypt']);
        //         $serialno->serialno_parameters = $serialno_parameter;
        //         $serialno->serialno = md5($serialno_parameter);
        //         $serialno->save();
             
        //         if($serialno){
        //             return response()->json(['data' => $serialno, 'status' => '0', 'message' => 'Serial No Generated']);
        //         }
        //         else
        //         {
        //             return response()->json(['status' => '1', 'message' => 'Invalid Data']);
        //         }
                
        //     }
        //     else 
        //     {
        //         return response()->json(['message' => 'Invalid Credentials', 'status' => '1' ]);
        //     }   
        // }
    }
        else{
            return response()->json(['message' => 'Unverified', 'status' => '1' ]);
        } 
    }
    
    public function destroy(Serialno $serialno)
    {
        $serialno->delete();
        return response()->json($serialno);
    }
}
