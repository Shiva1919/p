<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $branchs = Branch::where('customercode',$request->id)->first();
        $master_state = DB::table('State')->orderBy('statename','asc')->get();
        if($branchs == null)
        {
            return view('master.customer.branch.create', compact('branchs', 'master_state', 'request') );
        }
        $branch = Branch::join('City', 'acme_customer_branches_master.owncode', '=', 'City.owncode')
                        ->select('*')
                        ->where('customercode',$request->id)
                        ->get();
        $customer = DB::table('acme_customer_master')
                        ->join('acme_customer_branches_master', 'acme_customer_master.owncode', '=', 'acme_customer_branches_master.customercode')
                        ->where('customercode', $request->id)
                        ->get('acme_customer_master.noofbranches');
        
        $branches = Branch::where('customercode',$request->id)->count();
            //  return $branches;  
            
        return view('master.customer.branch.index',compact('branchs', 'branch', 'customer', 'branches'))->with('i', 1);
    }

    public function create(Request $request)
    {
        $master_state = DB::table('State')->orderBy('statename','asc')->get();
        return view('master.customer.branch.create', compact( 'master_state' , 'request'));
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
            'customercode' => 'required',
            'branchname' => 'required|string',
            'branchaddress1' => 'required',
            'branchstate' => 'required',
            'branchdistrict' => 'required',
            'branchtaluka' => 'required',
            'branchcity' => 'required',
        ]);
       
        $customer = DB::table('acme_customer_master')
                ->join('acme_customer_branches_master', 'acme_customer_master.owncode', '=', 'acme_customer_branches_master.customercode')
                ->where('customercode', $request->customercode)
                ->get('acme_customer_master.noofbranches');
    
        foreach($customer as $customers)
        {
           $branches = Branch::where('customercode',$request->customercode)->count(); 
            if($customers->noofbranches-1 >= $branches)
            {
                Branch::create($request->all());
                return redirect()->route('branch.index',['id' => $request->customercode])
                ->with('success','Branch Saved Successfully');
            }
            else
            {
                return redirect()->route('branch.index',['id' => $request->customercode])
                ->with('success','No of Branch Limit Over');
            }
        }

        Branch::create($request->all());
                return redirect()->route('branch.index',['id' => $request->customercode])
                ->with('success','Branch Saved Successfully');
    }

    public function show($id)
    {
        
    }

    public function edit(Branch $branch)
    {
        $state_master = Branch::join('State','acme_customer_branches_master.branchstate', '=', 'State.owncode')
                            ->where('acme_customer_branches_master.owncode',$branch->owncode)
                           ->get('State.*');   
        $state = DB::table('State')->orderBy('statename','asc')->get();

        $district_master = Branch::join('District','acme_customer_branches_master.branchdistrict', '=', 'District.OwnCode')
                            ->where('acme_customer_branches_master.owncode',$branch->owncode)
                           ->get('District.*');   
         
        $taluka_master = Branch::join('Taluka','acme_customer_branches_master.branchtaluka', '=', 'Taluka.owncode')
                            ->where('acme_customer_branches_master.owncode',$branch->owncode)
                           ->get('Taluka.*');   
        
        $city_master = Branch::join('City','acme_customer_branches_master.branchcity', '=', 'City.owncode')
                            ->where('acme_customer_branches_master.owncode',$branch->owncode)
                           ->get('City.*'   );  
        return view('master.customer.branch.edit',compact('branch', 'state_master', 'state', 'district_master', 'taluka_master', 'city_master'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'branchname' => 'required|string',
            'branchaddress1' => 'required',
            'branchstate' => '',
            'branchdistrict' => '',
            'branchtaluka' => '',
            'branchcity' => '',
        ]);
        $branch->update($request->all());
        return redirect()->route('branch.index', ['id' => $request->customercode])
                        ->with('success','Branch updated successfully');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branch.index', ['id' => $branch->customercode])
                        ->with('success','Branch deleted successfully');
    }
}
