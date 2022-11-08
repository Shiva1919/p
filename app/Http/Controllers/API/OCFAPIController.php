<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Company;
use App\Models\API\OCF;
use App\Models\API\OCFCustomer;
use App\Models\API\OCFModule;
use App\Models\API\Serialno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Validator;

class OCFAPIController extends Controller
{
    public function customercreate(Request $request)
    {
        $dataaa=[];
        $entrycode= OCFCustomer::where('entrycode', $request->entrycode)->get();
        if(count($entrycode)==0)
        {
            $rules = array(
                'name' => 'required',
                'entrycode' => '',
                'phone' => 'required',
                'email' => 'required',
                'address1' => 'required',
                'state' => 'required',
                'district' => 'required',
                'taluka' => 'required',
                'city' => 'required',
                'whatsappno' => 'required',
                'concernperson' => 'required',
                'packagecode' => 'required',
                'subpackagecode' => 'required',             
            );
          
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) 
            {
                return response()->json([
                    'message' => 'Invalid params passed', // the ,message you want to show
                    'errors' => $validator->errors()
                ], 422);
            }
            else
            {
                $role_id = 10;
                $password = 'AcmeAcme1994';
                $ocfcustomerflastid = OCFCustomer::orderBy('id', 'desc')->first();
                $insert_customers = new OCFCustomer();
                $insert_customers->name = $request->name;
                $insert_customers->entrycode = $request->name.$request->phone.$request->packagecode;
                $insert_customers->phone = $request->phone;
                $insert_customers->email = $request->email;
                $insert_customers->address1 = $request->address1;
                $insert_customers->state = $request->state;
                $insert_customers->district = $request->district;
                $insert_customers->taluka = $request->taluka;
                $insert_customers->city = $request->city;
                $insert_customers->whatsappno = $request->whatsappno;
                $insert_customers->concernperson = $request->concernperson;
                $insert_customers->packagecode = $request->packagecode;
                $insert_customers->subpackagecode = $request->subpackagecode;
                $insert_customers->save();
                // if(!empty($insert_customers->id)) 
                // {
                //     foreach ($request->Cdocument as $request ) 
                //     {
                //             $data=[
                //                 'customercode'=> $insert_customers->id,
                //                 // 'companycode'=> $ocfcompanyflastid->id+1,
                //                 'company_name'=>  $request['company_name'],
                //                 'pan_no'=> $request['pan_no'],
                //                 'gst_no'=> $request['gst_no'],
                //             ];
                //             array_push($dataaa,$data);
                //             Company::create($data);
                //     }
                // }
                return response()->json(['message' => 'Customer Saved Successfully','status' => '0','Customer' => $insert_customers]);
            }
            
            // $request->name.$request->phone.$request->packagename;
        }
        else
        {
            $rules = array(
                'name' => 'required',
                'entrycode' => '',
                'phone' => 'required',
                'email' => 'required',
                'address1' => 'required',
                'state' => 'required',
                'district' => 'required',
                'taluka' => 'required',
                'city' => 'required',
                'whatsappno' => 'required',
                'concernperson' => 'required',
                'packagecode' => 'required',
                'subpackagecode' => 'required',
                // 'company_name'=>'required',
                // 'pan_no'=> 'required',
                // 'gst_no'=> 'required',
            );
        
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) 
            {
                return response()->json([
                    'message' => 'Invalid params passed', // the ,message you want to show
                    'errors' => $validator->errors()
                ], 422);
            }
            else
            {
                $role_id = 10;
                $password = 'AcmeAcme1994';
                $ocfcustomerlastid = OCFCustomer::orderBy('id', 'desc')->first();
                $insert_customers = OCFCustomer::where('entrycode', $request->entrycode)->first();
                $insert_customers->name = $request->name;
                $insert_customers->entrycode = $request->entrycode;
                $insert_customers->phone = $request->phone;
                $insert_customers->email = $request->email;
                $insert_customers->address1 = $request->address1;
                $insert_customers->state = $request->state;
                $insert_customers->district = $request->district;
                $insert_customers->taluka = $request->taluka;
                $insert_customers->city = $request->city;
                $insert_customers->whatsappno = $request->whatsappno;
                $insert_customers->concernperson = $request->concernperson;
                $insert_customers->packagecode = $request->packagecode;
                $insert_customers->subpackagecode = $request->subpackagecode;
                $insert_customers->save();

                // Company::where('customercode',$insert_customers->id)->delete();
                // if(!empty($insert_customers->id))
                // {
                //     $company = Company::where('customercode',  $insert_customers->id)->get();
                //     foreach ($request->Cdocument as $request )
                //     {
                      
                //         // return $company;
                //         // $data = Company::where('customercode',  $insert_customers->id)->first();
                //         // $data->customercode = $insert_customers->id;
                //         // // $data->companycode = $ocfcompanyflastid->id+1;
                //         // $data->company_name = $request['company_name'];
                //         // $data->pan_no = $request['pan_no'];
                //         // $data->gst_no = $request['gst_no'];
                //         // $data->save();
                //             $data=[
                //                 'customercode'=> $insert_customers->id,
                //                 // 'companycode'=> $ocfcompanyflastid->id+1,
                //                 'company_name'=>  $request['company_name'],
                //                 'pan_no'=> $request['pan_no'],
                //                 'gst_no'=> $request['gst_no'],
                //             ];
                //             array_push($dataaa,$data);
                //             Company::create($data);
                //     }
                    return response()->json(['message' => 'Customer Updated Successfully','status' => '0','Customer' => $request->all(), 'Customercode' => $ocfcustomerlastid->id ]);
                }
            }
        }

    public function OCF(Request $request)
    {
        $data1=[];
        $series = OCF::orderBy('series', 'desc')->first('series');
        $ocf= OCF::where('ocfno', $request->ocfno)->get();
        $ocflastid = OCF::orderBy('id', 'desc')->orderBy('series', 'desc')->first();
        $companydata= OCF::where('companycode', $request->companycode)->get();
        if(count($companydata)==0)
        {
            $rules = array(
                // 'customercode' => 'required',
                'companycode' => 'required',
                'ocf_date' => 'required',
            );
        
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) 
            {
                return response()->json([
                    'message' => 'Invalid params passed', // the ,message you want to show
                    'errors' => $validator->errors()
                ], 422);
            }
            else
            {
                
                $insert_ocf = new OCF();
                
                // $insert_ocf->customercode = $request->customercode;
                $insert_ocf->companycode = $request->companycode;
                $insert_ocf->ocfno = ('OCF').($ocflastid->id+1).($series->series+1);
                $insert_ocf->ocf_date = $request->ocf_date;
                // $insert_ocf->module_total=$request->module_total;
                $insert_ocf->series=$series->series+1;
                $insert_ocf->save();
                if(!empty($insert_ocf->id))
                {
                
                    foreach ($request->Data as $data ) 
                    {
                    $data=[
                        'ocfcode'=> $insert_ocf->id,
                        'modulename'=> $data['modulename'],
                        // 'modulecode'=> $data['modulecode'],
                        'quantity'=> $data['quantity'],
                        // 'unit'=>  $data['unit'],
                        'expirydate'=> $data['expirydate'],
                        // 'amount'=> $data['amount'],
                        
                    ];
                    array_push($data1,$data);
                    OCFModule::create($data);
                    }
                     // serialno
                   
                    $company = Company::where('id', $request->companycode)->first();  
                    $serialnoparameters = $company->company_name.$company->pan_no.$company->gst_no;
                    $insert_serialno = new Serialno();
                    $insert_serialno->ocfno = ('OCF').($ocflastid->id+1).($series->series+1);
                    $insert_serialno->comp_name = $company->company_name;
                    $insert_serialno->pan = $company->pan_no;
                    $insert_serialno->gst = $company->gst_no;
                    $insert_serialno->serialno_issue_date = $request->ocf_date;
                    $insert_serialno->serialno_parameters = $serialnoparameters;
                    $insert_serialno->serialno = md5($serialnoparameters);
                    $insert_serialno->save();

                return response()->json(['message' => 'OCF Created Successfully','status' => '0','OCF' => $insert_ocf, 'Module' => $data1, 'Serialno' => $insert_serialno]);
                }
            }
        }
        else
        {
            return response()->json(['message' => 'Company Already Exists', 'status' => '1']);
            // $rules = array(
            //     'customercode' => 'required',
            //     'companycode' => 'required',
            //     'ocfno' => 'required',
            //     'ocf_date' => 'required',
            //     'series'=> ''
            // );
        
            // $validator = Validator::make($request->all(), $rules);
            // if ($validator->fails()) 
            // {
            //     return response()->json([
            //         'message' => 'Invalid params passed', // the ,message you want to show
            //         'errors' => $validator->errors()
            //     ], 422);
            // }
            // else
            // {
               
                
            //     $insert_ocf = OCF::where('ocfno', $request->ocfno)->first();
               
            //     $module = DB::table('module_master')->where('ocfcode', $insert_ocf->id)->sum('amount');
               
            //     // $insert_customers->tenantcode = $request->tenantcode;
            //     $insert_ocf->customercode = $request->customercode;
            //     $insert_ocf->companycode = $request->companycode;
            //     $insert_ocf->ocfno = $request->ocfno;
            //     $insert_ocf->ocf_date = $request->ocf_date;
                
            //     // OCFModule::where('ocfcode',$insert_ocf->id)->delete();
            //     if(!empty($insert_ocf->id))
            //     {
                
            //         foreach ($request->Data as $data )
            //                 $data=[
            //                     'ocfcode'=> $insert_ocf->id,
            //                     'modulename'=> $data['modulename'],
            //                     // 'modulecode'=> $data['modulecode'],
            //                     'quantity'=> $data['quantity'],
            //                     // 'unit'=>  $data['unit'],
            //                     'expirydate'=> $data['expirydate']
            //                     // 'amount'=> $data['amount'],   
            //                 ];

            //                 OCFModule::create($data);
            //     }
            //     // $insert_ocf->module_total=$module+$data['amount'];
            //     $insert_ocf->save();

            //     // serialno
            //     $insert_serialno = Serialno::where('ocfno', $request->ocfno)->first();
            //     $company = Company::where('id', $request->companycode)->first();  
            //     $serialnoparameters = $company->company_name.$company->pan_no.$company->gst_no;
            //     $insert_serialno->ocfno = $request->ocfno;
            //     $insert_serialno->comp_name = $company->company_name;
            //     $insert_serialno->pan = $company->pan_no;
            //     $insert_serialno->gst = $company->gst_no;
            //     $insert_serialno->serialno_issue_date = $request->ocf_date;
            //     $insert_serialno->serialno_parameters = $serialnoparameters;
            //     $insert_serialno->serialno = md5($serialnoparameters);
            //     $insert_serialno->save();
        
            //     return response()->json(['message' => 'OCF Updated Successfully','status' => '0','OCF' => $request->all(), 'Serialno' => $insert_serialno ]);
                
            // }
        }
    }

    public function getcompany($customerid)
    {
        $company = Company::where('customercode', $customerid)->get();
        return response()->json(['message' => 'Company','status' => '0','Company' => $company ]);
    }
    
    public function serialnootp(Request $request)
    {
        $Mobile = OCFCustomer::where('phone', $request->phone)->first();
        
        $serialno_ocfno =  OCF::where('ocfno', $request->ocfno)->first();
        if(empty($serialno_ocfno->ocfno))
        {
            return response()->json(['message' => 'OTP Send Unsuccessfully', 'status' => 1]);
        }
        else{
            
            $otp = Str::random(6);
        }
    
        if(empty($Mobile->phone))
        {
            return response()->json(['message' => 'Invalid Mobile Number', 'status' => 1]);
        }
        else
        {   // $verifyotps = User::where('mobile', $Mobile)->get();

            $update = OCFCustomer::where('phone', $Mobile->phone)->update(array('otp' => $otp));
        
            $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$Mobile->phone&message=Your%20otp%20for%20Acme%20catalogue%20is%20$otp";
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
            return response()->json(['message' => 'OTP Send Successfully', 'status' => 0, 'OTP' => $otp]);
        }
    }

    public function serialnoverifyotp(Request $request)
    {
        $verify = OCFCustomer::where('phone', $request->phone)->first();
        if(empty($verify->phone))
        {
            return response()->json(['message' => 'Invalid Mobile Number', 'status' => 1]);
        }
        else
        {
            $verifyotp = [
                'isverified' => $request->isverified
            ];
        }
        
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

}
