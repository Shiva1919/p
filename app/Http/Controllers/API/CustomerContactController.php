<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer_mobile_Model;
use Illuminate\Http\Request;

class CustomerContactController extends Controller
{
    public function index($custid)
    {
        $data =Customer_mobile_Model::where('Customercode', $custid)->get();
        return response()->json($data);
    }
}
