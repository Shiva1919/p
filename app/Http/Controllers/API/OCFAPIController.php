<?php

namespace App\Http\Controllers\API;

use Validator;
use Carbon\Carbon;
use App\Models\API\OCF;
use function Psy\debug;
use App\Models\API\Company;
use App\Models\API\Modules;
use Illuminate\Support\Str;
use App\Models\API\Serialno;
use Illuminate\Http\Request;
use App\Models\API\OCFModule;
use App\Models\API\OCFCustomer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\API\BroadcastMessage;
use App\Models\API\Packages;
use App\Models\API\SubPackages;
use Illuminate\Support\Facades\Cache;

class OCFAPIController extends Controller
{    
    public function customercreate(Request $request)
    {
        // $dataaa=[];
        $customercode= OCFCustomer::where('id', $request->customercode)->first();
        if($customercode )
        {
            return response()->json(['message' => 'Customer  Already Exist','status' => 0,'Customer' => $customercode]);
        }
        else
        {
            $rules = array(
                'name' => 'required',
                'entrycode' => '',
                'phone' => 'required',
                'email' => '',
                'address1' => 'required',
                'address2' => '',
                'state' => '',
                'district' => '',
                'taluka' => '',
                'city' => '',
                'whatsappno' => 'required',
                'concernperson' => 'required',
                'packagecode' => 'required',
                'subpackagecode' => 'required',  
                'customercode' => 'required'           
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
                $insert_customers->entrycode = $ocfcustomerflastid->id+1;
                $insert_customers->phone = $request->phone;
                $insert_customers->email = $request->email;
                $insert_customers->address1 = $request->address1;
                $insert_customers->address2 = $request->address2;
                $insert_customers->state = $request->state;
                $insert_customers->district = $request->district;
                $insert_customers->taluka = $request->taluka;
                $insert_customers->city = $request->city;
                $insert_customers->whatsappno = $request->whatsappno;
                $insert_customers->concernperson = $request->concernperson;
                $insert_customers->packagecode = $request->packagecode;
                $insert_customers->subpackagecode = $request->subpackagecode;
                $insert_customers->id = $request->customercode;
                $insert_customers->password = $password;
                $insert_customers->role_id = $role_id;
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
                return response()->json(['message' => 'Customer Saved Successfully','status' => 0,'Customer' => $insert_customers]);
            }
        }    
            // $request->name.$request->phone.$request->packagename;
        }
       

        public function company(Request $request)   // add New Company against Customer
        {
            $customer = OCFCustomer::where('id', $request->customercode)->first();
            if($customer == null)
            {
                return response()->json(['message' => 'Customer Not Exist', 'status' => 1]);
            }
            else
            {
                
                $companydata = Company::where('customercode', $request->customercode)
                                    ->where('company_name', $request->company_name)
                                    ->where('pan_no', $request->pan_no)
                                    ->where('gst_no', $request->gst_no)
                                    // ->where('InstallationDesc', $request->InstallationDesc)
                                    ->first();
             
                $company = new Company();
                $company->customercode     = $request->customercode;
                $company->company_name     = $request->company_name;
                $company->pan_no           = $request->pan_no;
                $company->gst_no           = $request->gst_no;
                $company->InstallationType = $request->InstallationType;
                $company->InstallationDesc = $request->InstallationDesc;
                if($company->InstallationType == 0  || $company->InstallationType == null) $company->InstallationType = 1;
                if($company->InstallationDesc == "" || $company->InstallationDesc == null) $company->InstallationDesc = "Main";
                
                if(empty($companydata))
                {
                    $company->save();
                    return response()->json(['message' => 'Company Saved Successfully', 'status' => 0, 'Company' => $company]);
                }
                else
                {
                    return response()->json(['message' => 'Company Already Exist', 'status' => 0, 'Company' => $companydata]);
                }
            }
        }

        public function serialnootp(Request $request)          // Currenly unused
        {
            $Mobile = $request->input('phone');
            $checkmobile =  OCFCustomer::where('phone', $request->input('phone'))->first();
            $checkentrycode =  OCFCustomer::where('entrycode', $request->input('entrycode'))->first();
            // $serialno_ocfno =  OCF::where('ocfno', $request->ocfno)->first();
            // $checkmobile1 =  Customers::where('phone', $Mobile)->first('phone');
            if($checkentrycode == null && $checkmobile == null)
            {
                return response()->json(['Message' => 'Invalid Code or Mobile No', 'status' => 1]);
            }
            if($checkmobile == null )
            {
                return response()->json(['Message' => 'Mobile No invalid', 'status' => 1]);
            }
            if($checkentrycode == null)
            {
                return response()->json(['Message' => 'Invalid Code ', 'status' => 1]);
            }
           
            $otp =   Str::random(6);
    
            $phone =  OCFCustomer::where('entrycode', $request->input('entrycode'))->first();
          
            $verifyotp = [
                'otp' => $otp,
            ];
            $update_verifyotp = $phone->update($verifyotp); 
            $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(1);
           
            Log::info("otp = ".$otp);
            Log::info("otp_expires_time = ".$otp_expires_time);
            Cache::put('otp_expires_time', $otp_expires_time);
            // $user = Customers::where('phone','=',$request->phone)->update(['otp' => $otp]);
            $users = OCFCustomer::where('phone','=',$request->phone)->update(['otp_expires_time' => $otp_expires_time]);
            
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
            return response()->json(['OTP' => $otp , 'status' => 0, 'message' => 'OTP Generated']);
        }
    
        public function serialnoverifyotp(Request $request)  // currenlty unused
        {
            $customer =  OCFCustomer::where('otp', $request->otp)->first();
            if($customer == null)
            {
                return response()->json(['Message' => 'Invalid Mobile No ', 'status' => 1]);
            }
            $time = date('d-m-Y H:i:s');
            
            if($time <= $customer->otp_expires_time)
            {
                return response()->json(['status' => 1, 'message' => 'OTP Expired']);
            }
            else if($request->otp == $customer->otp)
            {
                $verifyotp = [
                    'isverified' => 1
                ]; 
                $customer->update($verifyotp);  
                $company = DB::table('company_master')
                                ->select('company_master.*',DB::raw('CONCAT(ocf_master.Series, ocf_master.DocNo) as OCFNo'), 'ocf_master.ocf_date')
                                ->join('ocf_master', 'company_master.customercode', '=', 'ocf_master.customercode')
                                ->where('company_master.customercode', $customer->id)
                                ->get();
                
                
                // $modules = DB::table('ocf_modules')->where('ocfcode', $ocf[0]->id)->get();
                return response()->json(['status' => 0, 'message' => 'Verified', 'Customer' => $customer, 'Company' => $company ] );
            }
            else
            {
                return response()->json(['status' => 1 , 'message' => 'Invalid OTP']);
            }
        }

    public function OCF(Request $request)             // create new ocf
    {
        $data1=[];
        $series = OCF::orderBy('series', 'desc')->first('series');
        $series= $request->series;
        if ($request->series==null) $series="OCF";
        $ocflastid = OCF::where('series', $series)->orderBy('DocNo', 'desc')->first();
        $rules = array(
            'customercode' => 'required',
            'companycode' => 'required',
        
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
            $time = date('d-m-Y');               
            $insert_ocf = new OCF();
            $insert_ocf->customercode = $request->customercode;
            $insert_ocf->companycode = $request->companycode;
            $insert_ocf->DocNo = ($ocflastid->DocNo+1);
            $insert_ocf->series =($series);
            $insert_ocf->ocf_date = $time;
            $insert_ocf->save();
            
            if(!empty($insert_ocf->id))
                    {                    
                        foreach ($request->Data as $data ) 
                        {
                            $getmoduledata = OCFCustomer::leftjoin('acme_package', 'customer_master.packagecode', '=','acme_package.id')
                                                        ->leftjoin('acme_module', 'acme_package.id', '=', 'acme_module.producttype')
                                                        ->leftjoin('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
                                                        ->where('customer_master.id', $request->customercode)
                                                        ->where('acme_module.ModuleName',$data['modulename'])
                                                        ->get(['acme_module.id as moduleid', 'acme_module.ModuleName as modulename', 'acme_module_type.id as acme_module_typeid','acme_module_type.moduletype as acme_module_moduletype']);

                            if(count($getmoduledata)==0)
                            {
                                return response()->json(['message' => 'Check Module','status' => 1]);
                            }
                            else
                            {
                                $data=[
                                    'ocfcode'=> $insert_ocf->id,
                                    'modulename'=> $data['modulename'],
                                    'quantity'=> $data['quantity'],
                                    'expirydate'=> $data['expirydate'],
                                    'amount'=> $data['amount'],
                                    'moduletypes' => $getmoduledata[0]['acme_module_typeid'],
                                    'modulecode' => $getmoduledata[0]['moduleid'],
                                ];
                                array_push($data1,$data);
                                $ocfmoduledata = OCFModule::create($data);
                                $customer = OCFCustomer::where('id', $request->customercode)->first();
                                if($ocfmoduledata == null)
                                {
                                    return response()->json(['message' => 'OCF not Saved']);
                                }
                                else
                                {
                                        $checkmobile =  OCFCustomer::where('phone', $customer->phone)->first();
                            
                                        if($checkmobile == null)
                                        {
                                            return response()->json(['Message' => 'Invalid Mobile No', 'status' => 1]);
                                        }
                                        if($checkmobile == null )
                                        {
                                            return response()->json(['Message' => 'Mobile No invalid', 'status' => 1]);
                                        }
                                    
                                        $otp =  rand(100000, 999999);
                                        
                                        $phone =  OCFCustomer::where('id', $request->customercode)->where('phone', $customer->phone)->first();
                                        
                                        $verifyotp = [
                                            'otp' => $otp,
                                        ];
                                        $update_verifyotp = $phone->update($verifyotp); 
                                        $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(48);
                                        
                                        Log::info("otp = ".$otp);
                                        Log::info("otp_expires_time = ".$otp_expires_time);
                                        Cache::put('otp_expires_time', $otp_expires_time);
                                        // $user = Customers::where('phone','=',$request->phone)->update(['otp' => $otp]);
                                        $users = OCFCustomer::where('phone','=',$customer->phone)->update(['otp_expires_time' => $otp_expires_time]);
                                        
                                        $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkmobile->phone&message=Your%20unique%20registration%20key%20for%20Acme%20is%20$otp";
                                
                                        $params = 
                                        [   
                                            "to" => ["type" => "whatsapp", "number" => $customer->phone],
                                            "from" => ["type" => "whatsapp", "number" => "9422031763"],
                                            "message" => 
                                            [
                                                "content" => 
                                                [
                                                    "type" => "text",
                                                    "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
                                                ]
                                            ]
                                        ];
                                        $headers = ["Authorization" => "Basic " . base64_encode(env('60ab9945c306cdffb00cf0c2') . ":" . env('60ab9945c306cdffb00cf0c2'))];
                                        $client = new \GuzzleHttp\Client();
                                        $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
                                        $data = $response->getBody();
                                        Log::Info($data);
                                        return response()->json(['message' => 'OCF Created Successfully OTP Generated Update','status' => 0,'OCF' => $insert_ocf, 'Module' => $data1]);
                                }
                            }
                        }
                       
                        
                    }
                }
        
    }
    
    public function companyocf(Request $request) //Serial no AP
    {
        $company = Company::where('id', $request->companycode)->first(['company_master.id','company_master.company_name', 'company_master.pan_no', 'company_master.gst_no']);
        
        if($company== null)
        {
            return response()->json(['message' => 'Company Not Exist', 'status' => 1]);
        }
        else
        {
            $time = date('d-m-Y');
            // DB::raw('max(CASE WHEN acme_module_type.expiry = 1 THEN 0 ELSE 1 END) as Expired')
            // DB::raw('(CASE WHEN acme_module_type.expiry = 1  WHEN ocf_modules.expirydate == $time 1 THEN 0 ELSE 1 END) as expired_modules')
            $module = DB::table('ocf_master')
                            ->select(DB::raw('max(acme_module.ModuleName) as ModuleName'), DB::raw('max(ocf_modules.expiryDate) as ExpiryDate'), DB::raw('max(acme_module_type.expiry) as Expiry'),DB::raw('SUM(ocf_modules.quantity) AS Quantity'))
                            ->join('ocf_modules', 'ocf_master.id', '=', 'ocf_modules.ocfcode')
                            ->join('acme_module', 'ocf_modules.modulecode', '=', 'acme_module.id')
                            ->join('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
                            ->where('ocf_master.customercode', $request->customercode)
                            ->where('ocf_master.companycode', $request->companycode)
                            ->whereRaw('IF(acme_module_type.expiry = 1, IF(ocf_modules. expirydate >= DATE(NOW()),1,0),1)')
                            ->groupBy('ocf_modules.modulecode')
                            ->get();
            // return $module; 
            // $x=json_encode($module);           
            // $ciphertext = base64_encode(openssl_encrypt($x, "AES-128-CBC", "AcmeInfovision", OPENSSL_RAW_DATA, openssl_random_pseudo_bytes(16)));
            // var_dump($ciphertext);

            $serial = md5($module);
            $expirydate = date('d-m-Y', strtotime($time . " +1 year") );
            $insert_serialno = new Serialno();
            $insert_serialno->ocfno = $request->companycode;
            $insert_serialno->comp_name = $company->company_name;
            $insert_serialno->pan = $company->pan_no;
            $insert_serialno->gst = $company->gst_no;
            $insert_serialno->serialno_issue_date = $time;
            $insert_serialno->serialno_validity = $expirydate;
            $insert_serialno->serialno = $serial;
            $insert_serialno->save();
            return response()->json(['message' => 'Company', 'status' => 0, 'Company' => $company,'Modules' => $module, 'Serial' => $insert_serialno]);
        }
    }

    public function broadcastmessage(Request $request)
    {
        $rules = array(
            'messagetarget' => 'required',
            'customercode' => 'required',
            'datefrom' => 'required|date_format:d-m-Y H:i:s', 
            'todate' => 'required|date_format:d-m-Y H:i:s',
            'messagetitle' => 'required',
            // 'messagedesc ' => 'required',
            // 'active' => 'required',
            // 'howmanydaystodisplay' => 'required',
            // 'allowtomarkasread'=> '',
            // 'rolecode' => '',
            // 'url' => '',
            // 'specialkeytoclose' => ''
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
            $broadcast_message = new BroadcastMessage();
            $broadcast_message->MessageTarget = $request->messagetarget;
            $broadcast_message->CustomerCode = $request->customercode;
            $broadcast_message->PackageType = $request->packagecode;
            $broadcast_message->PackageSubType = $request->subpackagecode;
            $broadcast_message->CompanyCode = $request->companycode;
            $broadcast_message->GstType = $request->gstcode;
            $broadcast_message->DateFrom = $request->datefrom;
            $broadcast_message->ToDate = $request->todate;
            $broadcast_message->MessageTitle = $request->messagetitle;
            $broadcast_message->MessageDesc = $request->messagedesc;
            $broadcast_message->Active = $request->active;
            $broadcast_message->HowManyDaysToDisplay = $request->howmanydaystodisplay;
            $broadcast_message->AllowToMarkAsRead = $request->allowtomarkasread;
            $broadcast_message->RoleCode = $request->rolecode;
            $broadcast_message->URLString = $request->url;
            $broadcast_message->SpecialKeyToClose = $request->specialkeytoclose;
            $broadcast_message->MessageDescMarathi = $request->messagedescmarathi;
            $broadcast_message->MessageDescHindi = $request->messagedeschindi;
            $broadcast_message->MessageDescKannada = $request->messagedesckannada;
            $broadcast_message->MessageDescGujarathi = $request->messagedescgujarathi;
            $broadcast_message->save();

            if($request->messagetarget == 1)
            {
                $packages = Packages::all();
                $customers = OCFCustomer::all();
                return response()->json(['message' => 'Broadcast Messages', 'status' => 0, 'Packages' => $packages, 'Customers' => $customers,'Broadcast Message' => $broadcast_message]);
            }
            elseif($request->messagetarget == 2)
            {
                $package = Packages::where('id', $request->packagecode)->first();
                $subpackage = SubPackages::where('id', $request->subpackagecode)->get();
                if($package == null)
                {
                    return response()->json(['message' => 'Invalid Package', 'status' => 1]);
                }
                else{
                    
                    return response()->json(['message' => 'Broadcast Messages', 'status' => 0, 'Package' => $package, 'Subpackage' => $subpackage, 'Broadcast Message' => $broadcast_message]);
                }
            }
            elseif($request->messagetarget == 3)
            {
                $customer = OCFCustomer::where('id', $request->customercode)->first();
                if($customer == null)
                {
                    return response()->json(['message' => 'Invalid Customer', 'status' => 1]);
                }
                else{
                    return response()->json(['message' => 'Broadcast Messages', 'status' => 0, 'Customer' => $customer, 'Broadcast Message' => $broadcast_message]);
                }
            }
            else{
                return response()->json(['message' => 'Invalid Message Type', 'status' => 1]);
            }
        }        
    }

    function sr_validity(Request $request)
    {
        
        $request->validate([ 
            'customercode' => 'required',
            'company_name' => 'required',
            'pan_no' => 'required',
            'gst_no' => 'required'
        ]);
        $customer = OCFCustomer::where('id', $request->customercode)->first();
        
        if($customer == null)   return response()->json(['message' => 'Customer not Exist', 'status' => 1]);
        $companydata = Company::where('id', $request->companycode)->first();
        if($companydata == null) return response()->json(['message' => 'Company Not Exist', 'status' => 1]);

        $company = Company::where('customercode', $request->customercode)
                            ->where('company_name', $request->company_name)
                            ->where('pan_no', $request->pan_no)
                            ->where('gst_no', $request->gst_no)
                            
                            ->first();
        
        if($company == null)
        {    
            
            $time = date('Y-m-d ');
            $expirytime = date('d-m-Y', strtotime($time . " +5 minutes") );
            
            if($request->otp == "")
            {
                $checkmobile =  OCFCustomer::where('phone', $customer->phone)->first();
           
                if($checkmobile == null)
                {
                    return response()->json(['Message' => 'Invalid Mobile No', 'status' => 1]);
                }
                if($checkmobile == null )
                {
                    return response()->json(['Message' => 'Mobile No invalid', 'status' => 1]);
                }
            
                $otp =  rand(100000, 999999);
                
                $phone =  OCFCustomer::where('id', $request->customercode)->where('phone', $customer->phone)->first();
                
                $verifyotp = [
                    'otp' => $otp,
                ];
                $update_verifyotp = $phone->update($verifyotp); 
                $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(1);
                
                Log::info("otp = ".$otp);
                Log::info("otp_expires_time = ".$otp_expires_time);
                Cache::put('otp_expires_time', $otp_expires_time);
                // $user = Customers::where('phone','=',$request->phone)->update(['otp' => $otp]);
                $users = OCFCustomer::where('phone','=',$customer->phone)->update(['otp_expires_time' => $otp_expires_time]);
                
                $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkmobile->phone&message=Your%20otp%20for%20Acme%20catalogue%20is%20$otp";
        
                $params = 
                [   
                    "to" => ["type" => "whatsapp", "number" => $customer->phone],
                    "from" => ["type" => "whatsapp", "number" => "9422031763"],
                    "message" => 
                    [
                        "content" => 
                        [
                            "type" => "text",
                            "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
                        ]
                    ]
                ];
                $headers = ["Authorization" => "Basic " . base64_encode(env('60ab9945c306cdffb00cf0c2') . ":" . env('60ab9945c306cdffb00cf0c2'))];
                $client = new \GuzzleHttp\Client();
                $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
                $data = $response->getBody();
                Log::Info($data);
                return response()->json(['message' => 'OTP Generated Update Company','status' => 2]);  
            }
            else
            {
                if($request->otp == $customer->otp)
                {
                    $verifyotp = [
                        'isverified' => 1
                    ]; 
                    $customer->update($verifyotp);  
                    
                    $updatecompany=Company::find($request->companycode);
                    
                    $updatecompany->update($request->all());

                    $module = DB::table('ocf_master')
                                    ->select(DB::raw('max(acme_module.ModuleName) as ModuleName'),   DB::raw('max(ocf_modules.expiryDate) as ExpiryDate'),  DB::raw('max(acme_module_type.expiry) as Expiry'),DB::raw('SUM(ocf_modules.quantity) AS Quantity'))
                                    ->join('ocf_modules', 'ocf_master.id', '=', 'ocf_modules.ocfcode')
                                    ->join('acme_module', 'ocf_modules.modulecode', '=', 'acme_module.id')
                                    ->join('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
                                    ->where('ocf_master.customercode', $request->customercode)
                                    ->where('ocf_master.companycode', $request->companycode)
                                    ->whereRaw('IF(acme_module_type.expiry = 1, IF(ocf_modules. expirydate >= DATE(NOW()),1,0),1)')
                                    ->groupBy('ocf_modules.modulecode')
                                    ->get();

                    $company = Company::where('id', $request->companycode)->first(['company_master.id','company_master.customercode','company_master.company_name', 'company_master.pan_no', 'company_master.gst_no']);
                    $customer = OCFCustomer::where('id', $company->customercode)->first();
                    
                    if($customer->packagecode == 2)
                    {
                        $time = date('Y-m-d ');
                        $expirytime = date('d-m-Y', strtotime($time . " + 1 month") );
                        $company->expirydates = $expirytime;
                        $company->save();
                    
                    }
                    elseif($customer->packagecode == 3) 
                    {
                        $time = date('Y-m-d ');
                        $expirytime = date('d-m-Y', strtotime($time . " + 6 month") );
                        $company->expirydates = $expirytime;
                        $company->save();
                        
                    }
                    else{
                        return response()->json(['message' => 'Invalid Package', 'status' => 1]);
                    }
                    $serial = md5($module);
                    $time = date('d-m-Y');
                    $expirydate = date('d-m-Y', strtotime($time . " +1 year") );
                    $insert_serialno = new Serialno();
                    $insert_serialno->ocfno = $request->companycode;
                    $insert_serialno->comp_name = $company->company_name;
                    $insert_serialno->pan = $company->pan_no;
                    $insert_serialno->gst = $company->gst_no;
                    $insert_serialno->serialno_issue_date = $time;
                    $insert_serialno->serialno_validity = $expirydate;
                    $insert_serialno->serialno = $serial;
                    $insert_serialno->save();               
                   
                    return response()->json(['status' => 0, 'message' => 'Company Details Updated', 'Company' => $company, 'Modules' => $module, 'Serial' => $insert_serialno ]);
                }
                else
                {
                    return response()->json(['status' => 1 , 'message' => 'Invalid OTP']);
                }
            }           
        }
        else if($request->issuedate)
        {
            $checkserial = Serialno::where('ocfno', $request->companycode)->where('serialno_issue_date', $request->issuedate)->where('serialno', $request->serialno)->orderBy('id', 'desc')->first();
            $company = Company::where('id', $company->id)->first(['company_master.id','company_master.customercode','company_master.company_name', 'company_master.pan_no', 'company_master.gst_no']);
            $customer = OCFCustomer::where('id', $company->customercode)->first();
            
            if($customer->packagecode == 2)
            {
                $time = date('Y-m-d ');
                $expirytime = date('d-m-Y', strtotime($time . " + 1 month") );
                $company->expirydates = $expirytime;
                $company->save();
              
            }
            elseif($customer->packagecode == 3) 
            {
                $time = date('Y-m-d ');
                $expirytime = date('d-m-Y', strtotime($time . " + 6 month") );
                $company->expirydates = $expirytime;
                $company->save();
                
            }
            else{
                return response()->json(['message' => 'Invalid Package', 'status' => 1]);
            }
            if($checkserial)
            {  
                if($company== null)
                {
                    return response()->json(['message' => 'Company Not Exist', 'status' => 1]);
                }
                else
                {
                    $module = DB::table('ocf_master')
                                    ->select(DB::raw('max(acme_module.ModuleName) as ModuleName'),   DB::raw('max(ocf_modules.expiryDate) as ExpiryDate'),  DB::raw('max(acme_module_type.expiry) as Expiry'),DB::raw('SUM(ocf_modules.quantity) AS Quantity'))
                                    ->join('ocf_modules', 'ocf_master.id', '=', 'ocf_modules.ocfcode')
                                    ->join('acme_module', 'ocf_modules.modulecode', '=', 'acme_module.id')
                                    ->join('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
                                    ->where('ocf_master.customercode', $request->customercode)
                                    ->where('ocf_master.companycode', $company->id)
                                    ->whereRaw('IF(acme_module_type.expiry = 1, IF(ocf_modules. expirydate >= DATE(NOW()),1,0),1)')
                                    ->groupBy('ocf_modules.modulecode')
                                    ->get();
                    
                    // $x=json_encode($module);           
                    // $ciphertext = base64_encode(openssl_encrypt($x, "AES-128-CBC", "AcmeInfovision", OPENSSL_RAW_DATA, openssl_random_pseudo_bytes(16)));
                    // var_dump($ciphertext);
        
                    $serial = md5($module);
                    $time = date('d-m-Y');
                    $expirydate = date('d-m-Y', strtotime($time . " +1 year") );
                    $insert_serialno = new Serialno();
                    $insert_serialno->ocfno = $request->companycode;
                    $insert_serialno->comp_name = $company->company_name;
                    $insert_serialno->pan = $company->pan_no;
                    $insert_serialno->gst = $company->gst_no;
                    $insert_serialno->serialno_issue_date = $time;
                    $insert_serialno->serialno_validity = $expirydate;
                    $insert_serialno->serialno = $serial;
                    $insert_serialno->save();
                    return response()->json(['message' => 'Company', 'status' => 0, 'Company' => $company,'Modules' => $module, 'Serial' => $insert_serialno]);
                }
            }
            else
            {
                if($request->otp == "")
                {
                    $checkmobile =  OCFCustomer::where('phone', $customer->phone)->first();
            
                    if($checkmobile == null)
                    {
                        return response()->json(['Message' => 'Invalid Mobile No', 'status' => 1]);
                    }
                    if($checkmobile == null )
                    {
                        return response()->json(['Message' => 'Mobile No invalid', 'status' => 1]);
                    }
                
                    $otp = rand(100000, 999999);
            
                    $phone =  OCFCustomer::where('id', $request->customercode)->where('phone', $customer->phone)->first();
                    
                    $verifyotp = [
                        'otp' => $otp,
                    ];
                    $update_verifyotp = $phone->update($verifyotp); 
                    $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(1);
                    
                    Log::info("otp = ".$otp);
                    Log::info("otp_expires_time = ".$otp_expires_time);
                    Cache::put('otp_expires_time', $otp_expires_time);
                    // $user = Customers::where('phone','=',$request->phone)->update(['otp' => $otp]);
                    $users = OCFCustomer::where('phone','=',$customer->phone)->update(['otp_expires_time' => $otp_expires_time]);
                    
                    $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkmobile->phone&message=Your%20otp%20for%20Acme%20catalogue%20is%20$otp";
            
                    $params = 
                    [   
                        "to" => ["type" => "whatsapp", "number" => $customer->phone],
                        "from" => ["type" => "whatsapp", "number" => "9422031763"],
                        "message" => 
                        [
                            "content" => 
                            [
                                "type" => "text",
                                "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
                            ]
                        ]
                    ];
                    $headers = ["Authorization" => "Basic " . base64_encode(env('60ab9945c306cdffb00cf0c2') . ":" . env('60ab9945c306cdffb00cf0c2'))];
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
                    $data = $response->getBody();
                    Log::Info($data);
                    return response()->json(['message' => 'OTP Generated Update Serial','status' => 2]);  
                }
                else
                {
                    if($request->otp == $customer->otp)
                    {
                        $verifyotp = [
                            'isverified' => 1
                        ]; 
                        
                        $customer->update($verifyotp);  
                        $customer = OCFCustomer::where('id', $company->customercode)->first();
            
                        if($customer->packagecode == 2)
                        {
                            $time = date('Y-m-d ');
                            $expirytime = date('d-m-Y', strtotime($time . " + 1 month") );
                            $company->expirydates = $expirytime;
                            $company->save();
                        
                        }
                        elseif($customer->packagecode == 3) 
                        {
                            $time = date('Y-m-d ');
                            $expirytime = date('d-m-Y', strtotime($time . " + 6 month") );
                            $company->expirydates = $expirytime;
                            $company->save();
                            
                        }
                        else{
                            return response()->json(['message' => 'Invalid Package', 'status' => 1]);
                        }
                        $module = DB::table('ocf_master')
                                    ->select(DB::raw('max(acme_module.ModuleName) as ModuleName'),   DB::raw('max(ocf_modules.expiryDate) as ExpiryDate'),  DB::raw('max(acme_module_type.expiry) as Expiry'),DB::raw('SUM(ocf_modules.quantity) AS Quantity'))
                                    ->join('ocf_modules', 'ocf_master.id', '=', 'ocf_modules.ocfcode')
                                    ->join('acme_module', 'ocf_modules.modulecode', '=', 'acme_module.id')
                                    ->join('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
                                    ->where('ocf_master.customercode', $request->customercode)
                                    ->where('ocf_master.companycode', $company->id)
                                    ->whereRaw('IF(acme_module_type.expiry = 1, IF(ocf_modules. expirydate >= DATE(NOW()),1,0),1)')
                                    ->groupBy('ocf_modules.modulecode')
                                    ->get();
                    
                        $serial = md5($module);
                        $time = date('d-m-Y ');
                        $expirydate = date('d-m-Y', strtotime($time . " +1 year") );
                        $insert_serialno = new Serialno();
                        $insert_serialno->ocfno = $request->companycode;
                        $insert_serialno->comp_name = $company->company_name;
                        $insert_serialno->pan = $company->pan_no;
                        $insert_serialno->gst = $company->gst_no;
                        $insert_serialno->serialno_issue_date = $time;
                        $insert_serialno->serialno_validity = $expirydate;
                        $insert_serialno->serialno = $serial;
                        $insert_serialno->save();
                        
                        return response()->json(['message' => 'Serialno Updated', 'status' => 0, 'Company' => $company,'Modules' => $module, 'Serial' => $insert_serialno]);
                    }
                    else
                    {  
                        return response()->json(['status' => 1 , 'message' => 'Invalid OTP']);
                    }
                }        
            }
        }
        else{
            return response()->json(['message' => 'Enter Issue Date And Serial NO', 'status' => 1]);
        }
            
    }

    public function date_time()
    {
        $time = date('d-m-Y H:i:s');
        return response()->json(['message' => 'Server Date Time', 'status' => 0, 'Date Time' => $time]);
    }
        
    public function autologin(Request $request) 
    {
        //  $getcustomer = OCFCustomer::where('role_id', 10)->get();
      
        $user = OCFCustomer::where('id', $request->customercode)->where('active', 1)->first();

        if(!$user || $request->password !=$user->password)
        {
            return response(['message' => 'Invalid Credentials', 'status' => '1']);
        }
        else
        {
            $token = $user->createToken('LoginSerialNoToken')->plainTextToken;
            
            $response = [
              
                 'token' => $token,
                 'status' => '0' 
        ];
          $autologin = 'https://crm.acmeinfovision.com/customer/customerlogin/'.$request->customercode.'/'.$token ;
            //  return response($response, 200);
            return response()->json(['message' => 'Auto Login', 'status' => 0, 'URL' => $autologin ]);
        }
    }

    public function pincode(Request $request)
    {
        $city = DB::table('city')->where('pincode', $request->pincode)->get();
       
        if(count($city) == 0)
        {
            return response()->json(['message' => 'Pincode Not Exist', 'status' => 1]);
        }
        else
        {
            $taluka = DB::table('taluka')->where('id', $city[0]->talukaid)->first();
            $district = DB::table('district')->where('id', $taluka->districtid)->first();
            $state = DB::table('state')->where('id', $district->stateid)->first();
            return response()->json(['message' => 'City', 'status' => 0, 'State' => $state, 'District' => $district, 'Taluka' => $taluka, 'City' => $city]);
        }
        
    }

    public function broadcast_messages(Request $request)
    {
        $message = BroadcastMessage::where('MessageTarget', $request->messagetarget)
                                    ->where('CustomerCode', $request->customercode)
                                    ->where('RoleCode', $request->rolecode)
                                    ->where('CompanyCode', $request->companycode)->first();
                                   
        if(empty($message))
        {
            return response()->json(['message' => 'Invalid Data', 'status' => 1]);
        }
        else
        {
            return response()->json(['message' => 'Broadcast Message', 'status' => 0, 'Data' => $message]);
        }   
    }
    
}
