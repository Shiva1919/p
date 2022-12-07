<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Customer_mobile_Model;
use Illuminate\Support\Facades\DB;

class Customer_Mobile extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($custid)
    {
        $key = config('global.key');
        $data =Customer_mobile_Model::where('Customercode', $custid)->where('active_flag',1)
        ->get(['id',DB::raw('CAST(AES_DECRYPT(UNHEX(Mobile_number), '.$key.') AS CHAR) AS Mobile_number'),
        DB::raw('CAST(AES_DECRYPT(UNHEX(Email), \'YsfaHZ7FCKJcAEb7UuTX+QCQzJa7kR1bMflozJzmyOY=\') AS CHAR) AS Email'),'User_Name','Customercode','active_flag']);
        return response()->json($data);
    }
   function getcustomer($id){
    $key = config('global.key');
    return  $data =Customer_mobile_Model::where('Customercode',$id)->where('active_flag',1)->get();
        // return response()->json($data);
    }
    function getcustomer_mobile($id,$mobile){
        $key = config('global.key');
        $data =Customer_mobile_Model::where('Customercode',$id)->where('Mobile_number',$mobile)->where('active_flag',1)->get();
        if (count($data) > 0) {
            return response()->json([
                'status'=>404,
                'message'=>"Allready Saved Data"
            ]);
        }
        else{
            return response()->json([
                'status'=>200,
            ]);

        }
        return response()->json($data);
    }
    function getmobile_edit($id,$custid){
        $key = config('global.key');
        $data =Customer_mobile_Model::where('Customercode',$custid)->where('id',$id)
        ->first(['id',DB::raw('CAST(AES_DECRYPT(UNHEX(Mobile_number), '.$key.') AS CHAR) AS Mobile_number'),
        DB::raw('CAST(AES_DECRYPT(UNHEX(Email), \'YsfaHZ7FCKJcAEb7UuTX+QCQzJa7kR1bMflozJzmyOY=\') AS CHAR) AS Email'),'User_Name','Customercode','active_flag']);
        return response()->json($data);
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
          $key = config('global.key');
        $data = Customer_mobile_Model::where('Mobile_number',DB::raw("HEX(AES_ENCRYPT('$request->Mobilenumber','$key'))"))->first();
        if ($data) {
            $data->active_flag=1;
            $data->update();
            return response()->json([
                'status'=>200,
                'message'=>'Added Successfully'
            ]);
        }
        else{
              $Customer_mobile_Model= new Customer_mobile_Model;
              $Customer_mobile_Model->Mobile_number= DB::raw("HEX(AES_ENCRYPT('$request->Mobilenumber' , '$key'))");
              $Customer_mobile_Model->Email= DB::raw("HEX(AES_ENCRYPT('$request->Email' , '$key'))");
              $Customer_mobile_Model->User_Name= $request->UserName;
              $Customer_mobile_Model->Customercode= $request->Customercode;
              $Customer_mobile_Model->save();
              return response()->json([
                'status'=>200,
                'message'=>'Added Successfully'
            ]);
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
        $Customer_mobile_Model= Customer_mobile_Model::find($id);
        if ($Customer_mobile_Model) {
            return response()->json([
                'status'=>200,
                'data'=>$Customer_mobile_Model
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'Not Found'
            ]);

        }
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


        $Customer_mobile_Model= Customer_mobile_Model::find($id);
         if ($Customer_mobile_Model) {
        $Customer_mobile_Model->Mobile_number= DB::raw("HEX(AES_ENCRYPT('$request->Mobilenumber' , '$key'))");
        $Customer_mobile_Model->Email= DB::raw("HEX(AES_ENCRYPT('$request->Email' , '$key'))");
        $Customer_mobile_Model->User_Name= $request->UserName;
        $Customer_mobile_Model->update();
          return response()->json([
            'status'=>200,
            'message'=>'Update Successfully'
        ]);
      }
      else{
          return response()->json([
              'status'=>404,
              'message'=>'Not Found'
          ]);

      }

    }
    function allerdy_mobile($mobile){

        $data = DB::table('Customer_mobilenumbers')->where('Mobile_number',DB::raw("HEX(AES_ENCRYPT('$mobile','$key'))"))->where('active_flag',1)->get();
        if (count($data) > 0 ) {
            return response()->json([
                'status'=>1,
                'message'=>'Data Found'
            ]);

        }
        else{
            return response()->json([
                'status'=>0,
                'message'=>'Data Not Found'
            ]);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
    $Customer_mobile_Model= Customer_mobile_Model::find($id);
    $Customer_mobile_Model->active_flag=0;
    $Customer_mobile_Model->update();
    return response()->json([
        'status'=>200,
        'message'=>'Delete Successfully'
    ]);
    }
}
