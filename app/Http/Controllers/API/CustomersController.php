<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Branchs;
use App\Models\API\Contacts;
use App\Models\API\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
<<<<<<< HEAD
        $customer = Customers::limit(100)->get();
        return $customer;
=======
        $customer = Customers::where('role_id', 10)->get();
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
        return response()->json($customer);
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
<<<<<<< HEAD
        $request->validate([
            'tenantcode' => 'required',
            'name' => 'required',
            'entrycode' => 'required',
            'mobile' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'company_name' => 'required',
            'address1' => 'required',
            'address2' => '',
            'state' => 'required',
            'district' => 'required',
            'taluka' => 'required',
            'city' => 'required',
            'panno' => '',
            'gstno' => '',
            'noofbranches' => 'required|numeric',
=======
        
        $request->validate([
            'tenantcode' => '',
            'name' => 'required',
            'entrycode' => '',
            'mobile' => '',
            'phone' => '',
            'email' => '',
            'company_name' => '',
            'address1' => '',
            'address2' => '',
            'state' => '',
            'district' => '',
            'taluka' => '',
            'city' => '',
            'panno' => '',
            'gstno' => '',
            'noofbranches' => '',
            'role_id'=> ''
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
        ]);
        $insert_customers = Customers::create($request->all());
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
        $getbyid_customer = Customers::find($id);
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
    public function update(Request $request, Customers $customer)
    {
<<<<<<< HEAD
        $input = $request->all();
        $validator = Validator::make($input, [
            'tenantcode' => 'required',
            'name' => 'required',
            'entrycode' => 'required',
            'mobile' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'company_name' => 'required',
            'address1' => 'required',
            'address2' => '',
            'state' => 'required',
            'district' => 'required',
            'taluka' => 'required',
            'city' => 'required',
            'panno' => '',
            'gstno' => '',
            'noofbranches' => 'required|numeric',
=======
        $role_id = 10;
        $input = $request->all();
        $validator = Validator::make($input, [
            'tenantcode' => '',
            'name' => 'required',
            'entrycode' => '',
            'mobile' => '',
            'phone' => '',
            'email' => '',
            'company_name' => '',
            'address1' => '',
            'address2' => '',
            'state' => '',
            'district' => '',
            'taluka' => '',
            'city' => '',
            'panno' => '',
            'gstno' => '',
            'noofbranches' => '',
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $customer->tenantcode = $input['tenantcode'];
        $customer->name = $input['name'];
        $customer->entrycode = $input['entrycode'];
        $customer->mobile = $input['mobile'];
        $customer->phone = $input['phone'];
        $customer->email = $input['email'];
        $customer->company_name = $input['company_name'];
        $customer->address1 = $input['address1'];
        $customer->address2 = $input['address2'];
        $customer->state = $input['state'];
        $customer->district = $input['district'];
        $customer->taluka = $input['taluka'];
        $customer->city = $input['city'];
        $customer->panno = $input['panno'];
        $customer->gstno = $input['gstno'];
        $customer->noofbranches = $input['noofbranches'];
<<<<<<< HEAD
=======
        $customer->role_id = $role_id;
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
        $customer->save();
        return response()->json([$customer]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customers $customer)
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
<<<<<<< HEAD
        $data['district'] =DB::table('district')->where("stateid",$stateid)->orderBy('districtname','asc')->get();
        return response()->json($data);
=======
        $district =DB::table('district')->where("stateid",$stateid)->orderBy('districtname','asc')->get();
        return response()->json($district);
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
    }

    public function getTaluka($districtid)
    {
<<<<<<< HEAD
        $data['Taluka'] =DB::table('taluka')->where("districtid",$districtid)->orderBy('talukaname','asc')->get();
        return response()->json($data);
=======
        $taluka =DB::table('taluka')->where("districtid",$districtid)->orderBy('talukaname','asc')->get();
        return response()->json($taluka);
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
    }

    public function getCity($talukaid)
    {
<<<<<<< HEAD
        $data['City'] =DB::table('city')->where("talukaid",$talukaid)->orderBy('cityname','asc')->get();
        return response()->json($data);
=======
        $city =DB::table('city')->where("talukaid",$talukaid)->orderBy('cityname','asc')->get();
        return response()->json($city);
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
    }

    public function branchindex($customerid) 
    {
<<<<<<< HEAD
        $branch = Branchs::where('customercode', $customerid)->get();
=======
        $branch =  Branchs::where('customercode', $customerid)->get();
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
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
<<<<<<< HEAD
            'branchname' => 'required',
            'branchaddress1' => 'required',
            'branchaddress2' => 'required',
            'branchstate' => 'required',
            'branchdistrict' => 'required',
            'branchtaluka' => 'required',
            'branchcity' => 'required'
=======
            'branchname' => '',
            'branchaddress1' => '',
            'branchaddress2' => '',
            'branchstate' => '',
            'branchdistrict' => '',
            'branchtaluka' => '',
            'branchcity' => ''
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
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
<<<<<<< HEAD
        return response()->json([$branch]);
=======
        return response()->json($branch);
>>>>>>> 3cf49cd1721069170538a19aa68966f30dd3e704
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
            'phoneno' => 'required',
            'mobileno' => 'required',
            'emailid' => 'required',
            'branch' => 'required',
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
}
