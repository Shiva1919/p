<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\JSONStore;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JSONStoreController extends Controller
{
    public function index()
    {
        $apiurl = '172.16.2.127:8000/api/packagedata';
        $response = Http::get($apiurl);

        $data = json_decode($response->body());
        print_r($data);
        die;
    }
}
