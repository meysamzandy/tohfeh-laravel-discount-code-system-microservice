<?php

namespace App\Jobs;

use App\Models\DiscountCode;
use App\Models\SuccessJobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ProcessAutoCodeCreation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $data;
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The maximum number of exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 1;
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 3600 * 4;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $codeModel = new DiscountCode();
        $result = $codeModel->createCode($this->data);
        $successJobs = new SuccessJobs($result);
        $successJobs->save();
    }
}
