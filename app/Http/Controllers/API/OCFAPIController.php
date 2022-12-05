<?php

namespace App\Http\Controllers\API;

use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\API\OCF;
use App\Models\API\Company; 
use App\Models\API\Serialno;
use App\Models\API\OCFModule;
use App\Models\API\OCFCustomer;
use App\Models\API\BroadcastMessage;
use App\Models\API\Packages;
use App\Models\API\SubPackages;

use function PHPUnit\Framework\returnSelf;

class OCFAPIController extends Controller
{    
    public function customercreate(Request $request)
    {
        $key = "YsfaHZ7FCKJcAEb7UuTX+QCQzJa7kR1bMflozJzmyOY=";
        //Filter Customer data id wise


        $customerdata = DB::table('customer_master')
                            ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name),"'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode', 
                            DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'), 
                            DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'), 
                            DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'),  
                           'customer_master.address1', 'customer_master.address2', 'customer_master.state', 
                            'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson', 
                            'customer_master.packagecode', 'customer_master.subpackagecode')
                            ->where('id','=',$request->customercode)
                            ->first();
        
        //IF customer Exist Get customerdata
        if($customerdata)
        {
            return response()->json(['message' => 'Customer  Already Exist','status' => 0,'Customer' => $customerdata]);
        }
        else
        {
            //validation
            $rules = array(
                'name' => 'required',
                'entrycode' => '',
                'phone' => 'required|digits:10',
                'email' => '',
                'address1' => 'required',
                'address2' => '',
                'state' => '',
                'district' => '',
                'taluka' => '',
                'city' => '',
                'whatsappno' => 'required|digits:10',
                'concernperson' => 'required',
                'packagecode' => 'required',
                'subpackagecode' => 'required',  
                'customercode' => ''           
            );
            $validator = Validator::make($request->all(), $rules);
            //Validation Fails
            if ($validator->fails()) 
            {
                return response()->json([
                    'message' => 'Invalid params passed', 
                    'errors' => $validator->errors()
                ], 422);
            }
            else
            {
                //save New Customer 
                $role_id = 10;
                $password = 'AcmeAcme1994';
                $ocfcustomerflastid = OCFCustomer::orderBy('id', 'desc')->first();
                
                if($ocfcustomerflastid == null)
                {
                   $id = 1;
                  
                   $insert_customers = DB::table('customer_master')
                    ->insert( array(
                                    'id' => $request->customercode,
                                    'entrycode' =>$id,
                                    'name' => DB::raw("HEX(AES_ENCRYPT('$request->name' , '$key'))"), 
                                    'phone' => DB::raw("HEX(AES_ENCRYPT('$request->phone', '$key'))"),
                                    'email' => DB::raw("HEX(AES_ENCRYPT('$request->email', '$key'))"),
                                    'address1' => $request->address1,
                                    'address2' => $request->address2 == null ? "" : $request->address2,
                                    'state' => $request->state  == null ? "" : $request->state,
                                    'district' => $request->district == null ? "" : $request->district,
                                    'taluka' => $request->taluka == null ? "" : $request->taluka,
                                    'city' => $request->city  == null ? "" : $request->city,
                                    'whatsappno' => DB::raw("HEX(AES_ENCRYPT('$request->whatsappno', '$key'))"),   
                                    'concernperson' => $request->concernperson,
                                    'packagecode' => $request->packagecode,
                                    'subpackagecode' => $request->subpackagecode,
                                    'password' => $password,
                                    'role_id' => $role_id,
                                   )
                            );
                
                   $cust = DB::table('customer_master')
                   ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode', 
                   DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'), 
                   DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'), 
                   DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'), 
                   'customer_master.address1', 'customer_master.address2', 'customer_master.state', 
                   'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson', 
                   'customer_master.packagecode', 'customer_master.subpackagecode')
                   ->where('id','=',1)
                   ->first();     
                }
                else
                {
                                             
                    //Get Customer Data with customercode = 0                  
                    if($request->customercode == 0 || empty($request->customercode))
                    {
                        $insert_customers = DB::table('customer_master')
                        ->insert( array(
                                        // 'id' => $request->customercode,
                                        'entrycode' =>$ocfcustomerflastid->id+1,
                                        'name' => DB::raw("HEX(AES_ENCRYPT('$request->name' , '$key'))"), 
                                        'phone' => DB::raw("HEX(AES_ENCRYPT('$request->phone', '$key'))"),
                                        'email' => DB::raw("HEX(AES_ENCRYPT('$request->email', '$key'))"),
                                        'address1' => $request->address1,
                                        'address2' => $request->address2 == null ? "" : $request->address2,
                                        'state' => $request->state  == null ? "" : $request->state,
                                        'district' => $request->district == null ? "" : $request->district,
                                        'taluka' => $request->taluka == null ? "" : $request->taluka,
                                        'city' => $request->city  == null ? "" : $request->city,
                                        'whatsappno' => DB::raw("HEX(AES_ENCRYPT('$request->whatsappno', '$key'))"),   
                                        'concernperson' => $request->concernperson,
                                        'packagecode' => $request->packagecode,
                                        'subpackagecode' => $request->subpackagecode,
                                        'password' => $password,
                                        'role_id' => $role_id,
                                       )
                                );
                        $cust = DB::table('customer_master')
                                    ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode', 
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'), 
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'), 
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'), 
                                    'customer_master.address1', 'customer_master.address2', 'customer_master.state', 
                                    'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson', 
                                    'customer_master.packagecode', 'customer_master.subpackagecode')
                                    ->where('id','=',$ocfcustomerflastid->id+1)
                                    ->first();
                    }
                    //Get Customer Data with requested customercode  
                    else if($request->customercode)
                    {
                        $insert_customers = DB::table('customer_master')
                        ->insert( array(
                                        'id' => $request->customercode,
                                        'entrycode' =>$request->customercode,
                                        'name' => DB::raw("HEX(AES_ENCRYPT('$request->name' , '$key'))"), 
                                        'phone' => DB::raw("HEX(AES_ENCRYPT('$request->phone', '$key'))"),
                                        'email' => DB::raw("HEX(AES_ENCRYPT('$request->email', '$key'))"),
                                        'address1' => $request->address1,
                                        'address2' => $request->address2 == null ? "" : $request->address2,
                                        'state' => $request->state  == null ? "" : $request->state,
                                        'district' => $request->district == null ? "" : $request->district,
                                        'taluka' => $request->taluka == null ? "" : $request->taluka,
                                        'city' => $request->city  == null ? "" : $request->city,
                                        'whatsappno' => DB::raw("HEX(AES_ENCRYPT('$request->whatsappno', '$key'))"),   
                                        'concernperson' => $request->concernperson,
                                        'packagecode' => $request->packagecode,
                                        'subpackagecode' => $request->subpackagecode,
                                        'password' => $password,
                                        'role_id' => $role_id,
                                       )
                                );
                        $cust = DB::table('customer_master')
                                    ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode', 
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'), 
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'), 
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'),
                                    'customer_master.address1', 'customer_master.address2', 'customer_master.state', 
                                    'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson', 
                                    'customer_master.packagecode', 'customer_master.subpackagecode' )
                                    ->where('id','=',$request->customercode)
                                    ->first();   
                    }
                    else
                    {
                        return response()->json(['message' => 'Customer Not Saved', 'status' =>1]);
                    }
                }
                return response()->json(['message' => 'Customer Saved Successfully','status' => 0,'Customer' => $cust]);
            }
        }    
    }
       

    public function company(Request $request)   // add New Company against Customer
        {
            $key = "YsfaHZ7FCKJcAEb7UuTX+QCQzJa7kR1bMflozJzmyOY=";
            //Filter Customer
            $customer = OCFCustomer::where('id', $request->customercode)->first();
            //If Customer Exist
            if($customer == null)
            {
                return response()->json(['message' => 'Customer Not Exist', 'status' => 1]);
            }
            else
            {
                //Check Company Exist or Not
                $compquery = DB::table('company_master')
                                ->select('company_master.id','company_master.customercode','company_master.companyname', 'company_master.panno', 'company_master.gstno', 'company_master.InstallationType', 'company_master.InstallationDesc')
                                ->where('customercode', '=', $request->customercode)
                                ->where('companyname', '=', DB::raw("HEX(AES_ENCRYPT('$request->company_name' , '$key'))"))
                                ->where('panno', '=', DB::raw("HEX(AES_ENCRYPT('$request->pan_no' , '$key'))"))
                                ->where('gstno', '=', DB::raw("HEX(AES_ENCRYPT('$request->gst_no' , '$key'))"))
                                ->first();
                
                //If Company Not Exist
                if(empty($compquery))
                {
                    $rules = array(
                        'customercode' => 'required',
                        'company_name' => 'required',
                               
                    );
                    $validator = Validator::make($request->all(), $rules);
                    //Validation Fails
                    if ($validator->fails()) 
                    {
                        return response()->json([
                            'message' => 'Invalid params passed', 
                            'errors' => $validator->errors()
                        ], 422);
                    }
                    else
                    {
                        //Insert Company using Encryption
                        $company = DB::table('company_master')
                                        ->insert( array( 
                                        'customercode' => $request->customercode,
                                        'companyname' => DB::raw("HEX(AES_ENCRYPT('$request->company_name' , '$key'))"), 
                                        'panno' => DB::raw("HEX(AES_ENCRYPT('$request->pan_no', '$key'))"),
                                        'gstno' => DB::raw("HEX(AES_ENCRYPT('$request->gst_no', '$key'))"),
                                        'InstallationType' => DB::raw("IF('$request->InstallationType' = 0, 1, 1)"),
                                        'InstallationDesc'=>DB::raw("IF('$request->InstallationDesc' = '','Main', 'Main')")
                                            ) );
                        
                        $comp = DB::table('company_master')
                                    ->select('company_master.id','company_master.customercode','company_master.companyname', 'company_master.panno', 'company_master.gstno', 'company_master.InstallationType', 'company_master.InstallationDesc')
                                    ->where('customercode', '=', $request->customercode)
                                    ->where('companyname', '=', DB::raw("HEX(AES_ENCRYPT('$request->company_name' , '$key'))"))
                                    ->where('panno', '=', DB::raw("HEX(AES_ENCRYPT('$request->pan_no' , '$key'))"))
                                    ->where('gstno', '=', DB::raw("HEX(AES_ENCRYPT('$request->gst_no' , '$key'))"))
                                    ->first();

                        //Decrypt Saved Company Data
                        $getcomp =  DB::table('company_master')
                                    ->select('company_master.id','company_master.customercode', DB::raw('CAST(AES_DECRYPT(UNHEX(companyname), "'.$key.'") AS CHAR) AS companyname'), 
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(panno), "'.$key.'") AS CHAR) AS panno'), 
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(gstno), "'.$key.'") AS CHAR) AS gstno'), 
                                    'company_master.InstallationType', 'company_master.InstallationDesc')
                                    ->where('id','=', $comp->id)
                                    ->first();

                        return response()->json(['message' => 'Company Saved Successfully', 'status' => 0, 'Company' => $getcomp]);
                    }
                }
                else
                {
                    //If Company Already Exist
                    $existcomp = DB::table('company_master')
                    ->select('company_master.id','company_master.customercode', DB::raw('CAST(AES_DECRYPT(UNHEX(companyname), "'.$key.'") AS CHAR) AS companyname'), 
                    DB::raw('CAST(AES_DECRYPT(UNHEX(panno), "'.$key.'") AS CHAR) AS panno'), 
                    DB::raw('CAST(AES_DECRYPT(UNHEX(gstno), "'.$key.'") AS CHAR) AS gstno'), 
                    'company_master.InstallationType', 'company_master.InstallationDesc')
                    ->where('id','=', $compquery->id)
                    ->first();
        
                    return response()->json(['message' => 'Company Already Exist', 'status' => 0, 'Company' => $existcomp]);
                }
            }
        }

    public function OCF(Request $request)             // create new ocf
    {
        $key = "YsfaHZ7FCKJcAEb7UuTX+QCQzJa7kR1bMflozJzmyOY=";
        $data1=[];
        //Set series
        $series = OCF::orderBy('series', 'desc')->first('series');
        if ($request->series==null) $series="OCF";
        //Get DOC No
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
            if($ocflastid == null)
            {
                $id =0;
                $time = date('d-m-Y');               
                $insert_ocf = new OCF();
                $insert_ocf->customercode = $request->customercode;
                $insert_ocf->companycode = $request->companycode;
                $insert_ocf->DocNo = ($id+1);
                $insert_ocf->series =($series);
                $insert_ocf->ocf_date = $time;
                $insert_ocf->save();
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
            }
            
            
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
                    }
                }  
                $customer = OCFCustomer::where('id', $request->customercode)->first();
                if($ocfmoduledata == null)
                {
                    return response()->json(['message' => 'OCF not Saved']);
                }
                else
                {
                    $checkcustomer =  DB::table('customer_master')
                                        ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode', 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'), 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'), 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'), 'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time', 
                                        'customer_master.role_id', 'customer_master.address1', 'customer_master.address2', 'customer_master.state', 
                                        'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson', 
                                        'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password', 'customer_master.active')
                                        ->where('id','=',$request->customercode)
                                        ->first();
                                       
                    if($checkcustomer == null)
                    {
                        return response()->json(['Message' => 'Invalid Mobile No', 'status' => 1]);
                    }
                             
                    $otp =  rand(100000, 999999);

                    $update_otp = OCFCustomer::where('id', $request->customercode)->update(['otp' => $otp]);
                    $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(48);
                                        
                    Log::info("otp = ".$otp);
                    Log::info("otp_expires_time = ".$otp_expires_time);
                    Cache::put('otp_expires_time', $otp_expires_time);
                                
                    $users = OCFCustomer::where('id','=',$request->customercode)->update(['otp_expires_time' => $otp_expires_time]);
                                        
                    $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkcustomer->whatsappno&message=Your%20unique%20registration%20key%20for%20Acme%20is%20$otp";
                    $params = 
                            [   
                                "to" => ["type" => "whatsapp", "number" => $customer->whatsappno],
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
                          
                }
                return response()->json(['message' => 'OCF Created Successfully OTP Generated Update','status' => 0,'OCF' => $insert_ocf, 'Module' => $data1]);  
            }
        }    
    }

    public function  srno_validity(Request $request)
    {
        $key = "YsfaHZ7FCKJcAEb7UuTX+QCQzJa7kR1bMflozJzmyOY=";
        $rules = array(
            'customercode' => 'required',
            'company_name' => 'required',
            'pan_no' => '',
            'gst_no' => ''
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
            $customer = OCFCustomer::where('id', $request->customercode)->first();
            //Check Customer
            if($customer == null)   return response()->json(['message' => 'Customer not Exist', 'status' => 1]);
            $companydata = Company::where('id', $request->companycode)->first();
            $checkcompanydata = Company::where('customercode', $request->customercode)->where('id', $request->companycode)->first();
            if($checkcompanydata == null)
            {
                return response()->json(['message' => 'Company Not Exist', 'status' => 1]);
            }
            // Check Company
            if($companydata == null) return response()->json(['message' => 'Company Not Exist', 'status' => 1]);
        
            $company =  DB::table('company_master')
                        ->select('company_master.id','company_master.customercode','company_master.companyname', 'company_master.panno', 'company_master.gstno', 'company_master.InstallationType', 'company_master.InstallationDesc')
                        ->where('customercode', '=', $request->customercode)
                        ->where('id', '=', $request->companycode)
                        ->where('companyname', '=', DB::raw("HEX(AES_ENCRYPT('$request->company_name' , '$key'))"))
                        ->where('panno', '=', DB::raw("HEX(AES_ENCRYPT('$request->pan_no' , '$key'))"))
                        ->where('gstno', '=', DB::raw("HEX(AES_ENCRYPT('$request->gst_no' , '$key'))"))
                        ->first();

            //Company Decrypt Data
            $compupdate = DB::table('company_master')
                        ->select('company_master.id','company_master.customercode', DB::raw('CAST(AES_DECRYPT(UNHEX(companyname), "'.$key.'") AS CHAR) AS companyname'), 
                        DB::raw('CAST(AES_DECRYPT(UNHEX(panno), "'.$key.'") AS CHAR) AS panno'), 
                        DB::raw('CAST(AES_DECRYPT(UNHEX(gstno), "'.$key.'") AS CHAR) AS gstno'), 
                        'company_master.InstallationType', 'company_master.InstallationDesc','company_master.expirydates', 'company_master.updated_at')
                        ->where('id','=', $request->companycode)
                        ->first();
            //Customer Decrypt Data
            $checkcustomer =  DB::table('customer_master')
                        ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode', 
                        DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'), 
                        DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'), 
                        DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'), 'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time', 
                        'customer_master.role_id', 'customer_master.address1', 'customer_master.address2', 'customer_master.state', 
                        'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson', 
                        'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password', 'customer_master.active')
                        ->where('id','=',$request->customercode)
                        ->first();
        
            // If Company Not Exist
            if($company == null)
            {               
                $time = date('Y-m-d');
                $expirytime = date('d-m-Y', strtotime($time . " +5 minutes") );
                // OTP Generate IF Company Data Not match
                if($request->otp == "")
                {
                    $checkcustomer =  DB::table('customer_master')
                                        ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode', 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'), 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'), 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'), 'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time', 
                                        'customer_master.role_id', 'customer_master.address1', 'customer_master.address2', 'customer_master.state', 
                                        'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson', 
                                        'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password', 'customer_master.active')
                                        ->where('id','=',$request->customercode)
                                        ->first();
                    
                    $otp =  rand(100000, 999999);
                    
                    $update_verifyotp = OCFCustomer::where('id', $request->customercode)->update(['otp' => $otp]);
                
                    $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(1);
                    
                    Log::info("otp = ".$otp);
                    Log::info("otp_expires_time = ".$otp_expires_time);
                    Cache::put('otp_expires_time', $otp_expires_time);
                    
                    $users = OCFCustomer::where('id','=',$request->customercode)->update(['otp_expires_time' => $otp_expires_time]);
                    
                    $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkcustomer->whatsappno&message=Your%20otp%20for%20$compupdate->companyname%20is%20$otp";
                
                    $params = 
                    [   
                        "to" => ["type" => "whatsapp", "number" => $customer->whatsappno],
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
                // Check OTP When Company Updated
                else
                {
                    // IF OTP Match
                    if($request->otp == $customer->otp)
                    {
                        //IF OTP verified
                        $updateotp =  OCFCustomer::where('id', $customer->id)->update(['isverified'=> 1]);
                    
                        // Check Serial No Exist or Not
                        // $checkserial = Serialno::where('ocfno', $request->companycode)->where('serialno_issue_date', $request->issuedate)->where('serialno', $request->serialno)->orderBy('id', 'desc')->first();
                        $checkserial =  DB::table('serialno')
                                            ->select('serialno.ocfno','serialno.comp_name','serialno.pan', 'serialno.gst', 'serialno.serialno_issue_date', 'serialno.serialno_validity', 'serialno.otp_flag', 'serialno.serialno', 'serialno.id')
                                            ->where('ocfno', '=', $request->companycode)
                                            ->where('serialno_issue_date', '=', $request->issuedate)
                                            ->where('serialno', '=', DB::raw("HEX(AES_ENCRYPT('$request->serialno' , '$key'))"))
                                            ->first();
                        //Check Packagecode if 2 then expirytime is 1 Month  
                        if($customer->packagecode == 2)
                        {
                            $time = date('Y-m-d');
                            $expirytime = date('d-m-Y', strtotime($time . " + 1 month") );
                            $companydata->expirydates = $expirytime;
                            $companydata->save();
                                
                        }
                        // Check Packagecode if 3 then expirytime is 6 Month
                        elseif($customer->packagecode == 3) 
                        {
                            $time = date('Y-m-d');
                            $expirytime = date('d-m-Y', strtotime($time . " + 6 month") );
                            $companydata->expirydates = $expirytime;
                            $companydata->save();
                        }
                        else
                        {
                            return response()->json(['message' => 'Invalid Package', 'status' => 1]);
                        }
                        //If Serialno correct
                        if($checkserial)
                        {  
                                // Update Company
                                $updatecomp = DB::table('company_master')->where('id', '=', $request->companycode)
                                ->update(array(
                                    'companyname' => DB::raw("HEX(AES_ENCRYPT('$request->company_name' , '$key'))"), 
                                    'panno' => DB::raw("HEX(AES_ENCRYPT('$request->pan_no', '$key'))"),
                                    'gstno' => DB::raw("HEX(AES_ENCRYPT('$request->gst_no', '$key'))"),
                                ));
                                $updatecompany = DB::table('company_master')
                                                ->select('company_master.id','company_master.customercode', DB::raw('CAST(AES_DECRYPT(UNHEX(companyname), "'.$key.'") AS CHAR) AS companyname'), 
                                                DB::raw('CAST(AES_DECRYPT(UNHEX(panno), "'.$key.'") AS CHAR) AS panno'), 
                                                DB::raw('CAST(AES_DECRYPT(UNHEX(gstno), "'.$key.'") AS CHAR) AS gstno'), 
                                                'company_master.InstallationType', 'company_master.InstallationDesc','company_master.expirydates', 'company_master.updated_at')
                                                ->where('id','=', $request->companycode)
                                                ->first();
                              
                                // Modules
                                $module = OCF::select(DB::raw('max(acme_module.ModuleName) as ModuleName'),   DB::raw('max(ocf_modules.expiryDate) as ExpiryDate'),  DB::raw('max(acme_module_type.expiry) as Expiry'),DB::raw('SUM(ocf_modules.quantity) AS Quantity'))
                                                        ->join('ocf_modules', 'ocf_master.id', '=', 'ocf_modules.ocfcode')
                                                        ->join('acme_module', 'ocf_modules.modulecode', '=', 'acme_module.id')
                                                        ->join('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
                                                        ->where('ocf_master.customercode', $request->customercode)
                                                        ->where('ocf_master.companycode', $request->companycode)
                                                        ->whereRaw('IF(acme_module_type.expiry = 1, IF(ocf_modules. expirydate >= DATE(NOW()),1,0),1)')
                                                        ->groupBy('ocf_modules.modulecode')
                                                        ->get();
                                
                                // Serial No 
                                $serial = md5($module);
                                $time = date('d-m-Y');
                                $expirydate = date('d-m-Y', strtotime($time . " +1 year") );
                                $insert_serialno = DB::table('serialno')
                                        ->insert( array( 
                                        'ocfno' => $request->companycode,
                                        'comp_name' => DB::raw("HEX(AES_ENCRYPT('$updatecompany->companyname' , '$key'))"), 
                                        'pan' => DB::raw("HEX(AES_ENCRYPT('$updatecompany->panno', '$key'))"),
                                        'gst' => DB::raw("HEX(AES_ENCRYPT('$updatecompany->gstno', '$key'))"),
                                        'serialno_issue_date' => $time,
                                        'serialno_validity'=>$expirydate,
                                        'serialno' => DB::raw("HEX(AES_ENCRYPT('$serial' , '$key'))"), 
                                            ) );
                                $sr = Serialno::orderBy('id', 'desc')->first();
                                $srid = DB::table('serialno')->where('id', '=', $sr->id)->first();
                                            
                                //Decrypt Saved Serial Data
                                $getserial =  DB::table('serialno')
                                            ->select('serialno.id','serialno.ocfno', DB::raw('CAST(AES_DECRYPT(UNHEX(comp_name), "'.$key.'") AS CHAR) AS comp_name'), 
                                            DB::raw('CAST(AES_DECRYPT(UNHEX(pan), "'.$key.'") AS CHAR) AS pan'), 
                                            DB::raw('CAST(AES_DECRYPT(UNHEX(gst), "'.$key.'") AS CHAR) AS gst'), 
                                            'serialno.serialno_issue_date', 'serialno.serialno_validity', 
                                            DB::raw('CAST(AES_DECRYPT(UNHEX(serialno), "'.$key.'") AS CHAR) AS serialno'), 'serialno.otp_flag')
                                            ->where('id','=', $srid->id)
                                            ->first();
                                return response()->json(['message' => 'Company Updated', 'status' => 0, 'Company' => $updatecompany,'Modules' => $module, 'Serial' => $getserial]);
                            }
                            //If Serialno Incorrect
                            else
                            {
                                if($request->serialotp == "")
                                {                                
                                    $otp = rand(100000, 999999);
                                
                                    $update_verifyotp = OCFCustomer::where('id', $request->customercode)->update(['serialotp' => $otp]);
                                    $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(1);
                                        
                                    Log::info("otp = ".$otp);
                                    Log::info("otp_expires_time = ".$otp_expires_time);
                                    Cache::put('otp_expires_time', $otp_expires_time);
                                
                                    $users = OCFCustomer::where('id','=',$request->customercode)->update(['otp_expires_time' => $otp_expires_time]);

                                    $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkcustomer->whatsappno&message=Your%20otp%20for%20$compupdate->companyname%20is%20$otp";
                        
                                    $params = 
                                        [   
                                            "to" => ["type" => "whatsapp", "number" => $customer->whatsappno],
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
                                    if($request->serialotp == $customer->serialotp)
                                    {
                                        $updateotp =  OCFCustomer::where('id', $customer->id)->update(['isverified'=> 1]);
                                        
                                        $updatecomp = DB::table('company_master')->where('id', '=', $request->companycode)
                                                        ->update(array(
                                                            'companyname' => DB::raw("HEX(AES_ENCRYPT('$request->company_name' , '$key'))"), 
                                                            'panno' => DB::raw("HEX(AES_ENCRYPT('$request->pan_no', '$key'))"),
                                                            'gstno' => DB::raw("HEX(AES_ENCRYPT('$request->gst_no', '$key'))"),
                                                        ));
                                        
                                        if($customer->packagecode == 2)
                                        {
                                            $time = date('Y-m-d');
                                            $expirytime = date('d-m-Y', strtotime($time . " + 1 month") );
                                            $companydata->expirydates = $expirytime;
                                            $companydata->save();
                                        }
                                        elseif($customer->packagecode == 3) 
                                        {
                                            $time = date('Y-m-d');
                                            $expirytime = date('d-m-Y', strtotime($time . " + 6 month") );
                                            $companydata->expirydates = $expirytime;
                                            $companydata->save();       
                                        }
                                        else
                                        {
                                            return response()->json(['message' => 'Invalid Package', 'status' => 1]);
                                        }
                                        $updatecomp = DB::table('company_master')->where('id', '=', $request->companycode)
                                                            ->update(array(
                                                                'companyname' => DB::raw("HEX(AES_ENCRYPT('$request->company_name' , '$key'))"), 
                                                                'panno' => DB::raw("HEX(AES_ENCRYPT('$request->pan_no', '$key'))"),
                                                                'gstno' => DB::raw("HEX(AES_ENCRYPT('$request->gst_no', '$key'))"),
                                                            ));
                                        $updatecompany1 = DB::table('company_master')
                                                            ->select('company_master.id','company_master.customercode', DB::raw('CAST(AES_DECRYPT(UNHEX(companyname), "'.$key.'") AS CHAR) AS companyname'), 
                                                            DB::raw('CAST(AES_DECRYPT(UNHEX(panno), "'.$key.'") AS CHAR) AS panno'), 
                                                            DB::raw('CAST(AES_DECRYPT(UNHEX(gstno), "'.$key.'") AS CHAR) AS gstno'), 
                                                            'company_master.InstallationType', 'company_master.InstallationDesc','company_master.expirydates', 'company_master.updated_at')
                                                            ->where('id','=', $request->companycode)
                                                            ->first();
                        
                                        $module =OCF::select(DB::raw('max(acme_module.ModuleName) as ModuleName'),   DB::raw('max(ocf_modules.expiryDate) as ExpiryDate'),  DB::raw('max(acme_module_type.expiry) as Expiry'),DB::raw('SUM(ocf_modules.quantity) AS Quantity'))
                                                        ->join('ocf_modules', 'ocf_master.id', '=', 'ocf_modules.ocfcode')
                                                        ->join('acme_module', 'ocf_modules.modulecode', '=', 'acme_module.id')
                                                        ->join('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
                                                        ->where('ocf_master.customercode', $request->customercode)
                                                        ->where('ocf_master.companycode', $request->companycode)
                                                        ->whereRaw('IF(acme_module_type.expiry = 1, IF(ocf_modules. expirydate >= DATE(NOW()),1,0),1)')
                                                        ->groupBy('ocf_modules.modulecode')
                                                        ->get();
                                        
                                        $serial = md5($module);
                                        $time = date('d-m-Y');
                                        $expirydate = date('d-m-Y', strtotime($time . " +1 year") );
                                        $insert_serialno = DB::table('serialno')
                                                                ->insert( array( 
                                                                'ocfno' => $request->companycode,
                                                                'comp_name' => DB::raw("HEX(AES_ENCRYPT('$updatecompany1->companyname' , '$key'))"), 
                                                                'pan' => DB::raw("HEX(AES_ENCRYPT('$updatecompany1->panno', '$key'))"),
                                                                'gst' => DB::raw("HEX(AES_ENCRYPT('$updatecompany1->gstno', '$key'))"),
                                                                'serialno_issue_date' => $time,
                                                                'serialno_validity'=>$expirydate,
                                                                'serialno' => DB::raw("HEX(AES_ENCRYPT('$serial' , '$key'))"), 
                                                                'otp_flag' =>1) );

                                        $sr = Serialno::orderBy('id', 'desc')->first();
                                        $srid = DB::table('serialno')->where('id', '=', $sr->id)->first();
                                                    
                                        //Decrypt Saved Serial Data
                                        $getserial =  DB::table('serialno')
                                                    ->select('serialno.id','serialno.ocfno', DB::raw('CAST(AES_DECRYPT(UNHEX(comp_name), "'.$key.'") AS CHAR) AS comp_name'), 
                                                    DB::raw('CAST(AES_DECRYPT(UNHEX(pan), "'.$key.'") AS CHAR) AS pan'), 
                                                    DB::raw('CAST(AES_DECRYPT(UNHEX(gst), "'.$key.'") AS CHAR) AS gst'), 
                                                    'serialno.serialno_issue_date', 'serialno.serialno_validity', 
                                                    DB::raw('CAST(AES_DECRYPT(UNHEX(serialno), "'.$key.'") AS CHAR) AS serialno'), 'serialno.otp_flag')
                                                    ->where('id','=', $srid->id)
                                                    ->first();
                                            
                                        return response()->json(['message' => 'Company And Serialno Updated', 'status' => 0, 'Company data' => $updatecompany1,'Modules' => $module, 'Serialno' => $getserial]);
                                    }
                                    else
                                    {  
                                        return response()->json(['status' => 1 , 'message' => 'Invalid OTP']);
                                    }
                                }        
                            
                        }    
                    } 
                    // OTP Not Match
                    else
                    {
                        return response()->json(['message' => 'Invalid OTP', 'status' => 1]);
                    }  
                }
            }
            // If Company Exist
            else
            {
                // $checkserial = Serialno::where('ocfno', $request->companycode)->where('serialno_issue_date', $request->issuedate)->where('serialno', $request->serialno)->orderBy('id', 'desc')->first();
                $checkserial =  DB::table('serialno')
                                    ->select('serialno.ocfno','serialno.comp_name','serialno.pan', 'serialno.gst', 'serialno.serialno_issue_date', 'serialno.serialno_validity', 'serialno.otp_flag', 'serialno.serialno', 'serialno.id')
                                    ->where('ocfno', '=', $request->companycode)
                                    ->where('serialno_issue_date', '=', $request->issuedate)
                                    ->where('serialno', '=', DB::raw("HEX(AES_ENCRYPT('$request->serialno' , '$key'))"))
                                    ->first();
               
                if($customer->packagecode == 2)
                {
                    $time = date('Y-m-d');
                    $expirytime = date('d-m-Y', strtotime($time . " + 1 month") );
                    $companydata->expirydates = $expirytime;
                    $companydata->save();
                                
                }
                elseif($customer->packagecode == 3) 
                {
                    $time = date('Y-m-d');
                    $expirytime = date('d-m-Y', strtotime($time . " + 6 month") );
                    $companydata->expirydates = $expirytime;
                    $companydata->save();                
                }
                else
                {
                    return response()->json(['message' => 'Invalid Package', 'status' => 1]);
                }
               
                if($checkserial)
                {       
                    $module = OCF::select(DB::raw('max(acme_module.ModuleName) as ModuleName'),   DB::raw('max(ocf_modules.expiryDate) as ExpiryDate'),  DB::raw('max(acme_module_type.expiry) as Expiry'),DB::raw('SUM(ocf_modules.quantity) AS Quantity'))
                                        ->join('ocf_modules', 'ocf_master.id', '=', 'ocf_modules.ocfcode')
                                        ->join('acme_module', 'ocf_modules.modulecode', '=', 'acme_module.id')
                                        ->join('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
                                        ->where('ocf_master.customercode', $request->customercode)
                                        ->where('ocf_master.companycode', $request->companycode)
                                        ->whereRaw('IF(acme_module_type.expiry = 1, IF(ocf_modules. expirydate >= DATE(NOW()),1,0),1)')
                                        ->groupBy('ocf_modules.modulecode')
                                        ->get();
                                    
                        $serial = md5($module);
                        $time = date('d-m-Y');
                        $expirydate = date('d-m-Y', strtotime($time . " +1 year") );
        
                        $insert_serialno = DB::table('serialno')
                                        ->insert( array( 
                                        'ocfno' => $request->companycode,
                                        'comp_name' => DB::raw("HEX(AES_ENCRYPT('$compupdate->companyname' , '$key'))"), 
                                        'pan' => DB::raw("HEX(AES_ENCRYPT('$compupdate->panno', '$key'))"),
                                        'gst' => DB::raw("HEX(AES_ENCRYPT('$compupdate->gstno', '$key'))"),
                                        'serialno_issue_date' => $time,
                                        'serialno_validity'=>$expirydate,
                                        'serialno' => DB::raw("HEX(AES_ENCRYPT('$serial' , '$key'))"), 
                                            ) );
                            $sr = Serialno::orderBy('id', 'desc')->first();
                            $srid = DB::table('serialno')->where('id', '=', $sr->id)->first();
                                          
                            //Decrypt Saved Serial Data
                            $getserial =  DB::table('serialno')
                                        ->select('serialno.id','serialno.ocfno', DB::raw('CAST(AES_DECRYPT(UNHEX(comp_name), "'.$key.'") AS CHAR) AS comp_name'), 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(pan), "'.$key.'") AS CHAR) AS pan'), 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(gst), "'.$key.'") AS CHAR) AS gst'), 
                                        'serialno.serialno_issue_date', 'serialno.serialno_validity', 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(serialno), "'.$key.'") AS CHAR) AS serialno'))
                                        ->where('id','=', $srid->id)
                                        ->first();
                        return response()->json(['message' => 'Serialno Updated', 'status' => 0, 'Company' => $compupdate,'Modules' => $module, 'Serial' => $getserial]);    
                }
                else
                {
                    if($request->serialotp == "")
                    { 
                        $otp = rand(100000, 999999);
                                
                        $updateotp =  OCFCustomer::where('id', $customer->id)->update(['serialotp'=> $otp]);
                        $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(1);
                                        
                        Log::info("otp = ".$otp);
                        Log::info("otp_expires_time = ".$otp_expires_time);
                        Cache::put('otp_expires_time', $otp_expires_time);
                            // $user = Customers::where('phone','=',$request->phone)->update(['otp' => $otp]);
                        $users = OCFCustomer::where('id','=',$request->customercode)->update(['otp_expires_time' => $otp_expires_time]);
                                        

                        $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkcustomer->whatsappno&message=Your%20otp%20for%20$compupdate->companyname%20is%20$otp";
            
                        $params = 
                                [   
                                    "to" => ["type" => "whatsapp", "number" => $customer->whatsappno],
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
                        
                        if($request->serialotp == $customer->serialotp)
                        {
                            $updateotp =  OCFCustomer::where('id', $customer->id)->update(['isverified'=> 1]); 
                        
                            if($customer->packagecode == 2)
                            {
                                $time = date('Y-m-d');
                                $expirytime = date('d-m-Y', strtotime($time . " + 1 month") );
                                $companydata->expirydates = $expirytime;
                                $companydata->save();
                                            
                            }
                            elseif($customer->packagecode == 3) 
                            {
                                $time = date('Y-m-d');
                                $expirytime = date('d-m-Y', strtotime($time . " + 6 month") );
                                $companydata->expirydates = $expirytime;
                                $companydata->save();
                                                
                            }
                            else
                            {
                                return response()->json(['message' => 'Invalid Package', 'status' => 1]);
                            }

                            $module =OCF::select(DB::raw('max(acme_module.ModuleName) as ModuleName'),   DB::raw('max(ocf_modules.expiryDate) as ExpiryDate'),  DB::raw('max(acme_module_type.expiry) as Expiry'),DB::raw('SUM(ocf_modules.quantity) AS Quantity'))
                                            ->join('ocf_modules', 'ocf_master.id', '=', 'ocf_modules.ocfcode')
                                            ->join('acme_module', 'ocf_modules.modulecode', '=', 'acme_module.id')
                                            ->join('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
                                            ->where('ocf_master.customercode', $request->customercode)
                                            ->where('ocf_master.companycode', $company->id)
                                            ->whereRaw('IF(acme_module_type.expiry = 1, IF(ocf_modules. expirydate >= DATE(NOW()),1,0),1)')
                                            ->groupBy('ocf_modules.modulecode')
                                            ->get();
                                        
                            $serial = md5($module);
                            $time = date('d-m-Y');
                            $expirydate = date('d-m-Y', strtotime($time . " +1 year") );

                            $insert_serialno = DB::table('serialno')
                                        ->insert( array( 
                                        'ocfno' => $request->companycode,
                                        'comp_name' => DB::raw("HEX(AES_ENCRYPT('$compupdate->companyname' , '$key'))"), 
                                        'pan' => DB::raw("HEX(AES_ENCRYPT('$compupdate->panno', '$key'))"),
                                        'gst' => DB::raw("HEX(AES_ENCRYPT('$compupdate->gstno', '$key'))"),
                                        'serialno_issue_date' => $time,
                                        'serialno_validity'=>$expirydate,
                                        'serialno' => DB::raw("HEX(AES_ENCRYPT('$serial' , '$key'))"), 
                                        'otp_flag' => 1));

                            $sr = Serialno::orderBy('id', 'desc')->first();
                            $srid = DB::table('serialno')->where('id', '=', $sr->id)->first();
                                          
                            //Decrypt Saved Serial Data
                            $getserial =  DB::table('serialno')
                                        ->select('serialno.id','serialno.ocfno', DB::raw('CAST(AES_DECRYPT(UNHEX(comp_name), "'.$key.'") AS CHAR) AS comp_name'), 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(pan), "'.$key.'") AS CHAR) AS pan'), 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(gst), "'.$key.'") AS CHAR) AS gst'), 
                                        'serialno.serialno_issue_date', 'serialno.serialno_validity', 
                                        DB::raw('CAST(AES_DECRYPT(UNHEX(serialno), "'.$key.'") AS CHAR) AS serialno'), 'serialno.otp_flag')
                                        ->where('id','=', $srid->id)
                                        ->first();
                                            
                            return response()->json(['message' => 'Serialno Updated', 'status' => 0, 'Company' => $compupdate,'Modules' => $module, 'Serial' => $getserial]);
                        }
                        else
                        {  
                            return response()->json(['status' => 1 , 'message' => 'Invalid OTP']);
                        }
                    }            
                }
            }   
        }
    }

    public function serialnoverifyotp(Request $request)  //Verify Otp
    {
        $key = "YsfaHZ7FCKJcAEb7UuTX+QCQzJa7kR1bMflozJzmyOY=";
        //Customer Data 
        $customer =  DB::table('customer_master')
                    ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode', 
                    DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'), 
                    DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'), 
                    DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'), 'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time', 
                    'customer_master.role_id', 'customer_master.address1', 'customer_master.address2', 'customer_master.state', 
                    'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson', 
                    'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password', 'customer_master.active')
                    ->where('otp','=',$request->otp)
                    ->first();
        // $customer = OCFCustomer::where('otp', $request->otp)->first();
      
        if($customer == null)
        {
            return response()->json(['Message' => 'Invalid OTP', 'status' => 1]);
        }
        
        $time = date('d-m-Y H:i:s');
      
        //OTP Expiry Time
        if($time >= $customer->otp_expires_time)
        {
            return response()->json(['status' => 1, 'message' => 'OTP Expired']);
        }
        //verify OTP
        else if($request->otp == $customer->otp)
        { 
            $custpmerupdate = OCFCustomer::where('id', $customer->id)->update(['isverified'=> 1]);

            //Company Data
            $company = DB::table('company_master')
                        ->select('company_master.id','company_master.customercode', DB::raw('CAST(AES_DECRYPT(UNHEX(companyname), "'.$key.'") AS CHAR) AS companyname'), 
                        DB::raw('CAST(AES_DECRYPT(UNHEX(panno), "'.$key.'") AS CHAR) AS panno'), 
                        DB::raw('CAST(AES_DECRYPT(UNHEX(gstno), "'.$key.'") AS CHAR) AS gstno'),
                        'company_master.InstallationType', 'company_master.InstallationDesc',
                        DB::raw('CONCAT(ocf_master.Series, ocf_master.DocNo) as OCFNo'), 'ocf_master.ocf_date')
                        ->join('ocf_master', 'company_master.id', '=', 'ocf_master.companycode')
                        ->where('company_master.customercode','=', $customer->id)
                        ->get();
            // $company = Company::where('customercode', $customer->id)->get();
            
            return response()->json(['status' => 0, 'message' => 'Verified', 'Customer' => $customer, 'Company' => $company ] );
        }
        else
        {
            return response()->json(['status' => 1 , 'message' => 'Invalid OTP']);
        }
    }

    public function pincode(Request $request)
    {
        //Filter City Data pincode wise
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

    public function autologin(Request $request) 
    {
        //Filter Customer Data id wise
        $user = OCFCustomer::where('id', $request->customercode)->where('active', 1)->first();
        //check password is correct or not
        if(!$user || $request->password !=$user->password)
        {
            return response(['message' => 'Invalid Credentials', 'status' => '1']);
        }
        else
        {
            //create token
            $token = $user->createToken('LoginSerialNoToken')->plainTextToken;
            
            $response = [
              
                 'token' => $token,
                 'status' => '0' 
        ];
        //Generate Autologin URL
          $autologin = 'https://crm.acmeinfovision.com/customer/customerlogin/'.$request->customercode.'/'.$token ;
    
            return response()->json(['message' => 'Auto Login', 'status' => 0, 'URL' => $autologin ]);
        }
    }

    public function broadcast_messages(Request $request)
    {
        //Filter Data of messagetarget, customercode, rolecode, companycode
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

    public function date_time()
    {
        $time = date('d-m-Y H:i:s');
        return response()->json(['message' => 'Server Date Time', 'status' => 0, 'Date Time' => $time]);
    }



    public function serialnootp(Request $request)          // Currenly unused
    {
            $customer = OCFCustomer::where('id', $request->customercode)->first();
            $checkcustomer =  DB::table('customer_master')
                            ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode', 
                            DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'), 
                            DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'), 
                            DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'), 'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time', 
                            'customer_master.role_id', 'customer_master.address1', 'customer_master.address2', 'customer_master.state', 
                            'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson', 
                            'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password', 'customer_master.active')
                            ->where('id','=',$request->customercode)
                            ->first();

                if($checkcustomer == null)
                {
                    return response()->json(['Message' => 'Invalid Mobile No', 'status' => 1]);
                }
            
                $otp =  rand(100000, 999999);
                
                $update_verifyotp = OCFCustomer::where('id', $request->customercode)->update(['otp' => $otp]);
             
                $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(1);
                
                Log::info("otp = ".$otp);
                Log::info("otp_expires_time = ".$otp_expires_time);
                Cache::put('otp_expires_time', $otp_expires_time);
                // $user = Customers::where('phone','=',$request->phone)->update(['otp' => $otp]);
                $users = OCFCustomer::where('id','=',$request->customercode)->update(['otp_expires_time' => $otp_expires_time]);
                
                $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkcustomer->whatsappno&message=Your%20otp%20for%20Acme%20catalogue%20is%20$otp";
                $params = 
                [   
                    "to" => ["type" => "whatsapp", "number" => $customer->whatsappno],
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
                return response()->json(['message' => 'OTP Generated','status' => 2]);
    }

    public function broadcastmessage(Request $request)   //Broadcastmessage store API
    {
        $rules = array(
            'messagetarget' => 'required',
            'customercode' => 'required',
            'datefrom' => 'required', 
            'todate' => 'required',
            'messagetitle' => 'required',
           
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

    public function callcenterid(Request $request)
    {
        $customer = OCFCustomer::where('id', $request->customercode)->first();
        return response()->json($customer);
    }
    
}
