<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('discount_code_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->comment('name for the group of a discount code');
            $table->string('series',255)->unique()->nullable()->comment('use for external creation discount code api');
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
        Schema::dropIfExists('discount_code_groups');
    }
}
