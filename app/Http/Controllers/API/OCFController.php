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
        $ocf = OCF::orderBy('DocNo', 'desc')->get();
        return $ocf[0];
        // return response()->json($ocf);
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

        $str = substr($request->ocfno,3);

        $series = OCF::orderBy('DocNo', 'desc')->first();
        $ocf= OCF::where('DocNo', $str)->get();
        $ocflastid = OCF::orderBy('id', 'desc')->orderBy('Series', 'desc')->first();


        if(count($ocf)==0)
        {

                $insert_ocf = new OCF();
                // $customer = OCFCustomer::where('id', $request->customercode)->first();
                // $company = Company::where('customercode', $customer->id)->get();
                // return $company;
                // $insert_customers->tenantcode = $request->tenantcode;
                $insert_ocf->customercode = $request->customercode;
                $insert_ocf->companycode = $request->companycode;


                if (!$series && !$ocflastid) {
                   $insert_ocf->DocNo=01;
                }
                else {

                    $insert_ocf->DocNo=$series->DocNo+1;
                }
                $insert_ocf->Series = ('OCF');
                $insert_ocf->ocf_date = $request->ocf_date;
                $insert_ocf->AmountTotal=$request->module_total;
                $insert_ocf->save();
                if(!empty($insert_ocf->id))
                {

                    foreach ($request->Dcoument as $data )
                    {

                        $module_unit = DB::table('acme_module')
                        ->join('acme_module_type','acme_module.moduletypeid','=','acme_module_type.id')
                        ->where('acme_module.ModuleName',$data['modulecode'])
                        ->get(['acme_module.ModuleName AS Module_name','acme_module_type.moduletype As moduletype','acme_module_type.unit as unit']);

                    $data=[
                        'ocfcode'=> $insert_ocf->id,
                        'modulename'=> $data['modulecode'],
                        'modulecode'=> $data['modulecode'],
                        'moduletypes'=> $module_unit[0]->moduletype,
                        'quantity'=> $data['quantity'],
                        'unit'=>  $module_unit[0]->unit,
                        'expirydate'=> $data['expirydate'],
                        'amount'=> $data['amount'],
                        'activation'=> $data['activation']

                    ];

                    OCFModule::create($data);
                    }
                return response()->json(['message' => 'OCF Created Successfully','status' => '0','OCF' => $insert_ocf, 'Module' => $data]);
                }
        }
        else
        {


                $insert_ocf = OCF::where('DocNo', $str)->first();

                // $module = DB::table('ocf_modules')->where('ocfcode', $insert_ocf->id)->sum('amount');

                // $insert_customers->tenantcode = $request->tenantcode;
                $insert_ocf->customercode = $request->customercode;
                $insert_ocf->companycode = $request->companycode;
                $insert_ocf->ocf_date = $request->ocf_date;
                $insert_ocf->AmountTotal=$request->module_total;
                // $insert_ocf->series=$series->series+1;
                $insert_ocf->save();

                OCFModule::where('ocfcode',$insert_ocf->id)->delete();
                if(!empty($insert_ocf->id))
                {

                    foreach ($request->Dcoument as $data ){
                    $module_unit=[];
                    $module_unit = DB::table('acme_module')
                    ->join('acme_module_type','acme_module.moduletypeid','=','acme_module_type.id')
                    ->where('acme_module.ModuleName',$data['modulecode'])
                    ->get(['acme_module.ModuleName AS Module_name','acme_module_type.moduletype As moduletype','acme_module_type.unit as unit']);

                    $data=[
                        'ocfcode'=> $insert_ocf->id,
                        'modulename'=> $data['modulecode'],
                        'modulecode'=> $data['modulecode'],
                        'moduletypes'=> $module_unit[0]->moduletype,
                        'quantity'=> $data['quantity'],
                        'unit'=>  $module_unit[0]->unit,
                        'expirydate'=> $data['expirydate'],
                        'amount'=> $data['amount'],
                        'activation'=> $data['activation']

                    ];

                        OCFModule::create($data);

                }
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
        $module = DB::table('ocf_modules')->where('ocfcode', $id)->sum('amount');
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
        $str = substr($ocfno, 3);
       $data = OCF::where('DocNo', $str)->first();
        return response()->json($data);
    }

    public function getocf_customer($customer)
    {
        $data = OCF::where('customercode', $customer)->get();
        return response()->json($data);
    }
    public function getocf_customer_company($customer,$company)
    {
        $data = OCF::where('customercode', $customer)->where('companycode', $company)->get();
        return response()->json($data);
    }

    public function getocf_modules($ocf)
    {
        $data = DB::table('ocf_modules')->where('ocfcode', $ocf)->get();
        return response()->json($data);
    }
}
