<?php

namespace Tests\Unit;

use App\Models\API\BroadcastMessage;
use App\Models\API\Users;
use App\Models\User;
use Facade\Ignition\Support\Packagist\Package;
use Illuminate\Support\Facades\Broadcast;
use Tests\TestCase;

class RouteTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->withoutMiddleware();
        $credential = [
            'email' => 'admin123@gmail.com',
            'password' => 'Admin@123'
        ];

        $response = $this->post('login',$credential);

        $response->assertSessionMissing('errors');
    }

    public function test_user()
    {
        $this->withoutMiddleware();
        $response = $this->get('users');
        $response->assertSessionMissing('errors');
    }

    // public function test_useradd()
    // {
    //     $this->withoutMiddleware();
    //     $ownUser = Users::factory()->create([ "name" => "Test",
    //     "last_name" => "Data",
    //     "email" => "test62251@gmail.com",
    //     "phone" => "9139465257",
    //     "password" => "Test@123",
    //     "active" => 1,
    //     "role_id" => 1,
    //     "permission_id" => 1]);
    //     $response = $this->post('users');
    //     $response->assertSessionMissing('errors');


    // }

    // public function test_userupdate()
    // {
    //     $this->withoutMiddleware();
    //     $ownUser = Users::factory()->create(["last_name" => "Data"]);
    //     $response = $this->post('users/{$ownUser->id}',["last_name" => "Data1"] );
    //     $response->assertSessionMissing('errors');
    // }

    public function test_roles()
    {
        $this->withoutMiddleware();
        $response = $this->post('roles');
        $response->assertSessionMissing('errors');
    }

    public function test_permissions()
    {
        $this->withoutMiddleware();
        $response = $this->post('permissions');
        $response->assertSessionMissing('errors');
    }

    public function test_package()
    {
        $this->withoutMiddleware();
        $response = $this->get('package');
        $response->assertSessionMissing('errors');
    }

    public function test_customer()
    {
        $this->withoutMiddleware();
        $response = $this->post('customer');
        $response->assertSessionMissing('errors');
    }

    public function test_customerid()
    {
        $this->withoutMiddleware();
        $ownUser = User::factory();
        $response = $this->post('getcustomer/{$ownUser->id}');
        $response->assertSessionMissing('errors');
    }

    public function test_broadcastadd()
    {
        $this->withoutMiddleware();
        $credential = [
            "messagetarget"=> 3,
            "customercode"=> 2779,
            "companycode"=> 1,
            "packagecode"=> 3,
            "subpackagecode"=> 4,
            "gsttype"=> 1,
            "datefrom"=>"20-12-2022 14:41:19",
            "todate"=> "22-12-2022 14:59:19",
            "messagetitle"=> "Message Title",
            "messagedesc"=> "Message Description",
            "active"=> 1,
            "howmanydaystodisplay"=> 2,
            "allowtomarkasread"=> 1,
            "rolecode"=> 10,
            "url"=> "http://172.16.2.127:4200/customer/customerlogin/12/K0sPeMDrDo9WhZUHcfYevkCy6ocIHdZNs9ZRcE9Q",
            "specialkeytoclose"=> 0
        ];
        $user = BroadcastMessage::factory()->create();
        $response = $this->post('broadcast', $credential);
        $response->assertSessionMissing('errors');
    }


}
