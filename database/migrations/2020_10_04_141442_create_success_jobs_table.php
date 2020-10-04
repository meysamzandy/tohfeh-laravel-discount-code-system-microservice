<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuccessJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('success_jobs', function (Blueprint $table) {
            $table->id();
            $table->boolean('resultStats');
            $table->string('body')->nullable();
            $table->string('message')->nullable();
            $table->smallInteger('statusCode');
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
        Schema::dropIfExists('success_jobs');
    }
}
