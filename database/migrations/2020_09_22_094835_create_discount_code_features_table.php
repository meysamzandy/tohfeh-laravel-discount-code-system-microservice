<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodeFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('discount_code_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('discount_code_groups')->onDelete('cascade');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamp('start_time')->comment('Code start time validation.');
            $table->timestamp('end_time')->default(now())->comment('Code end time validation.');
            $table->string('code_type', 10)->comment('has three parameters : free & percent & price');
            $table->unsignedSmallInteger('percent')->nullable()->comment('between 0 & 100');
            $table->unsignedInteger('limit_percent_price')->nullable()->comment('Maximum amount allowed to apply discount percentage.');
            $table->unsignedInteger('price')->nullable()->comment('The amount of discount');
            $table->string('description', 255)->comment('description for feature');
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
        Model::unguard();
        $this->setFKCheckOff();
        Schema::dropIfExists('discount_code_features');
        $this->setFKCheckOn();
        Model::reguard();

    }

    private function setFKCheckOff(): void
    {
        switch(DB::getDefaultConnection()) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = OFF');
                break;
        }
    }

    private function setFKCheckOn(): void
    {
        switch(DB::getDefaultConnection()) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = ON');
                break;
        }
    }
}
