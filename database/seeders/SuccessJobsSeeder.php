<?php

namespace Database\Seeders;

use App\Models\SuccessJobs;
use Illuminate\Database\Seeder;

class SuccessJobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SuccessJobs::factory()->create();
    }
}
