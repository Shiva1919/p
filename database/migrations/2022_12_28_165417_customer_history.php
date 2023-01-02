<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('customer_history', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('entrycode');
            $table->string('address1');
            $table->string('address2');
            $table->integer('otp');
            $table->integer('serialotp');
            $table->string('phone')->unique();
            $table->string('whatsappno')->unique();
            $table->string('email')->unique();
            $table->string('state');
            $table->string('district');
            $table->string('taluka');
            $table->string('city');
            $table->integer('role_id');
            $table->string('password');
            $table->string('concernperson');
            $table->integer('packagecode');
            $table->integer('subpackagecode');
            $table->integer('customercode');
           
            $table->timestamps();
        });
    }
  
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
