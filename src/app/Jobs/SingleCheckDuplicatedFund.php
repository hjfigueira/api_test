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

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected ?int $fundId,
    )
    {
    }


    /**
     * Execute the job.
     */
    public function handle(CheckDuplicatedFundService $checkForDuplicatesService): void
    {
        $checkForDuplicatesService->handle($this->fundId);
    }
}
