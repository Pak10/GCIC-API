<?php

namespace App\Jobs\Investments;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SettleInvestment implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $investment;


    public function __construct($investment)
    {
        $this->investment =  $investment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
