<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveCodeEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::unprepared('CREATE EVENT `code_remove` ON SCHEDULE EVERY 1 DAY STARTS "2020-10-17 03:00:00.000000" ON COMPLETION NOT PRESERVE ENABLE DO 
             DELETE from discount_codes where discount_codes.cancel_date IS NOT NULL AND discount_codes.cancel_date < NOW() - INTERVAL 30 DAY'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
