<?php

namespace App\Jobs;

use App\Models\Fund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;

class CheckDuplicatedFund implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const NO_DUPLICATES_FOUND = 'no duplicated records found by name or aliases';
    private const DUPLICATES_ITEM = 'fund id #%d has the same name or alias of fund #%d';
    private const DUPLICATES_FOUND = 'possible %d duplicate records found';

    private int $fundId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $fundId)
    {
        $this->fundId = $fundId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var Fund $fund */
        $fund = Fund::find($this->fundId);

        $fundIdentifiers = [ $fund->name ];
        foreach ($fund->aliases as $alias){
            $fundIdentifiers[] = $alias->name;
        }

        $potentialDuplicates = Fund::where(function($query) use ($fundIdentifiers){
            $query
                ->whereIn('name', $fundIdentifiers)
                ->orWhereHas('aliases', function($builder) use ($fundIdentifiers){
                    return $builder->whereIn('name', $fundIdentifiers);
                });
        })
        ->where('id', '!=', $fund->id)
        ->get();

        $duplicateCount = $potentialDuplicates->count();
        if($duplicateCount > 0){
            Log::warning(sprintf(self::DUPLICATES_FOUND, $duplicateCount));

            foreach ($potentialDuplicates as $duplicate)
            {
                /** @var \Junges\Kafka\Producers\ProducerBuilder $producer */
                $producer = Kafka::publishOn('candidate-fund-duplicate')
                    ->withHeaders([
                        'parent_id' => $fund->id,
                        'duplicate_id' => $duplicate->id
                    ]);

                $producer->send();

                Log::warning(sprintf(self::DUPLICATES_ITEM, $fund->id, $duplicate->id));
            }

            return;
        }

        Log::info(self::NO_DUPLICATES_FOUND);
    }
}
