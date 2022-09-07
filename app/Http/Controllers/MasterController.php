<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function order_confirmation()
    {
        return view('master.order_confirmation_form');
    }
}
