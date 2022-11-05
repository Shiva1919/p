<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Company;
use App\Models\API\OCF;
use App\Models\API\OCFCustomer;
use App\Models\API\OCFModule;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
class OCFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ocf = OCF::all();
        return response()->json($ocf);
    }       

    public function getocflastid()
    {
        $ocf = OCF::orderBy('id', 'desc')->orderBy('series', 'desc')->first();
        return response()->json($ocf);
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $series = OCF::orderBy('series', 'desc')->first('series');
        $ocf= OCF::where('ocfno', $request->ocfno)->get();
        $ocflastid = OCF::orderBy('id', 'desc')->orderBy('series', 'desc')->first();

        if(count($ocf)==0)
        {
            $rules = array(
                'customercode' => 'required',
                'companycode' => 'required',
                'ocfno' => '',
                'ocf_date' => 'required',
                'series'=> ''
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
                
                $insert_ocf = new OCF();
                $customer = OCFCustomer::where('id', $request->customercode)->first();
                $company = Company::where('customercode', $customer->id)->get();  
                // return $company;
                // $insert_customers->tenantcode = $request->tenantcode;
                $insert_ocf->customercode = $request->customercode;
                $insert_ocf->companycode = $request->companycode;
                $insert_ocf->ocfno = ('OCF').($ocflastid->id+1).($series->series+1);
                $insert_ocf->ocf_date = $request->ocf_date;
                $insert_ocf->module_total=$request->module_total;
                $insert_ocf->series=$series->series+1;
                $insert_ocf->save();
                if(!empty($insert_ocf->id))
                {
                
                    foreach ($request->Dcoument as $data ) 
                    {
                    $data=[
                        'ocfcode'=> $insert_ocf->id,
                        'modulename'=> $data['modulename'],
                        'modulecode'=> $data['modulecode'],
                        'quantity'=> $data['quantity'],
                        'unit'=>  $data['unit'],
                        'expirydate'=> $data['expirydate'],
                        'amount'=> $data['amount'],
                        
                    ];

                    OCFModule::create($data);
                    }
                return response()->json(['message' => 'OCF Created Successfully','status' => '0','OCF' => $insert_ocf, 'Module' => $data]);
                }
            }
        }
        else
        {
            $rules = array(
                'customercode' => 'required',
                'companycode' => 'required',
                'ocfno' => '',
                'ocf_date' => 'required',
                'series'=> ''
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
               
                
                $insert_ocf = OCF::where('ocfno', $request->ocfno)->first();
               
                // $module = DB::table('module_master')->where('ocfcode', $insert_ocf->id)->sum('amount');
               
                // $insert_customers->tenantcode = $request->tenantcode;
                $insert_ocf->customercode = $request->customercode;
                $insert_ocf->companycode = $request->companycode;
                $insert_ocf->ocfno = $request->ocfno;
                $insert_ocf->ocf_date = $request->ocf_date;
                $insert_ocf->module_total=$request->module_total;
                // $insert_ocf->series=$series->series+1;
                $insert_ocf->save();

                OCFModule::where('ocfcode',$insert_ocf->id)->delete();
                if(!empty($insert_ocf->id))
                {
                
                    foreach ($request->Dcoument as $data )
                            $data=[
                                'ocfcode'=> $insert_ocf->id,
                                'modulename'=> $data['modulename'],
                                'modulecode'=> $data['modulecode'],
                                'quantity'=> $data['quantity'],
                                'unit'=>  $data['unit'],
                                'expirydate'=> $data['expirydate'],
                                'amount'=> $data['amount'],   
                            ];

                            OCFModule::create($data);
                        }
                return response()->json(['message' => 'OCF Updated Successfully','status' => '0','OCF' => $insert_ocf, 'Module' => $data ]);
                
            }
        }
    }

    public function OCFstore(Request $request)
    {
        // return $request;
        $series = OCF::orderBy('series', 'desc')->first('series');
        $ocf= OCF::where('ocfno', $request->ocfno)->get();
        $ocflastid = OCF::orderBy('id', 'desc')->orderBy('series', 'desc')->first();

        if(count($ocf)==0)
        {
            $rules = array(
                'customercode' => 'required',
                'companycode' => 'required',
                'ocfno' => '',
                'ocf_date' => 'required',
                'series'=> ''
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
                
                $insert_ocf = new OCF();
                $customer = OCFCustomer::where('id', $request->customercode)->first();
                $company = Company::where('customercode', $customer->id)->get();  
                // return $company;
                // $insert_customers->tenantcode = $request->tenantcode;
                $insert_ocf->customercode = $request->customercode;
                $insert_ocf->companycode = $request->companycode;
                $insert_ocf->ocfno = ('OCF').($ocflastid->id+1).($series->series+1);
                $insert_ocf->ocf_date = $request->ocf_date;
                $insert_ocf->module_total=$request->module_total;
                $insert_ocf->series=$series->series+1;
                $insert_ocf->save();
                if(!empty($insert_ocf->id))
                {
                
                    foreach ($request->Dcoument as $data ) 
                    {
                    $data=[
                        'ocfcode'=> $insert_ocf->id,
                        'modulename'=> $data['modulename'],
                        'modulecode'=> $data['modulecode'],
                        'quantity'=> $data['quantity'],
                        'unit'=>  $data['unit'],
                        'expirydate'=> $data['expirydate'],
                        'amount'=> $data['amount'],
                        
                    ];

                    OCFModule::create($data);
                    }
                return response()->json(['message' => 'OCF Created Successfully','status' => '0','OCF' => $insert_ocf, 'Module' => $data]);
                }
            }
        }
        else
        {
            $rules = array(
                'customercode' => 'required',
                'companycode' => 'required',
                'ocfno' => '',
                'ocf_date' => 'required',
                'series'=> ''
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
               
                
                $insert_ocf = OCF::where('ocfno', $request->ocfno)->first();
               
                $module = DB::table('module_master')->where('ocfcode', $insert_ocf->id)->sum('amount');
               
                // $insert_customers->tenantcode = $request->tenantcode;
                $insert_ocf->customercode = $request->customercode;
                $insert_ocf->companycode = $request->companycode;
                $insert_ocf->ocfno = $request->ocfno;
                $insert_ocf->ocf_date = $request->ocf_date;
                
                // OCFModule::where('ocfcode',$insert_ocf->id)->delete();
                if(!empty($insert_ocf->id))
                {
                
                    foreach ($request->Dcoument as $data )
                            $data=[
                                'ocfcode'=> $insert_ocf->id,
                                'modulename'=> $data['modulename'],
                                'modulecode'=> $data['modulecode'],
                                'quantity'=> $data['quantity'],
                                'unit'=>  $data['unit'],
                                'expirydate'=> $data['expirydate'],
                                'amount'=> $data['amount'],   
                            ];

                            OCFModule::create($data);
                }
                $insert_ocf->module_total=$module+$data['amount'];
                $insert_ocf->save();
        
                return response()->json(['message' => 'OCF Updated Successfully','status' => '0','OCF' => $insert_ocf, 'Module' => $data ]);
                
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $module = DB::table('module_master')->where('ocfcode', $id)->sum('amount');
        return $module;
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getocfno($ocfno)
    {
         $data = OCF::where('ocfno', $ocfno)->first();
        return response()->json($data);
    }
}
