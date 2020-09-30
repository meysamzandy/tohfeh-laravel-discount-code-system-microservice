<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMarketAccessLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('market_access_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('code_id')->constrained('discount_codes')->onDelete('cascade');
            $table->string('market_name', 40)->comment('market name');
            $table->string('version_major', 3)->comment('market major version');
            $table->string('version_minor', 3)->comment('market minor version');
            $table->string('version_patch', 3)->comment('market patch version');

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
        Schema::dropIfExists('market_access_limits');
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
