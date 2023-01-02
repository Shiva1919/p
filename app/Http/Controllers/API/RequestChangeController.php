<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\ChangeRequestAction;
use App\Models\API\Company;
use App\Models\API\CompanyChangeRequest;
use App\Models\API\CustomerChangeRequest;
use App\Models\API\OCFCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Validator;

class RequestChangeController extends Controller
{
    public function customerchangerequest(Request $request)
    {
        $key = config('global.key');
        $data1=[];
        $getcustomer =  DB::table('customer_master')
                        ->select('customer_master.id', DB::raw('CAST(AES_DECRYPT(UNHEX(name),"'.$key.'") AS CHAR) AS name'), 'customer_master.entrycode',
                        DB::raw('CAST(AES_DECRYPT(UNHEX(email), "'.$key.'") AS CHAR) AS email'),
                        DB::raw('CAST(AES_DECRYPT(UNHEX(phone), "'.$key.'") AS CHAR) AS phone'),
                        DB::raw('CAST(AES_DECRYPT(UNHEX(whatsappno), "'.$key.'") AS CHAR) AS whatsappno'),
                        'customer_master.address1', 'customer_master.address2', 'customer_master.state',
                        'customer_master.district', 'customer_master.taluka', 'customer_master.city', 'customer_master.concernperson',
                        'customer_master.packagecode', 'customer_master.subpackagecode')
                        ->where('id','=',$request->customercode)
                        ->first();
      
        if($getcustomer == null)
        {
            return response()->json(['message' => 'Customer Not Exist', 'status' => 1]);
        }
        else
        {
            $rules = array(
                'customercode' => 'required',
                'customername' => 'required',
                'whattochange' => 'required',
                'oldvalue' => 'required',
                'newvalue' => 'required',
                'requestdatetime' => '',
                'status' => ''
            );
            $validator = Validator::make($request->all(), $rules);
            //Validation Fails
            if ($validator->fails())
            {
                return response()->json([
                    'message' => 'Invalid params passed',
                    'errors' => $validator->errors()
                ], 422);
            }
            else
            {
                $insert_customers = new CustomerChangeRequest();
                $insert_customers->customercode = $request->customercode;
                $insert_customers->customername = $request->customername;
                
                if($request->whattochange == 'phoneno')
                {
                    $insert_customers->whattochange = $request->whattochange;
                    if($getcustomer->phone == $request->oldvalue)
                    {
                        $insert_customers->oldvalue = DB::raw("HEX(AES_ENCRYPT('$request->oldvalue' , '$key'))");
                        $insert_customers->newvalue = DB::raw("HEX(AES_ENCRYPT('$request->newvalue' , '$key'))");
                    }
                    else
                    {
                        return response()->json(['message' => 'Invalid Phone No']);
                    }
                }
                else if($request->whattochange == 'whatsappno')
                {
                    $insert_customers->whattochange = $request->whattochange;
                    if($getcustomer->whatsappno == $request->oldvalue)
                    {
                        $insert_customers->oldvalue = DB::raw("HEX(AES_ENCRYPT('$request->oldvalue' , '$key'))");
                        $insert_customers->newvalue = DB::raw("HEX(AES_ENCRYPT('$request->newvalue' , '$key'))");
                    }
                    else
                    {
                        return response()->json(['message' => 'Invalid Whatsapp No']);
                    }
                }
                else if($request->whattochange == 'address1')
                {
                    $insert_customers->whattochange = $request->whattochange;
                    $insert_customers->oldvalue =$request->oldvalue;
                    $insert_customers->newvalue = $request->newvalue;
                    // foreach ($request->oldvalue as $data )
                    // { 
                    //    if($getcustomer->address1 == $data['address1'] && $getcustomer->address2 == $data['address2'] && $getcustomer->state == $data['state'] && $getcustomer->district == $data['district'] && $getcustomer->taluka == $data['taluka'] && $getcustomer->city == $data['city'])
                    //    {
                    //         $data=[
                    //             'address1'=> $data['address1'],
                    //             'address2'=> $data['address2'],
                    //             'state'=> $data['state'],
                    //             'district'=> $data['district'],
                    //             'taluka' => $data['taluka'],
                    //             'city' => $data['city'],
                    //         ];
                    //         array_push($data1,$data);
                    //         $insert_customers->oldvalue = json_encode($data);
                    //    }
                    //    else{
                    //         return response()->json(['message' => 'Data Mismatch']);
                    //    }
                    // }
                    // foreach ($request->newvalue as $newdata )
                    // {
                    //     $newdata=[
                    //         'address1'=> $data['address1'],
                    //         'address2'=> $data['address2'],
                    //         'state'=> $data['state'],
                    //         'district'=> $data['district'],
                    //         'taluka' => $data['taluka'],
                    //         'city' => $data['city'],
                    //     ];
                    //     array_push($data1,$newdata);
                    //    $insert_customers->newvalue = json_encode($newdata);
                    // }
                }
                else if($request->whattochange == 'address2')
                {
                    $insert_customers->whattochange = $request->whattochange;
                    $insert_customers->oldvalue =$request->oldvalue;
                    $insert_customers->newvalue = $request->newvalue;
                }
                else{
                    return response()->json(['message' => 'Field Not Exist', 'status' => 1]);
                }   
                $time= date('Y-m-d H:i:s');
                $insert_customers->requestdatetime = $time;
                $insert_customers->save();
                return response()->json(['message' => 'Data Updated', 'Customer Updated Data' => $insert_customers]);
            }
        }
    }

    public function companychangerequest(Request $request)
    {
        $key = config('global.key');
        $getcompany =  DB::table('company_master')
                                    ->select('company_master.id','company_master.customercode', DB::raw('CAST(AES_DECRYPT(UNHEX(companyname), "'.$key.'") AS CHAR) AS companyname'),
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(panno), "'.$key.'") AS CHAR) AS panno'),
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(gstno), "'.$key.'") AS CHAR) AS gstno'),
                                    'company_master.InstallationType', 'company_master.InstallationDesc')
                                    ->where('customercode','=', $request->customercode)
                                    ->where('id','=', $request->companycode)
                                    ->first();
        
        if($getcompany == null)
        {
            return response()->json(['message' => 'Customer Not Exist', 'status' => 1]);
        }
        else
        {
            $rules = array(
                'customercode' => 'required',
                'companycode' => 'required',
                'whattochange' => 'required',
                'oldvalue' => 'required',
                'newvalue' => 'required',
                'requestdatetime' => '',
                'status' => ''
            );
            $validator = Validator::make($request->all(), $rules);
            //Validation Fails
            if ($validator->fails())
            {
                return response()->json([
                    'message' => 'Invalid params passed',
                    'errors' => $validator->errors()
                ], 422);
            }
            else
            {
                $insert_company = new CompanyChangeRequest();
                $insert_company->customercode = $request->customercode;
                $insert_company->companycode = $request->companycode;
                
                if($request->whattochange == 'Company Name')
                {
                    $insert_company->whattochange = $request->whattochange;
                    if($getcompany->companyname == $request->oldvalue)
                    {
                        $insert_company->oldvalue = DB::raw("HEX(AES_ENCRYPT('$request->oldvalue' , '$key'))");
                        $insert_company->newvalue = DB::raw("HEX(AES_ENCRYPT('$request->newvalue' , '$key'))");
                    }
                    else
                    {
                        return response()->json(['message' => 'Company Not Exist']);
                    }
                }
                else if($request->whattochange == 'PAN NO')
                {
                    $insert_company->whattochange = $request->whattochange;
                    if($getcompany->panno == $request->oldvalue)
                    {
                        $insert_company->oldvalue = DB::raw("HEX(AES_ENCRYPT('$request->oldvalue' , '$key'))");
                        $insert_company->newvalue = DB::raw("HEX(AES_ENCRYPT('$request->newvalue' , '$key'))");
                    }
                    else
                    {
                        return response()->json(['message' => 'Please Check Your Old PAN No']);
                    }
                }
                else if($request->whattochange == 'GST NO')
                {
                    $insert_company->whattochange = $request->whattochange;
                    if($getcompany->gstno == $request->oldvalue)
                    {
                        $insert_company->oldvalue = DB::raw("HEX(AES_ENCRYPT('$request->oldvalue' , '$key'))");
                        $insert_company->newvalue = DB::raw("HEX(AES_ENCRYPT('$request->newvalue' , '$key'))");
                    }
                    else
                    {
                        return response()->json(['message' => 'Please Check Your Old GST No']);
                    }
                }
                else{
                    return response()->json(['message' => 'Field Not Exist', 'status' => 1]);
                }   
                $time= date('Y-m-d H:i:s');
                $insert_company->requestdatetime = $time;
                $insert_company->save();
                return response()->json(['message' => 'Data Updated', 'Company Updated Data' => $insert_company]);
            }
        }
    }

    public function changerequestaction(Request $request)
    {
        $key = config('global.key');
        $changerequest = new ChangeRequestAction();
        $changerequest->requestontable = $request->requestontable;
        $changerequest->usercode = $request->usercode;
        $updatecustomer = OCFCustomer::where('id', $request->usercode)->orderBy('id', 'desc')->first();
        $updatecompany = Company::where('id', $request->usercode)->orderBy('id', 'desc')->first();
        $time= date('Y-m-d H:i:s');
        DB::beginTransaction();
        try{
            if($request->requestontable == 1)
            {
                $getchangerequest =CustomerChangeRequest::where('customercode','=', $request->usercode)->where('whattochange', $request->whattochange)
                                            ->orderBy('id', 'desc')->first();
                
            
                if($request->actionvalue == "pass")
                {   
                    $changerequest->actionvalue = $request->actionvalue;
                    $changerequest->description = $request->description;
                    if($request->whattochange == "phoneno" )
                    { 
                        $data = DB::table('customer_history')
                            ->insert(array(
                                'customerid' => $updatecustomer->id,
                                'name' => $updatecustomer->name,
                                'phone' =>$updatecustomer->phone,   
                                'created_at'=> now(),
                                'updated_at'  => now()
                            ));
                    
                        $updatecustomer->update(['phone' => $getchangerequest->newvalue]); 
                    }
                    else if ($request->whattochange == "whatsappno")
                    {
                        $data = DB::table('customer_history')
                            ->insert(array(
                                'customerid' => $updatecustomer->id,
                                'name' => $updatecustomer->name,
                                'whatsappno' =>$updatecustomer->whatsappno,   
                                'created_at'=> now(),
                                'updated_at'  => now()
                            ));

                        $updatecustomer->update(['whatsappno' => $getchangerequest->newvalue]); 
                            
                    }
                    else if ($request->whattochange == "address1")
                    {
                        $data = DB::table('customer_history')
                            ->insert(array(
                                'customerid' => $updatecustomer->id,
                                'name' => $updatecustomer->name,
                                'address1' =>$updatecustomer->address1,   
                                'created_at'=> now(),
                                'updated_at'  => now()
                            ));

                        $updatecustomer->update(['address1' => $getchangerequest->newvalue]); 
                    }
                    else if ($request->whattochange == "address2")
                    {
                        $data = DB::table('customer_history')
                            ->insert(array(
                                'customerid' => $updatecustomer->id,
                                'name' => $updatecustomer->name,
                                'address2' =>$updatecustomer->address2,   
                                'created_at'=> now(),
                                'updated_at'  => now()
                            ));

                        $updatecustomer->update(['address2' => $getchangerequest->newvalue]); 
                    }
                    else{
                        return response()->json(['message' =>'Field Not Exist', 'status' => 1]);
                    }
                    
                }
                else if($request->actionvalue == 'reject')
                {
                    $changerequest->actionvalue = $request->actionvalue;
                    $changerequest->description = $request->description;
                }
                else if($request->actionvalue == 'pending')
                {
                    $changerequest->actionvalue = $request->actionvalue;
                    $changerequest->description = $request->description;
                }
                else{
                    return response()->json(['message' => 'Invalid Action', 'status' => 1]);
                }
                
                $changerequest->actiondatedtime = $time;
                $changerequest->save();
                DB::commit();
                return response()->json(['message' => 'Change Action Updated', 'Action' => $changerequest]);
            }
            else if($request->requestontable == 2 )
            {
                $getchangerequest = CompanyChangeRequest::select('companychangerequest.companycode', 'companychangerequest.whattochange',
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(oldvalue), "'.$key.'") AS CHAR) AS oldvalue'),
                                    DB::raw('CAST(AES_DECRYPT(UNHEX(newvalue), "'.$key.'") AS CHAR) AS newvalue'))
                                    ->where('companycode','=', $request->usercode)
                                    ->orderBy('id', 'desc')
                                    ->first();
                $getchangerequest =CompanyChangeRequest::where('companycode','=', $request->usercode)->where('whattochange', $request->whattochange)
                                ->orderBy('id', 'desc')->first();
            
                if($request->actionvalue == "pass")
                {   
                    $changerequest->actionvalue = $request->actionvalue;
                    $changerequest->description = $request->description;
                    if($request->whattochange == "Company Name" )
                    {
                        $data = DB::table('company_history')
                            ->insert(array(
                                'companyid' => $updatecompany->id,
                                'companyname' => $updatecompany->companyname,  
                                'created_at'=> now(),
                                'updated_at'  => now()
                            ));

                        $updatecompany->update(['companyname' => $getchangerequest->newvalue]); 
                    }
                    else if($request->whattochange == "PAN NO" )
                    {
                        $data = DB::table('company_history')
                            ->insert(array(
                                'companyid' => $updatecompany->id,
                                'panno' => $updatecompany->panno,  
                                'created_at'=> now(),
                                'updated_at'  => now()
                            ));

                        $updatecompany->update(['panno' => $getchangerequest->newvalue]); 
                    }
                    else if($request->whattochange == "GST NO" )
                    {
                        $data = DB::table('company_history')
                            ->insert(array(
                                'companyid' => $updatecompany->id,
                                'gstno' => $updatecompany->gstno,  
                                'created_at'=> now(),
                                'updated_at'  => now()
                            ));

                        $updatecompany->update(['gstno' => $getchangerequest->newvalue]); 
                    }
                    else{
                        return response()->json(['message' => 'Field Not Exist', 'status' => 1]);
                    }
            
                    $changerequest->actiondatedtime = $time;
                    $changerequest->save();
                    DB::commit();
                    return response()->json(['message' => 'Change Action Updated', 'Action' => $changerequest]);
                }
                else{
                    return response()->json(['message' => 'Invalid Request']);
                }
            }
            else
            {
                return response()->json(['message' => 'Table Not Exist', 'status' => 1]);
            }     
        }
        catch(Throwable $e)
        {
            DB::rollBack();
            throw $e;
        }  
    }
}


