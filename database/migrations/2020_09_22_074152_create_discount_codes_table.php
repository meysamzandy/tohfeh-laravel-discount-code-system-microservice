<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('discount_code_groups')->onDelete('cascade');
            $table->string('code',40)->unique()->index()->comment('discount codes are here');
            $table->string('created_type',10)->default('manual')->comment('has two parameters : auto & manual');
            $table->string('access_type',10)->default('public')->comment('has two parameters : public & private');
            $table->unsignedBigInteger('usage_limit')->default(1)->comment('count of authorized uses for this code.');
            $table->unsignedBigInteger('usage_count')->default(0)->comment('count used of this code');
            $table->unsignedInteger('usage_limit_per_user')->default(1)->comment('count of authorized uses for this code per user.');
            $table->boolean('first_buy')->default(false)->comment("Is this code for the user's first purchase?");
            $table->boolean('has_market')->default(false)->comment('Does this code have restrictions on use in a particular market?');
            $table->timestamp('cancel_date')->nullable()->comment('Date that this code can no longer be used.');
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
        Schema::dropIfExists('discount_codes');
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
