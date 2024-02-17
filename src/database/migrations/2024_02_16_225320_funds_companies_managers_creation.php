<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fund_manager', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name',255);
        });

        Schema::create('fund', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name',255);
            $table->integer('start_year')->comment('year when the fund first started');
            $table->integer("fund_manager_id")->unsigned();
            $table->foreign('fund_manager_id')->references('id')->on('fund_manager');
        });

        Schema::create('alias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name',255);
            $table->integer("fund_id")->unsigned();
            $table->foreign('fund_id')->references('id')->on('fund');
        });

        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name',255);
        });

        Schema::create('company_fund', function (Blueprint $table) {
            $table->integer("company_id")->unsigned();
            $table->integer("fund_id")->unsigned();
            $table->primary(['company_id','fund_id']);
            $table->foreign('company_id')->references('id')->on('company');
            $table->foreign('fund_id')->references('id')->on('fund');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_fund');
        Schema::dropIfExists('company');
        Schema::dropIfExists('alias');
        Schema::dropIfExists('fund');
        Schema::dropIfExists('fund_manager');
    }
};
