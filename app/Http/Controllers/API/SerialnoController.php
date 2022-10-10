<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Customers;
use App\Models\API\OCFchange;
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
    public function serialnosendotp(Request $request, $ocfno)
    {
        $Mobile = $request->input('phone');
        $checkmobile =  Customers::where('phone', $request->input('phone'))->first();
        // $checkmobile1 =  Customers::where('phone', $Mobile)->first('phone');
        if($checkmobile == null)
        {
            return response()->json(['OTP' => 'Mobile No invalid', 'status' => '1']);
        }
        $serialno_ocfno =  OrderConfirmations::where('ocfno', $ocfno)->first();

        $otp =   Str::random(6);
        // return $otp;
        $verify = Customers::where('phone', $Mobile)->first();
        $verifyotp = [
            'otp' => $otp,
        ];
        $update_verifyotp = $verify->update($verifyotp); 
        // return $verify;
        $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(1);
       
        Log::info("otp = ".$otp);
        Log::info("otp_expires_time = ".$otp_expires_time);
        Cache::put('otp_expires_time', $otp_expires_time);
        // $user = Customers::where('phone','=',$request->phone)->update(['otp' => $otp]);
        $users = Customers::where('phone','=',$request->phone)->update(['otp_expires_time' => $otp_expires_time]);
        
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
        return response()->json(['OTP' => $otp , 'status' => '0', 'data' => $update_verifyotp]);
    }

    public function serialnoverifyotp(Request $request, $mobile)
    {
        $verify = Customers::where('phone', $mobile)->first();
        $time = date('Y-m-d H:i:s');
        if($time >= $verify->otp_expires_time)
        {
            return response()->json(['status' => '1', 'message' => 'OTP Expired']);
        }
        else if($request->otp == $verify->otp)
        {
            $verifyotp = [
                'isverified' => 1
            ]; 
            $verify->update($verifyotp);  
            return response()->json(['status' => '0', 'message' => 'Verified']);
        }
        else
        {
            return response()->json(['status' => '1' , 'message' => 'Invalid OTP']);
        }
       
    }

    public function ocfchange(Request $request, $ocfno)
    {
        // $serialno_ocfno = OrderConfirmations::where('ocfno', $ocfno)->first();
        // $serialno_customer = Customers::where('id', $serialno_ocfno->customercode)->first();
        $request->validate([
            'ocfno' => 'acme_ocf_change',
            'company_name' => 'required',
            'panno' => 'required',
            'gstno' => 'required'
        ]);
            $ocfchange = new OCFchange();
            $ocfchange->ocfno = $ocfno;
            $ocfchange->company_name = $request->company_name;
            $ocfchange->panno = $request->panno;
            $ocfchange->gstno = $request->gstno;
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

    public function serialnogenerate(Request $request, $ocfno)
    {
        $serialno_ocfno = OrderConfirmations::where('ocfno', $ocfno)->first();
        $serialno_customers = Customers::where('id', $serialno_ocfno->customercode)->first();   
        // return $serialno_customers;
        if( $serialno_customers->isverified ==1)
        {
            $serialno_customer = Customers::where('id', $serialno_ocfno->customercode)->first();

            $parameters = [
                'company_name' => $request->company_name,
                'panno' => $request->panno,
                'gstno' => $request->gstno,
                'transaction_datetime' => $serialno_ocfno->fromdate,
                'serialno_issue_date' => $serialno_ocfno->fromdate,
                'serialno_validity' => $serialno_ocfno->todate,
                'serialno_validity_Encrypt' => $serialno_ocfno->todate
            ];
            // return $parameters;
            if($parameters['company_name'] == $serialno_customer->company_name && $parameters['panno'] == $serialno_customer->panno && $parameters['gstno'] == $serialno_customer->gstno)
            {
                $serialno_parameter = $serialno_customer->company_name. $serialno_customer->panno.$serialno_customer->gstno;
                $serialno = new Serialno();
                $serialno->ocfno = $ocfno;
                $serialno->transaction_datetime = $parameters['transaction_datetime'];
                $serialno->serialno_issue_date = $parameters['serialno_issue_date'];
                $serialno->serialno_validity = $parameters['serialno_validity'];
                $serialno->serialno_validity_Encrypt = md5($parameters['serialno_validity_Encrypt']);
                $serialno->serialno_parameters = $serialno_parameter;
                $serialno->serialno = md5($serialno_parameter);
                $serialno->save();
                if($serialno){
                    return response()->json(['data' => $serialno, 'status' => '0', 'message' => 'Serial No Generated']);
                }
                else
                {
                    return response()->json(['status' => '1', 'message' => 'Invalid Data']);
                }
                
            }
            else 
            {
                return response()->json(['message' => 'Invalid Credentials', 'status' => '1' ]);
            }   
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
