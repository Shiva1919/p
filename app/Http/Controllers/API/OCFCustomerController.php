<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\API\Branchs;
use App\Models\API\Company;
use App\Models\API\Contacts;
use App\Models\API\Modules;
use App\Models\API\OCFCustomer;
use App\Models\Module;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Log;

class OCFCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()

    {           //vikram changes
        $key = config('global.key');
        $customer = DB::table('customer_master')
                        ->select('customer_master.id', DB::raw('AES_DECRYPT(UNHEX(name), "'.$key.'") AS name'), 'customer_master.entrycode',
                        DB::raw('AES_DECRYPT(UNHEX(email), "'.$key.'")  AS email'),
                        DB::raw('AES_DECRYPT(UNHEX(phone), "'.$key.'")  AS phone'),
                        DB::raw('AES_DECRYPT(UNHEX(whatsappno), "'.$key.'")  AS whatsappno'),
                        DB::raw('CAST(AES_DECRYPT(UNHEX(city),"'.$key.'") AS CHAR) AS city'),
                        'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time',
                        'customer_master.role_id', 'customer_master.address1', 'customer_master.address2', 'customer_master.state',
                        'customer_master.district', 'customer_master.taluka',  'customer_master.concernperson',
                        'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password', 'customer_master.active','customer_master.customerlanguage')
                        ->where('role_id',10)->where('active', 1)->orderBy('id','desc')
                        ->get();
                        // DB::raw('AES_DECRYPT(UNHEX(city), "'.$key.'")  AS city'),
                return response()->json($customer);
    }

   public function deactivecustomerslist(){
        $key = config('global.key');

        $customer = DB::table('customer_master')
                        ->select('customer_master.id', DB::raw('AES_DECRYPT(UNHEX(name), "'.$key.'") AS name'), 'customer_master.entrycode',
                        DB::raw('AES_DECRYPT(UNHEX(email), "'.$key.'")  AS email'),
                        DB::raw('AES_DECRYPT(UNHEX(phone), "'.$key.'")  AS phone'),
                        DB::raw('AES_DECRYPT(UNHEX(whatsappno), "'.$key.'")  AS whatsappno'),
                        DB::raw('CAST(AES_DECRYPT(UNHEX(city),"'.$key.'") AS CHAR) AS city'),
                        'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time',
                        'customer_master.role_id', 'customer_master.address1', 'customer_master.address2', 'customer_master.state',
                        'customer_master.district', 'customer_master.taluka',  'customer_master.concernperson',
                        'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password', 'customer_master.active','customer_master.customerlanguage')
                        ->where('role_id',10)->where('active', 0)->orderBy('id','desc')
                        ->get();
                    return response()->json($customer);
    }

    public function getmoduledata($customerid)
    {
        $getmodules = OCFCustomer::leftjoin('acme_package', 'customer_master.packagecode', '=','acme_package.id')
                                ->leftjoin('acme_module', 'acme_package.id', '=', 'acme_module.producttype')
                               ->where('customer_master.id', $customerid)
                               ->where('acme_module.active', 1)
                               ->get('acme_module.*');

        return response()->json($getmodules);
    }


    public function getmoduletypedata($moduleid)
    {
        $data = Modules::leftjoin('acme_module_type', 'acme_module.moduletypeid', '=','acme_module_type.id')->where('acme_module.moduletypeid',$moduleid)->get('acme_module_type.*');
        return $data;
        // $data = Modules::leftjoin('acme_module_type', 'acme_module.moduletypeid', '=','acme_module_type.id')->where('acme_module.moduletypeid',$moduleid)->get();
        // return $data;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
    //vikram changes
    public function store(Request $request)
    {

        $key = config('global.key');
            $rules = array(
                'name' => 'required',
                'entrycode' => '',
                'phone' => 'required',
                'whatsappno' => 'required',
                'email' => 'required',
                'address1' => 'required',
                'state' => 'required',
                'district' => 'required',
                'taluka' => 'required',
                'city' => 'required',
                'active' => 'required',
                'concernperson' => 'required',
                'packagecode' => 'required',
                'subpackagecode' => 'required',
                'customerlanguage' => ''
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

                $ocfcustomerflastid = OCFCustomer::orderBy('id', 'desc')->first();
                if($ocfcustomerflastid){
                    $id=$ocfcustomerflastid->id+1;
                }
                else{
                    $id=1;
                }
                $role_id = 10;
                $password = 'AcmeAcme1994';
                $insert_customers = new OCFCustomer();
                $insert_customers->name = DB::raw("HEX(AES_ENCRYPT('$request->name' , '$key'))");
                $insert_customers->phone = DB::raw("HEX(AES_ENCRYPT('$request->phone' , '$key'))");
                $insert_customers->email =DB::raw("HEX(AES_ENCRYPT('$request->email' , '$key'))");
                $insert_customers->entrycode = $id;
                $insert_customers->whatsappno =DB::raw("HEX(AES_ENCRYPT('$request->whatsappno' , '$key'))");
                $insert_customers->address1 = DB::raw("HEX(AES_ENCRYPT('$request->address1', '$key'))");
                $insert_customers->address2 = DB::raw("HEX(AES_ENCRYPT('$request->address2', '$key'))");
                $insert_customers->state = DB::raw("HEX(AES_ENCRYPT('$request->state', '$key'))");
                $insert_customers->district = DB::raw("HEX(AES_ENCRYPT('$request->district', '$key'))");
                $insert_customers->taluka = DB::raw("HEX(AES_ENCRYPT('$request->taluka', '$key'))");
                $insert_customers->city = DB::raw("HEX(AES_ENCRYPT('$request->city', '$key'))");
                $insert_customers->active = $request->active;
                $insert_customers->concernperson = DB::raw("HEX(AES_ENCRYPT('$request->concernperson', '$key'))");
                $insert_customers->password = $password;
                $insert_customers->role_id = $role_id;
                $insert_customers->packagecode = $request->packagecode;
                $insert_customers->subpackagecode = $request->subpackagecode;
                $insert_customers->customerlanguage = $request->language;
                $insert_customers->save();
                if(!empty($insert_customers->id))
                {
                  $document=$request->Cdocument;
                    for ($i=0; $i < count($request->Cdocument); $i++)
                    {
                        $data=(object) $document[$i];
                        // $responce_data=[];
                        // array_push($responce_data, $document[$i]);

                    DB::table('company_master')
                       ->insert(
                           array(
                               'customercode'=> $insert_customers->id,
                               'companyname'=> DB::raw("HEX(AES_ENCRYPT('$data->company_name','$key'))"),
                               'panno'=> DB::raw("HEX(AES_ENCRYPT('$data->pan_no','$key'))"),
                               'gstno'=>DB::raw("HEX(AES_ENCRYPT('$data->gst_no','$key'))") ,
                               'gsttype' => $data->gst_type,
                               'InstallationType' => $data->InstallationType,
                               'InstallationDesc' => $data->InstallationDesc

                           )
                           );
                    }

                }

                $checkcustomer =  DB::table('customer_master')
                ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode',
                DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'),
                DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'),
                DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'), 'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time',
                'customer_master.role_id', 'customer_master.address1', 'customer_master.address2', 'customer_master.state',
                'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson',
                'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password', 'customer_master.active')
                ->where('id','=',$id)
                ->first();

                $otp =  rand(100000, 999999);
                $update_otp = OCFCustomer::Where('id',$id)->update(['otp' =>  DB::raw("HEX(AES_ENCRYPT('$otp' , '$key'))")]);
                // $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$checkcustomer->whatsappno&message=Your%20ACME%20Customer%20Registration%20is%20Successfully%20Completed.%20\nYour%20Verification%20ID%20-%20$otp%20\n*%20Please%20Do%20Not%20Share%20ID%20With%20Anyone.";
                try
                {

                    $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$checkcustomer->whatsappno&message=Your%20ACME%20Customer%20Registration%20is%20Successfully%20Completed.%20\nYour%20Verification%20ID%20-%20$otp%20\n*%20Please%20Do%20Not%20Share%20ID%20With%20Anyone.";
                    $params =
                    [
                        "to" => ["type" => "whatsapp", "number" => $checkcustomer->whatsappno],
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
                        $dataa = $response->getBody();
                        Log::Info($dataa);
                }
                catch (Throwable $e)
                {
                    report($e);
                    return response()->json(['message' => 'Whatsapp Url Error']);
                }




                return response()->json(['message' => 'Customer Saved Successfully OTP Generated','status' => '0','Customer' => $insert_customers,'Company' => $data]);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

         //vikram changes
    public function show($id)
    {
        $key = config('global.key');
        $getbyid_customer = DB::table('customer_master')
        ->select('customer_master.id', DB::raw('AES_DECRYPT(UNHEX(name), "'.$key.'") AS name'), 'customer_master.entrycode',
        DB::raw('AES_DECRYPT(UNHEX(email), "'.$key.'")  AS email'), DB::raw('AES_DECRYPT(UNHEX(phone), "'.$key.'")  AS phone'),
        DB::raw('AES_DECRYPT(UNHEX(whatsappno), "'.$key.'")  AS whatsappno'), DB::raw('AES_DECRYPT(UNHEX(address1), "'.$key.'")  AS address1'),
        DB::raw('AES_DECRYPT(UNHEX(address2), "'.$key.'")  AS address2'), DB::raw('AES_DECRYPT(UNHEX(state), "'.$key.'")  AS state'),
        DB::raw('AES_DECRYPT(UNHEX(taluka), "'.$key.'") AS taluka'), DB::raw('AES_DECRYPT(UNHEX(district), "'.$key.'")  AS district'),
        DB::raw('AES_DECRYPT(UNHEX(city), "'.$key.'")  AS city'), DB::raw('AES_DECRYPT(UNHEX(concernperson), "'.$key.'")  AS concernperson'),
        'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time', 'customer_master.role_id',
        'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password',
        'customer_master.active','customer_master.customerlanguage')
        ->where('id','=',$id)
        ->first();
        if (is_null($getbyid_customer))
        {
            return $this->sendError('Customer not found.');
        }
        return response()->json($getbyid_customer);
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

         //vikram changes
    public function update(Request $request, OCFCustomer $customer)
    {

        $key = config('global.key');

        $role_id = 10;
        $password = 'AcmeAcme1994';
        $input = $request->all();


        $validator = Validator::make($input, [
            'tenantcode' => '',
            'name' => '',
            'entrycode' => '',
            'phone' => '',
            'whatsappno' => '',
            'email' => '',
            'companyname' => '',
            'address1' => '',
            'address2' => '',
            'state' => '',
            'district' => '',
            'taluka' => '',
            'city' => '',
            'active' => '',
            'concernperson' => '',
            'packagecode' => '',
            'subpackagecode' => '',
            'customerlanguage' => ''
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        // $customer->entrycode = $input['entrycode'];

        $customer->name = DB::raw("HEX(AES_ENCRYPT('$request->name' , '$key'))");
        $customer->phone =DB::raw("HEX(AES_ENCRYPT('$request->phone' , '$key'))");
        $customer->email =DB::raw("HEX(AES_ENCRYPT('$request->email' , '$key'))");
        $customer->whatsappno =DB::raw("HEX(AES_ENCRYPT('$request->whatsappno','$key'))");
        $customer->address1 = DB::raw("HEX(AES_ENCRYPT('$request->address1' , '$key'))");
        $customer->address2 = DB::raw("HEX(AES_ENCRYPT('$request->address2' , '$key'))");
        $customer->state = DB::raw("HEX(AES_ENCRYPT('$request->state' , '$key'))");
        $customer->district = DB::raw("HEX(AES_ENCRYPT('$request->district' , '$key'))");
        $customer->taluka =DB::raw("HEX(AES_ENCRYPT('$request->taluka' , '$key'))");
        $customer->city = DB::raw("HEX(AES_ENCRYPT('$request->city' , '$key'))");
        $customer->active = $request->active;
        $customer->concernperson = DB::raw("HEX(AES_ENCRYPT('$request->concernperson' , '$key'))");
        $customer->packagecode = $request->packagecode;
        $customer->subpackagecode = $request->subpackagecode;
        $customer->role_id = $role_id;
        $customer->password = $password;
        $customer->customerlanguage = $request->language;
        $customer->save();

        if(!empty($request->id)) {
            $document=$request->Cdocument;

            for ($i=0; $i < count($request->Cdocument); $i++)
            {
                $data=(object) $document[$i];

                if ($data->id==0) {

                    DB::table('company_master')
                       ->insert(
                           array(
                               'customercode'=> $customer->id,
                            //    'comapnycode'=> $ocfcompanyflastid->id+1,
                               'companyname'=> DB::raw("HEX(AES_ENCRYPT('$data->company','$key'))"),
                               'panno'=> DB::raw("HEX(AES_ENCRYPT('$data->pan','$key'))"),
                               'gstno'=>DB::raw("HEX(AES_ENCRYPT('$data->gst','$key'))") ,
                               'gsttype' => $data->gst_type,
                               'InstallationType' => $data->InstallationType,
                               'InstallationDesc' => $data->InstallationDesc

                           )

                           );



                }
                else{


                $update_data= Company::find($data->id);
                  $update_data->companyname=DB::raw("HEX(AES_ENCRYPT('$data->company','$key'))");
                  $update_data->panno=DB::raw("HEX(AES_ENCRYPT('$data->pan','$key'))");
                  $update_data->gstno=DB::raw("HEX(AES_ENCRYPT('$data->gst','$key'))");
                  $update_data->InstallationType=$data->InstallationType;
                  $update_data->gsttype=$data->gst_type;
                  $update_data->InstallationDesc=$data->InstallationDesc;
                  $update_data->save();
                }

          }

      }
      return response()->json(['message' => 'Customer Updated Successfully','status' => '0','Customer' => $customer]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OCFCustomer $customer)
    {
        $customer->delete();
        return response()->json([$customer]);
    }

    public function getState()
    {
        $state = DB::table('state')->orderBy('statename','asc')->get();
        return response()->json([$state]);
    }

    public function getDistrict($stateid)
    {
        $data['district'] =DB::table('district')->where("stateid",$stateid)->orderBy('districtname','asc')->get();
        return response()->json($data);
    }

    public function getTaluka($districtid)
    {
        $data['Taluka'] =DB::table('taluka')->where("districtid",$districtid)->orderBy('talukaname','asc')->get();
        return response()->json($data);
    }

    public function getCity($talukaid)
    {
        $data['City'] =DB::table('city')->where("talukaid",$talukaid)->orderBy('cityname','asc')->get();
        return response()->json($data);
    }

    public function branchindex($customerid)
    {

        $branch =  Branchs::where('customercode', $customerid)->orderBy('branchname', 'asc')->get();

        // $branch = Branchs::where('customercode', $customerid)->get();

        return response()->json($branch);
    }

    public function branchshow($customerid, $id)
    {
        $branch = Branchs::where('customercode', $customerid)->find($id);
        return response()->json($branch);
    }

    public function branchstore(Request $request, $customerid)
    {
        $request->validate([
            'branchname' => 'required',
            'branchaddress1' => 'required',
            'branchstate' => 'required',
            'branchdistrict' => 'required',
            'branchtaluka' => 'required',
            'branchcity' => 'required'
        ]);

        $branch = new Branchs();
        $branch->branchname = $request->branchname;
        $branch->branchaddress1 = $request->branchaddress1;
        $branch->branchaddress2 = $request->branchaddress2;
        $branch->branchstate = $request->branchstate;
        $branch->branchdistrict = $request->branchdistrict;
        $branch->branchtaluka = $request->branchtaluka;
        $branch->branchcity = $request->branchcity;
        $branch->customercode = $customerid;
        $branch->save();
        return response()->json([$branch]);
    }

    public function branchupdate(Request $request, $customerid, $id)
    {
        $branch = Branchs::where('customercode', $customerid)->where('id', $id)->first();
        $branchdata = [
            'branchname' => $request->branchname,
            'branchaddress1' => $request->branchaddress1,
            'branchaddress2' => $request->branchaddress2,
            'branchstate' => $request->branchstate,
            'branchdistrict' => $request->branchdistrict,
            'branchtaluka' => $request->branchtaluka,
            'branchcity' => $request->branchcity,
        ];
        $update_branch = $branch->update($branchdata);
        return response()->json([$update_branch]);
    }

    public function branchdelete($customerid, $id)
    {
        $branchdata = Branchs::where('customercode', $customerid)->where('id', $id)->first();
        $delete_branch= $branchdata->delete();
        return response()->json([$delete_branch]);
    }

    public function contactindex($customerid)
    {
        $contact = Contacts::where('customercode', $customerid)->get();
        return response()->json($contact);
    }

    public function contactshow($customerid, $id)
    {
        $contact = Contacts::where('customercode', $customerid)->find($id);
        return response()->json($contact);
    }

    public function contactstore(Request $request, $customerid)
    {
        $request->validate([
            'contactpersonname' => 'required',
            'phoneno' => '',
            'mobileno' => '',
            'emailid' => '',
            'branch' => '',
        ]);

        $contact = new Contacts();
        $contact->contactpersonname = $request->contactpersonname;
        $contact->phoneno = $request->phoneno;
        $contact->mobileno = $request->mobileno;
        $contact->emailid = $request->emailid;
        $contact->branch = $request->branch;
        $contact->customercode = $customerid;
        $contact->save();
        return response()->json([$contact]);
    }

    public function contactupdate(Request $request, $customerid, $id)
    {
        $contact = Contacts::where('customercode', $customerid)->where('id', $id)->first();
        $contactdata = [
            'contactpersonname' => $request->contactpersonname,
            'phoneno' => $request->phoneno,
            'mobileno' => $request->mobileno,
            'emailid' => $request->emailid,
            'branch' => $request->branch,
        ];
        $update_contact = $contact->update($contactdata);
        return response()->json([$update_contact]);
    }

    public function contactdelete($customerid, $id)
    {
        $contactdata = Contacts::where('customercode', $customerid)->where('id', $id)->first();
        $delete_contact= $contactdata->delete();
        return response()->json([$delete_contact]);
    }
     public function companybycustomer($customerid)
    {

        //vikram changes
        $company = Company::where('customercode', $customerid)->get([DB::raw('CAST(AES_DECRYPT(companyname, "'.$key.'") AS CHAR) AS companyname')]);
        return response()->json($company);
    }

    public function ocflist(Request $request)
    {
        $customer = OCFCustomer::where('id', $request->customercode)->first();
        $company = Company::where('customercode', $customer->id)->get();
        return $company;
    }

    public function companyotps(Request $request)          // Currenly unused
    {
        $ocfcustomerflastid = OCFCustomer::orderBy('id', 'desc')->first();

        $id=$ocfcustomerflastid->id+1;
        return $id;
        $key = config('global.key');
        $customer = OCFCustomer::where('id', $id)->first();
        $compupdate = DB::table('company_master')
                        ->select('company_master.id','company_master.customercode', DB::raw('CAST(AES_DECRYPT(UNHEX(companyname), "'.$key.'") AS CHAR) AS companyname'),
                        DB::raw('CAST(AES_DECRYPT(UNHEX(panno), "'.$key.'") AS CHAR) AS panno'),
                        DB::raw('CAST(AES_DECRYPT(UNHEX(gstno), "'.$key.'") AS CHAR) AS gstno'),
                        'company_master.InstallationType', 'company_master.InstallationDesc','company_master.expirydates', 'company_master.updated_at')
                        ->where('id','=', $id)
                        ->first();



            $checkcustomer =  DB::table('customer_master')
                        ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode',
                        DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'),
                        DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'),
                        DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'), 'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time',
                        'customer_master.role_id', 'customer_master.address1', 'customer_master.address2', 'customer_master.state',
                        'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson',
                        'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password', 'customer_master.active')
                        ->where('id','=',$id)
                        ->first();



        if($checkcustomer == null)
        {
            return response()->json(['Message' => 'Invalid Mobile No', 'status' => 1]);
        }

        $otp =  rand(100000, 999999);
        $update_otp = OCFCustomer::Where('id',$id)->update((['otp' => $otp]));
        $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkcustomer->whatsappno&message=Your%20ACME%20Customer%20Registration%20is%20Successfully%20Completed.%20\nYour%20Verification%20ID%20-%20$otp%20\n*%20Please%20Do%20Not%20Share%20ID%20With%20Anyone.";
        $params =
                [
                    "to" => ["type" => "whatsapp", "number" => $checkcustomer->whatsappno],
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
        return   $otp;
    }
}
