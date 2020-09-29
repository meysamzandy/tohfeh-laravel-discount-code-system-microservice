<?php

namespace App\Jobs;

use App\Models\DiscountCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ProcessAutoCodeCreation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $codes;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @param DiscountCode $codes
     * @param $data
     */
    public function __construct(DiscountCode $codes,$data)
    {

        $this->codes = $codes;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $this->codes->createCode($this->data);

    }
}
