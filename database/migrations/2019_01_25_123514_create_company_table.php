<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->string('name', 255);
            $table->string('cnpj')->unique();            
            $table->string('postcode', 10);            
            $table->string('address', 255);            
            $table->string('number',10);            
            $table->string('neighborhood', 255);            
            $table->string('city', 255);
            $table->string('state',255);
            $table->timestamps();
            $table->unsignedInteger('user_id')>unique();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company');
    }
}
