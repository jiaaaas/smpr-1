<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerformanceReportsTable extends Migration
{
    public function up()
    {
        Schema::create('performance_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type');
            $table->string('month')->nullable();
            $table->year('year');
            $table->json('employee_ids')->nullable();
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('performance_reports');
    }
}
