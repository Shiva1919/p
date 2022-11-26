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
        $customer = Customers::leftjoin('city', 'users.city', '=', 'city.id')->where('role_id', 10)->where('active', 1)->orderBy('name','asc')
        ->get( ['city.cityname','users.*' ]);
        //  $customer = Customers::where('role_id', 10)->where('active', 1)->orderBy('name','asc')->get();

        // $customer = Customers::limit(100)->get();
        return response()->json($customer);
    }

    public function deactivecustomerslist()
    {
        $package = Customers::where('active', 0)->orderBy('name', 'asc')->get();
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
            'companyname' => '',
            'address1' => '',
            // 'address2' => '',
            'state' => '',
            'district' => '',
            'taluka' => '',
            'city' => '',
            'panno' => '',
            'gstno' => '',
            'noofbranches' => '',
            'role_id' => '',
            'active' => '',
            'password' => '',
            'concernperson' => '',
            'packagecode' => '',
            'subpackagecode' => ''
        ]);
        $password = 'AcmeAcme1994';
        $insert_customers = new Customers();
        // $insert_customers->tenantcode = $request->tenantcode;
        $insert_customers->name = $request->name;
        $insert_customers->entrycode = $request->entrycode;
        $insert_customers->phone = $request->phone;
        $insert_customers->email = $request->email;
        $insert_customers->companyname = $request->company_name;
        $insert_customers->address1 = $request->address1;
        // $insert_customers->address2 = $request->address2;
        $insert_customers->state = $request->state;
        $insert_customers->district = $request->district;
        $insert_customers->taluka = $request->taluka;
        $insert_customers->city = $request->city;
        $insert_customers->panno = $request->panno;
        $insert_customers->gstno = $request->gstno;
        $insert_customers->noofbranches = $request->noofbranches;
        $insert_customers->role_id = $request->role_id;
        $insert_customers->active = $request->active;
        $insert_customers->password = $password;
        $insert_customers->concernperson = $request->concernperson;
        $insert_customers->packagecode = $request->packagecode;
        $insert_customers->subpackagecode = $request->subpackagecode;
        $insert_customers->save();
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
    public function ocflist($id)
    {
        $module =array();
        $company =array();
        $ocf =array();
        $getbyid_customer = DB::table('company_master')->where('customercode',$id)->get();

//compnay list
        for ($i=0; $i < count($getbyid_customer) ; $i++) {
$com=[];
            $com=[
                   'companyname'=>$getbyid_customer[$i]->companyname,

            ];
            array_push($company,$com);

            $company_ocf = DB::table('ocf_master')
                            ->where('customercode',$id)
                            ->where('companycode',$getbyid_customer[$i]->id)
                            ->get();

                            // return $company_ocf[0]->Series;

            if (count($company_ocf)!=0) {
                //ocf list
                 for ($b=0; $b < count($company_ocf); $b++) {
$ocfdata=[];
                    $ocfdata=[
                        'companyname'=>$getbyid_customer[$i]->companyname,
                        'ocf_no'=>$company_ocf[$b]->Series.$company_ocf[$b]->DocNo,

                 ];
                 array_push($ocf,$ocfdata);
                   $ocf_modules = DB::table('ocf_modules')
                    ->where('ocfcode',$company_ocf[$b]->id)
                    ->get();
//module list
                    for ($c=0; $c < count($ocf_modules); $c++) {
                        $data=[];
                        $data=[
                            'id'=>$getbyid_customer[$i]->id,
                            'companyname'=>$getbyid_customer[$i]->companyname,
                            'ocf_no'=>$company_ocf[$b]->Series.$company_ocf[$b]->DocNo,
                            'module_name'=>$ocf_modules[$c]->modulename,
                            'quantity'=>$ocf_modules[$c]->quantity,
                        ];
                        array_push($module,$data);
                    }

                    //module list

                }

            }
            else{
                $data=[
                    'id'=>$getbyid_customer[$i]->id,
                    'companyname'=>$getbyid_customer[$i]->companyname,
                    'ocf_no'=>'',
                    'module_name'=>'',
                    'quantity'=>'',
                ];
                array_push($module,$data);
            }
        }
        // return $ocflist;

        return response()->json(['ocflist'=>$module,'company'=>$company,'Ocf'=>$ocf]);
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
        $role_id = 10;
        $password = 'AcmeAcme1994';
        $input = $request->all();
        $validator = Validator::make($input, [
            'tenantcode' => '',
            'name' => '',
            'entrycode' => '',
            'mobile' => '',
            'phone' => '',
            'email' => '',
            'companyname' => '',
            'address1' => '',
            // 'address2' => '',
            'state' => '',
            'district' => '',
            'taluka' => '',
            'city' => '',
            'panno' => '',
            'gstno' => '',
            'noofbranches' => '',
            'active' => '',
            'concernperson' => '',
            'packagecode' => '',
            'subpackagecode' => ''
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        // $customer->tenantcode = $input['tenantcode'];
        $customer->name = $input['name'];
        $customer->entrycode = $input['entrycode'];
        $customer->mobile = $input['mobile'];
        $customer->phone = $input['phone'];
        $customer->email = $input['email'];
        $customer->companyname = $input['companyname'];
        $customer->address1 = $input['address1'];
        // $customer->address2 = $input['address2'];
        $customer->state = $input['state'];
        $customer->district = $input['district'];
        $customer->taluka = $input['taluka'];
        $customer->city = $input['city'];
        $customer->panno = $input['panno'];
        $customer->gstno = $input['gstno'];
        $customer->noofbranches = $input['noofbranches'];
        $customer->active = $input['active'];
        $customer->concernperson = $input['concernperson'];
        $customer->packagecode = $input['packagecode'];
        $customer->subpackagecode = $input['subpackagecode'];
        $customer->role_id = $role_id;
        $customer->password = $password;
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


}
