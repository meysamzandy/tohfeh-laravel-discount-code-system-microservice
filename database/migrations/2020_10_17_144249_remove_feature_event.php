<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveFeatureEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::unprepared('CREATE EVENT `feature_remove` ON SCHEDULE EVERY 1 DAY STARTS "2020-10-17 03:00:00.000000" ON COMPLETION NOT PRESERVE ENABLE DO
        DELETE from discount_code_features where discount_code_features.end_time < NOW() - INTERVAL 30 DAY'
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::unprepared('DROP EVENT IF EXISTS feature_remove');
    }
}
