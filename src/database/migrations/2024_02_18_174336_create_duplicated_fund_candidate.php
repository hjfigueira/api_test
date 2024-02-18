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
        Schema::create('duplicated_fund_candidate', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('resolved')->default(false);
            $table->integer('parent_id');
            $table->integer('duplicate_id');

            $table->foreign('parent_id')->references('id')->on('fund');
            $table->foreign('duplicate_id')->references('id')->on('fund');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duplicated_fund_candidate');
    }
};
