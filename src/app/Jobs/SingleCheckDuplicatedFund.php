<?php

namespace App\Jobs;

use App\Jobs\Services\CheckDuplicatedFundService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SingleCheckDuplicatedFund implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $fundId;


    /**
     * Create a new job instance.
     */
    public function __construct(?int $fundId)
    {
        $this->fundId = $fundId;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $checkForDuplicatesService = new CheckDuplicatedFundService();
        $checkForDuplicatesService->handle($this->fundId);
    }
}
