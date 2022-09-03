<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::simplePaginate(10);
        return view('master.customer.index',compact('customer'))->with('i', 1);
    }

    public function create()
    {
        $master_state = DB::table('State')->orderBy('statename','asc')->get();
        return view('master.customer.create',compact('master_state'));
    }

    public function getDistrict(Request $request)
    {
        $data['District'] =DB::table('District')->where("StateID",$request->StateID)->orderBy('DistrictName','asc')->get();
        return response()->json($data);
    }

    public function getTaluka(Request $request)
    {
        $data['Taluka'] =DB::table('Taluka')->where("districtid",$request->districtid)->orderBy('talukaname','asc')->get();
        return response()->json($data);
    }

    public function getCity(Request $request)
    {
        $data['City'] =DB::table('City')->where("talukaid",$request->talukaid)->orderBy('cityname','asc')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    { 
        $request->validate([
            'name' => 'required|string',
            'entrycode' => 'required',
            'primarymobileno' => 'required|numeric|digits:10',
            'phoneno' => 'required|numeric|digits:10',
            'primaryemailid' => 'required|email',
            'ownername' => 'required|string',
            'address1' => 'required',
            'address2' => '',
            'state' => 'required',
            'district' => 'required',
            'taluka' => 'required',
            'city' => 'required',
            'panno' => 'required',
            'gstno' => 'required',
            'noofbranches' => 'required|numeric',
        ]);
        Customer::create($request->all());
        return redirect()->route('customer.index')
                        ->with('success','Customer created successfully.');
    }

    public function show($id)
    {
        //
    }

    public function edit(Customer $customer)
    {
        $state_master = Customer::join('State','acme_customer_master.state', '=', 'State.owncode')
                            ->where('acme_customer_master.owncode',$customer->owncode)
                           ->get('State.*');   
        $state = DB::table('State')->orderBy('statename','asc')->get();

        $district_master = Customer::join('District','acme_customer_master.district', '=', 'District.OwnCode')
                            ->where('acme_customer_master.owncode',$customer->owncode)
                           ->get();   
         
        $taluka_master = Customer::join('Taluka','acme_customer_master.taluka', '=', 'Taluka.owncode')
                            ->where('acme_customer_master.owncode',$customer->owncode)
                           ->get('Taluka.*');   
        //  return $taluka_master;
        $city_master = Customer::join('City','acme_customer_master.city', '=', 'City.owncode')
                            ->where('acme_customer_master.owncode',$customer->owncode)
                           ->get('City.*');  
        
        return view('master.customer.edit', compact('customer', 'state_master', 'state', 'district_master', 'city_master', 'taluka_master'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string',
            'entrycode' => 'required',
            'primarymobileno' => 'required|numeric|digits:10',
            'phoneno' => 'required|numeric|digits:10',
            'primaryemailid' => 'required|email',
            'ownername' => 'required|string',
            'address1' => 'required',
            'address2' => '',
            'state' => 'required',
            'district' => 'required',
            'taluka' => '',
            'city' => '',
            'panno' => 'required',
            'gstno' => 'required',
            'noofbranches' => 'required|numeric',
        ]);
        $customer->update($request->all());
        return redirect()->route('customer.index')
                        ->with('success','Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customer.index')
                        ->with('success','Customer deleted successfully');
    }
    
    public function selectBranch(Request $request, $id)
    {
        // $branch = Branch::where('owncode',$id)->first();
        // $branchs = Branch::where('customercode',$id)->simplePaginate(10);
        $branch = Branch::join('City', 'acme_customer_branches_master.owncode', '=', 'City.owncode')
                        ->select('*')
                        ->where('customercode',$id)
                        ->get();
        return view('master.customer.branch.index',compact('branch'))->with('i', 1);
    }

}
