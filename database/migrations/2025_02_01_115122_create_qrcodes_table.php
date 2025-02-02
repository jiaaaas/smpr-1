<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrcodesTable extends Migration
{
    public function up()
    {
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('otp');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qrcodes');
    }
}