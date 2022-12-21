<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Sendurl;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url =Sendurl::all();
        return $url;
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

        $validater=Validator::make($request->all(),[
            'url'=>'required'
        ]);
        if ($validater->fails()) {
            return response()->json([
                'status'=>400,
                'error'=>$validater->messages()
            ]);

        }
        else
        {
              $url= new Sendurl;
              $url->url= $request->input('url');
              $url->save();
              return response()->json([
                'status'=>200,
                'message'=>'Url Added Successful'
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
        $url= Sendurl::find($id);
        if ($url) {
            return response()->json([
                'status'=>200,
                'url'=>$url
            ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'Url Not Found'
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
    public function update(Request $request,$id)
    {
             $url= Sendurl::find($id);
              if ($url) {
                $validater=Validator::make($request->all(),[
                    'url'=>'required'
                ]);
                $url->url= $request->input('url');
                $url->update();
                return response()->json([
                  'status'=>200,
                  'error'=>$validater->messages(),
                  'message'=>'Url Update Successful'
              ]);
            }
            else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Url Not Found'
                ]);

            }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $url= Sendurl::find($id);
    $url->delete();
    return response()->json([
        'status'=>200,
        'message'=>'Url Delete Successful'
    ]);
    }
}
