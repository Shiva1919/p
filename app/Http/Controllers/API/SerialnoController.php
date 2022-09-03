<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Customers;
use App\Models\API\OCFchange;
use App\Models\API\OrderConfirmations;
use App\Models\API\Serialno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SerialnoController extends Controller
{
    public function serialnosendotp(Request $request, $ocfno)
    {
        $Mobile = $request->input('mobile');
        $serialno_ocfno =  OrderConfirmations::where('ocfno', $ocfno)->first();

        $otp = $serialno_ocfno->ocfno + rand(1000, 9999);
      
        // $verifyotps = User::where('mobile', $Mobile)->get();

        $update = DB::table('users')->where('mobile', $Mobile)->update(array('otp' => $otp));
       
        $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$Mobile&message=Your%20otp%20for%20Acme%20catalogue%20is%20$otp";
        $params = ["to" => ["type" => "whatsapp", "number" => $request->input('number')],
        "from" => ["type" => "whatsapp", "number" => "9139465257"],
        "message" => [
        "content" => [
        "type" => "text",
        "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
        ]
        ]
        ];
        // return $params;
        $headers = ["Authorization" => "Basic " . base64_encode(env('60ab9945c306cdffb00cf0c2') . ":" . env('60ab9945c306cdffb00cf0c2'))];
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
        $data = $response->getBody();
        Log::Info($data);
        // $this->verifyOtp($request,$Mobile);
        return response()->json(['status' => $update]);
    }

    public function serialnoverifyotp(Request $request, $mobile)
    {
        $verify = Customers::where('mobile', $mobile)->first();
        $verifyotp = [
            'isverified' => $request->isverified
        ];

        $update_verifyotp = $verify->update($verifyotp);
       
        if($verify->isverified == $verify->otp)
        {
            return response()->json(['status' => 'Success']);
        }
        else
        {
            return response()->json(['status' => 'Fail']);
        }
        return response()->json($update_verifyotp);
    }

    public function ocfchange(Request $request, $ocfno)
    {
        // $serialno_ocfno = OrderConfirmations::where('ocfno', $ocfno)->first();
        // $serialno_customer = Customers::where('id', $serialno_ocfno->customercode)->first();
        $request->validate([
            'ocfno' => 'unique:acme_ocf_change',
            'company_name' => 'required',
            'panno' => 'required|unique:acme_ocf_change',
            'gstno' => 'required|unique:acme_ocf_change'
        ]);
            $ocfchange = new OCFchange();
            $ocfchange->ocfno = $ocfno;
            $ocfchange->company_name = $request->company_name;
            $ocfchange->panno = $request->panno;
            $ocfchange->gstno = $request->gstno;
            $ocfchange->save();
            return response()->json([ $ocfchange]);
    }

    public function ocfstatus()
    {

    }

    public function serialnogenerate(Request $request, $ocfno)
    {
        $serialno_ocfno = OrderConfirmations::where('ocfno', $ocfno)->first();
        $serialno_customer = Customers::where('id', $serialno_ocfno->customercode)->first();
        $parameters = [
            'company_name' => $request->company_name,
            'panno' => $request->panno,
            'gstno' => $request->gstno,
            'transaction_datetime' => $serialno_ocfno->fromdate,
            'serialno_issue_date' => $serialno_ocfno->fromdate,
            'serialno_validity' => $serialno_ocfno->todate
        ];
        if($parameters['company_name'] == $serialno_customer->company_name && $parameters['panno'] == $serialno_customer->panno && $parameters['gstno'] == $serialno_customer->gstno)
        {
            $serialno_parameter = $serialno_customer->company_name. $serialno_customer->panno.$serialno_customer->gstno;
            $serialno = new Serialno();
            $serialno->ocfno = $ocfno;
            $serialno->transaction_datetime = $parameters['transaction_datetime'];
            $serialno->serialno_issue_date = $parameters['serialno_issue_date'];
            $serialno->serialno_validity = $parameters['serialno_validity'];
            $serialno->serialno_parameters = $serialno_parameter;
            $serialno->serialno = md5($serialno_parameter);
            $serialno->save();
            return response()->json(['data' => $serialno->serialno]);
        }
        else 
        {
            return response()->json(['message' => 'Invalid Credentials']);
        }       
    }
    
    public function destroy(Serialno $serialno)
    {
        $serialno->delete();
        return response()->json($serialno);
    }
}
