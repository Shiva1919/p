<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Company;
use App\Models\API\OCF;
use App\Models\API\OCFCustomer;
use App\Models\API\OCFModule;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\FuncCall;
use Validator;
class OCFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     function index()
    {
        $ocf = OCF::all();
        return response()->json($ocf);
    }

    function getocflastid()
    {
        $ocf = OCF::orderBy('DocNo', 'desc')->get('DocNo');
        if (count($ocf)!=0) {
            return response()->json($ocf[0]->DocNo+1);
        }else{
            return response()->json($ocf[0]=01);
        }
        // return $ocf[0]->DocNo;

        //  return response()->json($ocf);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function store(Request $request)
    {
        $key = config('global.key');
        $str = substr($request->ocfno,3);
        $data1=[];
        $ocf= OCF::where('DocNo', $str)->get();
        $series = OCF::orderBy('DocNo', 'desc')->first();

        $ocflastid = OCF::orderBy('id', 'desc')->first();
        // if(count($ocf)==0)
        // {


                $insert_ocf = new OCF();
                if ($series) {
                    $insert_ocf->DocNo=$series->DocNo+1;
                }
                else{
                    $insert_ocf->DocNo=1;
                 }
                if (!$series && !$ocflastid) {
                    $insert_ocf->DocNo=01;
                 }

                $insert_ocf->customercode = $request->customercode;
                $insert_ocf->companycode = $request->companycode;

                $insert_ocf->Series = ('OCF');
                $ocf_date= date("Y-m-d", strtotime($request->ocf_date));
                $insert_ocf->ocf_date =  $ocf_date;
                $insert_ocf->AmountTotal=$request->module_total;

                $insert_ocf->save();

                if(!empty($insert_ocf->id))
                {

                    foreach ($request->Dcoument as $data )
                    {
                        if ($data['expirydate']==null) {
                            $data['expirydate']='0000-00-00';
                        }
                       $expirydate= date("Y-m-d", strtotime($data['expirydate']));

                    $module_unit =OCFCustomer::leftjoin('acme_package', 'customer_master.packagecode', '=','acme_package.id')
                                                    ->leftjoin('acme_module', 'acme_package.id', '=', 'acme_module.producttype')
                                                    ->leftjoin('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
                                                    ->where('customer_master.id', $request->customercode)
                                                    ->where('acme_module.ModuleName',$data['modulecode'])
                                                    ->get(['acme_module.id as moduleid', 'acme_module.ModuleName as modulename', 'acme_module_type.id as acme_module_typeid','acme_module_type.moduletype as acme_module_moduletype']);

                    $data=[
                        'ocfcode'=> $insert_ocf->id,
                        'modulename'=> $data['modulecode'],
                        'modulecode' => $module_unit[0]->moduleid,
                        'moduletypes'=> $module_unit[0]->acme_module_typeid,
                        'quantity'=> $data['quantity'],
                        'expirydate'=> $expirydate,
                        'amount'=> $data['amount'],
                        'activation'=> $data['activation']

                    ];
                    array_push($data1,$data);

                   $ocfmoduledata = OCFModule::create($data);


                }
                   $customer = OCFCustomer::where('id', $request->customercode)->first();


                   if($ocfmoduledata == null)
                   {
                     return response()->json(['message' => 'OCF not Saved']);
                   }
                   else
                   {


                    $checkcustomer =  DB::table('customer_master')
                    ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name), "'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode',
                    DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'),
                    DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'),
                    DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'), 'customer_master.otp', 'customer_master.isverified', 'customer_master.otp_expires_time',
                    'customer_master.role_id', 'customer_master.address1', 'customer_master.address2', 'customer_master.state',
                    'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson',
                    'customer_master.packagecode', 'customer_master.subpackagecode', 'customer_master.password', 'customer_master.active')
                    ->where('id','=',$request->customercode)
                    ->first();

                if($checkcustomer == null)
                {
                    return response()->json(['Message' => 'Invalid Mobile No', 'status' => 1]);
                }
                        return response()->json(['message' => "OCF Created Successfully Your OCF No is OCF$insert_ocf->DocNo" ,'status' => '0','OCF' => $insert_ocf, 'Module' => $data1]);
                   }


            }

    // }
//     else{


//         $insert_ocf = OCF::where('DocNo', $str)->first();
//         $ocf_date= date("Y-m-d", strtotime($request->ocf_date));
//           $insert_ocf->customercode = $request->customercode;
//           $insert_ocf->companycode = $request->companycode;
//           $insert_ocf->ocf_date = $ocf_date;
//           $insert_ocf->AmountTotal=$request->module_total;
//           $insert_ocf->save();

//           if(!empty($insert_ocf->id))
//           {

//               foreach ($request->Dcoument as $data ){
//                 if ($data['expirydate']==null) {
//                     $data['expirydate']='0000-00-00';
//                 }
//                 $expirydate= date("Y-m-d", strtotime($data['expirydate']));
//                 $module_unit=[];
//                 $module_unit = OCFCustomer::leftjoin('acme_package', 'customer_master.packagecode', '=','acme_package.id')
//                 ->leftjoin('acme_module', 'acme_package.id', '=', 'acme_module.producttype')
//                 ->leftjoin('acme_module_type', 'acme_module.moduletypeid', '=', 'acme_module_type.id')
//                 ->where('customer_master.id', $request->customercode)
//                 ->where('acme_module.ModuleName',$data['modulecode'])
//                 ->get(['acme_module.id as moduleid', 'acme_module.ModuleName as modulename', 'acme_module_type.id as acme_module_typeid','acme_module_type.moduletype as acme_module_moduletype']);
//                 if ($data['id']==0) {


//                          $data=[
//                             'ocfcode'=> $insert_ocf->id,
//                             'modulename'=> $data['modulecode'],
//                             'modulecode' => $module_unit[0]->moduleid,
//                             'moduletypes'=> $module_unit[0]->acme_module_typeid,
//                             'quantity'=> $data['quantity'],
//                             'expirydate'=> $expirydate,
//                             'amount'=> $data['amount'],
//                             'activation'=> $data['activation']
//                         ];

//                      OCFModule::create($data);
//                 }
//             else{

//                $update_data= OCFModule::find($data['id']);
//                 $update_data->modulename=$data['modulecode'];
//                 $update_data->modulecode=$module_unit[0]->moduleid;
//                 $update_data->moduletypes=$module_unit[0]->acme_module_typeid;
//                 $update_data->quantity=$data['quantity'];
//                 $update_data->expirydate=$expirydate;
//                 $update_data->amount=$data['amount'];
//                 $update_data->activation=$data['activation'];
//                 $update_data->save();
//                 }
//                 array_push($data1,$data);
//            }
//           return response()->json(['message' => 'OCF Updated Successfully','status' => '0','OCF' => $insert_ocf, 'Module' => $data1 ]);

//       }
//   }
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
    public function get_ocfdata_list_customerwise($DocNo){
        $data = DB::table('ocf_modules')
                ->leftJoin('ocf_master','ocf_master.id','=','ocf_modules.ocfcode')
                ->where('ocf_master.DocNo',$DocNo)->get('ocf_modules.*');
 return $data;
    }

    public function getocf_modules($ocf)
    {
        $data = DB::table('ocf_modules')->where('ocfcode', $ocf)->get();
        return response()->json($data);
    }

//ocf activation deactivation
    public function activeocf($customer,$company,$ocf)
    {
        $active = OCF::where('customercode', $customer)->where('companycode', $company)->where('DocNo', $ocf)->first();
        $updateactive = OCF::where('DocNo', $ocf)->update(['active'=> 1]);

        if($updateactive ==1)
        {
            $activeocfmodules = OCFModule::where('ocfcode', $active->id)->update(['activation' => 1]);
        }
        return response()->json(['message'=> 'OCF Activated','Customer'=>$customer]);
    }

    public function deactiveocf($customer, $company, $ocf)
    {
        $deactive = OCF::where('customercode', $customer)->where('companycode', $company)->where('DocNo', $ocf)->first();
        $updateactive = OCF::where('DocNo', $ocf)->update(['active'=> 0]);

        if($updateactive == 1)
        {
            $deactiveocfmodules = OCFModule::where('ocfcode', $deactive->id)->update(['activation' => 0]);
        }
        return response()->json(['message'=> 'OCF Deactivated','Customer'=>$customer]);
    }


    public function ocfactive(Request $request)
    {
        $key = config('global.key');
        if($request->ispassed == "U")
        {
            $ocf = OCFCustomer::select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name),"'.$key.'") AS CHAR) AS name'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(city), "'.$key.'") AS CHAR) AS city'),'company_master.companyname',
                                'company_master.panno', 'company_master.gstno', DB::raw('CONCAT(srno_ocf_master.Series,srno_ocf_master.DocNo) AS OCFNo'))
                                ->leftjoin('company_master', 'customer_master.id', '=', 'company_master.customercode' )
                                ->leftjoin('ocf_master', 'customer_master.id', '=', 'ocf_master.customercode' )
                                ->where('customer_master.id', $request->id)
                                ->where('company_master.customercode', $request->id)
                                ->where('ocf_master.ispassed', "U")
                                ->where('ocf_master.active', 1)
                                ->get();
        }
        elseif($request->ispassed == "P")
        {
            $ocf = OCFCustomer::select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name),"'.$key.'") AS CHAR) AS name'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(city), "'.$key.'") AS CHAR) AS city'),DB::raw('CAST(AES_DECRYPT(UNHEX(companyname), "'.$key.'") AS CHAR) AS companyname'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(panno), "'.$key.'") AS CHAR) AS panno'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(gstno), "'.$key.'") AS CHAR) AS gstno'),
                                DB::raw('CONCAT(srno_ocf_master.Series,srno_ocf_master.DocNo) AS OCFNo'))
                                ->leftjoin('company_master', 'customer_master.id', '=', 'company_master.customercode' )
                                ->leftjoin('ocf_master', 'customer_master.id', '=', 'ocf_master.customercode' )
                                ->where('customer_master.id', $request->id)
                                ->where('company_master.customercode', $request->id)
                                ->where('ocf_master.ispassed', "P")
                                ->where('ocf_master.active', 1)
                                ->get();

        }
        elseif($request->ispassed == "R")
        {
            $ocf = OCFCustomer::select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name),"'.$key.'") AS CHAR) AS name'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(city), "'.$key.'") AS CHAR) AS city'),DB::raw('CAST(AES_DECRYPT(UNHEX(companyname), "'.$key.'") AS CHAR) AS companyname'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(panno), "'.$key.'") AS CHAR) AS panno'),
                                DB::raw('CAST(AES_DECRYPT(UNHEX(gstno), "'.$key.'") AS CHAR) AS gstno'),
                                DB::raw('CONCAT(srno_ocf_master.Series,srno_ocf_master.DocNo) AS OCFNo'))
                                ->leftjoin('company_master', 'customer_master.id', '=', 'company_master.customercode' )
                                ->leftjoin('ocf_master', 'customer_master.id', '=', 'ocf_master.customercode' )
                                ->where('customer_master.id', $request->id)
                                ->where('company_master.customercode', $request->id)
                                ->where('ocf_master.ispassed', "R")
                                ->where('ocf_master.active', 1)
                                ->get();
        }
        else
        {
            return "FAIL";
        }
        return $ocf;


    }

}
