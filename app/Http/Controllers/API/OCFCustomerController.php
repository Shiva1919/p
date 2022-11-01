<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Branchs;
use App\Models\API\Company;
use App\Models\API\Contacts;
use App\Models\API\OCFCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OCFCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = OCFCustomer::leftjoin('city', 'customer_master.city', '=', 'city.id')->where('role_id', 10)->where('active', 1)->orderBy('name','asc')
        ->get( ['city.cityname','customer_master.*' ]);
        //  $customer = Customers::where('role_id', 10)->where('active', 1)->orderBy('name','asc')->get();

        // $customer = Customers::limit(100)->get();
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $role_id = 10;
        $request->validate([
            'tenantcode' => '',
            'name' => '',
            'entrycode' => '',
            'phone' => '',
            'email' => '',
            // 'company_name' => '',
            'address1' => '',
            // 'address2' => '',
            'state' => '',
            'district' => '',
            'taluka' => '',
            'city' => '',
            // 'panno' => '',
            // 'gstno' => '',
            'noofbranch' => '',
            'role_id' => '',
            'active' => '',
            'password' => '',
            'concernperson' => '',
            'packagecode' => '',
            'subpackagecode' => ''
        ]);
        $password = 'AcmeAcme1994';
        $insert_customers = new OCFCustomer();
        $insert_customers->name = $request->name;
        $insert_customers->entrycode = $request->entrycode;
        $insert_customers->phone = $request->phone;
        $insert_customers->email = $request->email;
        $insert_customers->address1 = $request->address1;
        $insert_customers->state = $request->state;
        $insert_customers->district = $request->district;
        $insert_customers->taluka = $request->taluka;
        $insert_customers->city = $request->city;
        $insert_customers->noofbranch = $request->noofbranch;
        $insert_customers->role_id = $request->role_id;
        $insert_customers->active = $request->active;
        $insert_customers->password = $password;
        $insert_customers->role_id = $role_id;
        $insert_customers->concernperson = $request->concernperson;
        $insert_customers->packagecode = $request->packagecode;
        $insert_customers->subpackagecode = $request->subpackagecode;
         $insert_customers->save();
        if(!empty($insert_customers->id)) {
              foreach ($request->Cdocument as $data ) {
                $data=[
                    'customercode'=> $insert_customers->id,
                    'company_name'=>  $data['company'],
                    'pan_no'=> $data['pan'],
                    'gst_no'=> $data['gst'],
                ];
              Company::create($data);
            }

        }

        return response()->json([$insert_customers]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getbyid_customer = OCFCustomer::find($id);

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
    public function update(Request $request, OCFCustomer $customer)
    {
        $role_id = 10;
        $password = 'AcmeAcme1994';
        $input = $request->all();


        $validator = Validator::make($input, [
            'tenantcode' => '',
            'name' => '',
            'entrycode' => '',
            // 'mobile' => '',
            'phone' => '',
            'email' => '',
            'company_name' => '',
            'address1' => '',
            // 'address2' => '',
            'state' => '',
            'district' => '',
            'taluka' => '',
            'city' => '',
            // 'panno' => '',
            // 'gstno' => '',
            'noofbranch' => '',
            'active' => '',
            'concernperson' => '',
            'packagecode' => '',
            'subpackagecode' => ''
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $customer->name = $input['name'];
        $customer->entrycode = $input['entrycode'];
        $customer->phone = $input['phone'];
        $customer->email = $input['email'];
        $customer->address1 = $input['address1'];
        $customer->state = $input['state'];
        $customer->district = $input['district'];
        $customer->taluka = $input['taluka'];
        $customer->city = $input['city'];
        $customer->noofbranch = $input['noofbranch'];
        $customer->active = $input['active'];
        $customer->concernperson = $input['concernperson'];
        $customer->packagecode = $input['packagecode'];
        $customer->subpackagecode = $input['subpackagecode'];
        $customer->role_id = $role_id;
        $customer->password = $password;
        $customer->save();

        if(!empty($input['id'])) {
            Company::where('customercode',$input['id'])->delete();
            foreach ($request->Cdocument as $data ) {
              $data=[
                  'customercode'=> $input['id'],
                  'company_name'=>  $data['company'],
                  'pan_no'=> $data['pan'],
                  'gst_no'=> $data['gst'],
              ];

            Company::create($data);
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
        $company = Company::where('customercode', $customerid)->get('company_name');
        return response()->json($company);
    }
<<<<<<< HEAD
    
}
=======

}
>>>>>>> 1d46a30363c8cd99d9f07f5d0347edc3c7e5c959
