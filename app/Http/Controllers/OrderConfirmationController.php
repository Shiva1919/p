<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use App\Models\Customer;
use App\Models\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderConfirmationController extends Controller
{
    public function index()
    {   
        $order_confirmation = OrderConfirmation::simplePaginate(10);
        return view('master.order_confirmation.index', compact('order_confirmation'))->with('i', 1);  
    }

    public function create()
    {
        $master_customer = DB::table('acme_customer_master')->orderBy('name','asc')->get();
        $master_branch = DB::table('acme_customer_branches_master')->orderBy('branchname','asc')->get();
        $master_package = DB::table('acme_package_type_master')->orderBy('name','asc')->get();
        // $startdate = Carbon::now();

        return view('master.order_confirmation.create', compact('master_customer', 'master_branch', 'master_package'))
                        ->with('success','Order Confirmation created successfully.');
    }

    public function getCustomer(Request $request)
    {
        $customer=$request->post('customer');
		$data['customer']=DB::table('acme_customer_master')->where('owncode',$customer)->get();
        return response()->json($data);
    }

    public function getStates(Request $request)
    {
        $customer = $request->post('customer');
        $data['state'] = Customer::join('State','acme_customer_master.state', '=', 'State.owncode')
                            ->where('acme_customer_master.owncode',$customer)
                           ->get('State.*');   
        return response()->json($data);
    }

    public function getCitys(Request $request)
    {
        $customer = $request->post('customer');
        $data['city'] = Customer::join('City','acme_customer_master.city', '=', 'City.owncode')
                            ->where('acme_customer_master.owncode',$customer)
                           ->get('City.*');   
                           return $data;
        return response()->json($data);
    }

    public function getBranch(Request $request)
    {
        $data['branch'] =DB::table('acme_customer_branches_master')->where("customercode",$request->customer)->get();
        return response()->json($data);
    }

    public function getSubPackage(Request $request)
    {
        $data['subpackage'] =DB::table('acme_package_subtype_master')->where("packagetype",$request->customer)->get();
        return response()->json($data);
    }

    public function store(Request $request)
    { 
        if($request->salestype == "Module"){
            $request->validate([
                'salestype'  => 'required',
                'eefOcfnocode' => 'required|numeric',
                'ocfno' => 'required',
                'purchasedate' => 'date_format:d/m/Y',
                'customercode' => 'required',
                'concernperson' => 'required',
                'branchcode' => 'required',
                'packagetype'  => 'required',
                'packagesubtype' => 'required',
                'narration'  => 'required'
            ]);
        }
        else{
            $request->validate([
                'salestype'  => 'required',
                'eefOcfnocode' => '',
                'ocfno' => 'required',
                'initialusercount' => 'required|numeric',
                'fromdate' => 'date_format:d/m/Y',
                'todate' => 'date_format:d/m/Y',
                'validityperiodofinitialusers' => 'required',
                'customercode' => 'required',
                'concernperson' => 'required',
                'branchcode' => 'required',
                'packagetype'  => 'required',
                'packagesubtype' => 'required',
                'narration'  => 'required'
            ]);
        }
        
       OrderConfirmation::create($request->all());
    //    return $q;
        return redirect()->route('order_confirmation.index')
                        ->with('success','Order Confirmation created successfully.');

    }

    public function show($id)
    {
        //
    }

    public function edit(OrderConfirmation $order_confirmation)
    {
        $master_customer = OrderConfirmation::join('acme_customer_master','acme_customer_products.customercode', '=', 'acme_customer_master.owncode')
                            ->where('acme_customer_products.owncode',$order_confirmation->owncode)
                           ->get('acme_customer_master.*'); 
        $customer = DB::table('acme_customer_master')->orderBy('name','asc')->get();

        $master_state = Customer::join('State','acme_customer_master.state', '=', 'State.owncode')
                            ->where('acme_customer_master.owncode',$order_confirmation->customercode)
                           ->get('State.*'); 
                        
        $master_city = Customer::join('City','acme_customer_master.city', '=', 'City.owncode')
                           ->where('acme_customer_master.owncode',$order_confirmation->customercode)
                          ->get('City.*');                    

        $master_package = OrderConfirmation::join('acme_package_type_master','acme_customer_products.packagetype', '=', 'acme_package_type_master.owncode')
                            ->where('acme_customer_products.owncode',$order_confirmation->owncode)
                            ->get('acme_package_type_master.*'); 
        $package = DB::table('acme_package_type_master')->orderBy('name','asc')->get();

        
        $subpackage =   OrderConfirmation::join('acme_package_subtype_master','acme_customer_products.packagesubtype', '=', 'acme_package_subtype_master.owncode')
                             ->where('acme_package_subtype_master.owncode',$order_confirmation->packagesubtype)
                            ->get('acme_package_subtype_master.*');    
           
        $master_branch = OrderConfirmation::join('acme_customer_branches_master','acme_customer_products.branchcode', '=', 'acme_customer_branches_master.owncode')
                        ->where('acme_customer_products.owncode',$order_confirmation->owncode)
                        ->get('acme_customer_branches_master.*');
        $branch = DB::table('acme_customer_branches_master')->orderBy('branchname','asc')->get();
           
        return view('master.order_confirmation.edit', compact('order_confirmation', 'master_customer', 'customer', 'master_state', 'master_city', 'master_package', 'package', 'subpackage', 'master_branch', 'branch', 'master_package'))
                        ->with('success','Order Confirmation created successfully.');
    }

    public function update(Request $request, OrderConfirmation $order_confirmation)
    {
        if($request->salestype == "Module"){
            $request->validate([
                'salestype'  => 'required',
                'eefOcfnocode' => 'required|numeric',
                'ocfno' => 'required',
                'purchasedate' => 'date_format:d/m/Y',
                'customercode' => 'required',
                'concernperson' => 'required',
                'branchcode' => 'required',
                'packagetype'  => 'required',
                'packagesubtype' => 'required',
                'narration'  => 'required'
            ]);
        }
        else{
            $request->validate([
                'salestype'  => 'required',
                'eefOcfnocode' => '',
                'ocfno' => 'required',
                'initialusercount' => 'required|numeric',
                'fromdate' => 'date_format:d/m/Y',
                'todate' => 'date_format:d/m/Y',
                'validityperiodofinitialusers' => 'required',
                'customercode' => 'required',
                'concernperson' => 'required',
                'branchcode' => 'required',
                'packagetype'  => 'required',
                'packagesubtype' => 'required',
                'narration'  => 'required'
            ]);
        }
       
        $order_confirmation->update($request->all());
        return redirect()->route('order_confirmation.index')
                        ->with('success','Order Confirmation updated successfully');
    }

    public function destroy(OrderConfirmation $order_confirmation)
    {
        $order_confirmation->delete();
        return redirect()->route('order_confirmation.index')
                        ->with('success','Order Cofirmation deleted successfully');
    }
}
