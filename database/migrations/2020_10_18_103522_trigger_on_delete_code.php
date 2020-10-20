<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TriggerOnDeleteCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::unprepared('
        CREATE TRIGGER group_check_on_delete_code AFTER DELETE ON `discount_codes` FOR EACH ROW
            BEGIN
                SET @COUNT=(SELECT COUNT(id) FROM discount_codes WHERE group_id=OLD.group_id);
                IF @COUNT = 0 THEN
                DELETE from discount_code_groups WHERE id=OLD.group_id;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::unprepared('DROP EVENT IF EXISTS group_check_on_delete_feature');
    }
}
