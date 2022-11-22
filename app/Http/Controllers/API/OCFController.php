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
                        // return $module_unit;
                    $data=[
                        'ocfcode'=> $insert_ocf->id,
                        'modulename'=> $data['modulecode'],
                        // 'modulecode'=> $data['modulecode'],
                        'moduletypes'=> $module_unit[0]->moduletype,
                        'quantity'=> $data['quantity'],
                        'unit'=>  $module_unit[0]->unit,
                        'expirydate'=> $data['expirydate'],
                        'amount'=> $data['amount'],
                        'activation'=> $data['activation']

                    ];

                   $ocfmoduledata = OCFModule::create($data);
                   $customer = OCFCustomer::where('id', $request->customercode)->first();
                   if($ocfmoduledata == null)
                   {
                     return response()->json(['message' => 'OCF not Saved']);
                   }
                   else
                   {
                        $checkmobile =  OCFCustomer::where('phone', $customer->phone)->first();

                        if($checkmobile == null)
                        {
                            return response()->json(['Message' => 'Invalid Mobile No', 'status' => 1]);
                        }
                        if($checkmobile == null )
                        {
                            return response()->json(['Message' => 'Mobile No invalid', 'status' => 1]);
                        }

                        $otp =  rand(100000, 999999);

                        $phone =  OCFCustomer::where('id', $request->customercode)->where('phone', $customer->phone)->first();

                        $verifyotp = [
                            'otp' => $otp,
                        ];
                        $update_verifyotp = $phone->update($verifyotp);
                        $otp_expires_time = Carbon::now('Asia/Kolkata')->addHours(48);

                        Log::info("otp = ".$otp);
                        Log::info("otp_expires_time = ".$otp_expires_time);
                        Cache::put('otp_expires_time', $otp_expires_time);
                        // $user = Customers::where('phone','=',$request->phone)->update(['otp' => $otp]);
                        $users = OCFCustomer::where('phone','=',$customer->phone)->update(['otp_expires_time' => $otp_expires_time]);

                        $url = "http://whatsapp.acmeinfinity.com/api/sendText?token=60ab9945c306cdffb00cf0c2&phone=91$$checkmobile->phone&message=Your%20unique%20registration%20key%20for%20Acme%20is%20$otp";

                        $params =
                        [
                            "to" => ["type" => "whatsapp", "number" => $customer->phone],
                            "from" => ["type" => "whatsapp", "number" => "9422031763"],
                            "message" =>
                            [
                                "content" =>
                                [
                                    "type" => "text",
                                    "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
                                ]
                            ]
                        ];
                        $headers = ["Authorization" => "Basic " . base64_encode(env('60ab9945c306cdffb00cf0c2') . ":" . env('60ab9945c306cdffb00cf0c2'))];
                        $client = new \GuzzleHttp\Client();
                        $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
                        $data = $response->getBody();
                        Log::Info($data);

                        return response()->json(['message' => 'OCF Created Successfully OTP Generated','status' => '0','OCF' => $insert_ocf, 'Module' => $data]);
                   }

                }
            }

    }
    else{
        $insert_ocf = OCF::where('DocNo', $str)->first();
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
    public function get_ocfdata_list_customerwise($DocNo){
        $data = DB::table('ocf_modules')
                ->leftJoin('ocf_master','ocf_master.id','=','ocf_modules.ocfcode')
                ->where('ocf_master.DocNo',$DocNo)->get();
 return $data;
    }

    public function getocf_modules($ocf)
    {
        $data = DB::table('ocf_modules')->where('ocfcode', $ocf)->get();
        return response()->json($data);
    }
}
