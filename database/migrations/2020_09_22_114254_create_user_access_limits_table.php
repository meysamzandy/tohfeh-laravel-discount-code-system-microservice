<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserAccessLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('user_access_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('code_id')->constrained('discount_codes')->onDelete('cascade');
            $table->uuid('uuid')->comment('user uuid in user management system');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('user_access_limits');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
