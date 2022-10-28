<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class hsnController extends Controller
{
   function index($id){

    $query =  DB::connection('mysql_2')->table('HsnCodesTable')->where('hsncode', $id)->get();
// return count($query);

    if (count($query)) {
        return response()->json(['data'=>$query[0],'status'=> 1]);
    }
    else{

        return response()->json(['status'=> 0]);

    }


   }
}
