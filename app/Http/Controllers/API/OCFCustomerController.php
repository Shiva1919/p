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
        $customer = DB::Table('customer_master')->where('role_id',10)->where('active', 1)->orderBy('name','asc')
        // ->get();
       ->get(['id','entrycode','otp','serialotp','isverified','role_id','address1','address2','state','district','taluka','city','concernperson','packagecode','subpackagecode',DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'),
                DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'),
                DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'),
                DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone')]);
          return response()->json($customer);
    }

    public function getmoduledata($customerid)
    {
        $getmodules = OCFCustomer::leftjoin('acme_package', 'customer_master.packagecode', '=','acme_package.id')
                                ->leftjoin('acme_module', 'acme_package.id', '=', 'acme_module.producttype')
                               ->where('customer_master.id', $customerid)->get('acme_module.*');

        return response()->json($getmodules);
    }
    public function deactivecustomerslist()
    {
        $package = OCFCustomer::where('active', 0)->orderBy('name', 'asc')->get();
        return response()->json($package);
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
                'subpackagecode' => 'required'
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
                $insert_customers->address1 = $request->address1;
                $insert_customers->address2 = $request->address2;
                $insert_customers->state = $request->state;
                $insert_customers->district = $request->district;
                $insert_customers->taluka = $request->taluka;
                $insert_customers->city = $request->city;
                $insert_customers->active = $request->active;
                $insert_customers->password = $password;
                $insert_customers->role_id = $role_id;
                $insert_customers->concernperson = $request->concernperson;
                $insert_customers->packagecode = $request->packagecode;
                $insert_customers->subpackagecode = $request->subpackagecode;
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
                            //    'comapnycode'=> $ocfcompanyflastid->id+1,
                               'companyname'=> DB::raw("HEX(AES_ENCRYPT('$data->company_name','$key'))"),
                               'panno'=> DB::raw("HEX(AES_ENCRYPT('$data->pan_no','$key'))"),
                               'gstno'=>DB::raw("HEX(AES_ENCRYPT('$data->gst_no','$key'))") ,
                               'InstallationType' => $data->InstallationType,
                               'InstallationDesc' => $data->InstallationDesc

                           )
                           );
                    }

                }
                return response()->json(['message' => 'Customer Saved Successfully','status' => '0','Customer' => $insert_customers,'Company' => $data]);
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
        $getbyid_customer = DB::Table('customer_master')->where('id',$id)
        ->first(['id',DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'),DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'),DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'),DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'),'state','district','taluka','address1','address2','role_id','concernperson','packagecode','password','city','subpackagecode','active']);

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
            'subpackagecode' => ''
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
        $customer->address1 = $request->address1;
        $customer->address2 = $request->address2;
        $customer->state = $request->state;
        $customer->district = $request->district;
        $customer->taluka = $request->taluka;
        $customer->city = $request->city;
        $customer->active = $request->active;
        $customer->concernperson = $request->concernperson;
        $customer->packagecode = $request->packagecode;
        $customer->subpackagecode = $request->subpackagecode;
        $customer->role_id = $role_id;
        $customer->password = $password;
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
                               'customercode'=> $insert_customers->id,
                            //    'comapnycode'=> $ocfcompanyflastid->id+1,
                               'companyname'=> DB::raw("HEX(AES_ENCRYPT('$data->company_name','$key'))"),
                               'panno'=> DB::raw("HEX(AES_ENCRYPT('$data->pan_no','$key'))"),
                               'gstno'=>DB::raw("HEX(AES_ENCRYPT('$data->gst_no','$key'))") ,
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
                  $update_data->InstallationDesc=$data->InstallationDesc;
                  $update_data->save();
                }

          }

      }
        return response()->json([$customer]);
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

}
