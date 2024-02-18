<?php

namespace App\Jobs;

use App\Models\Fund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;

class CheckDuplicatedFund implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const NO_DUPLICATES_FOUND = 'no new duplicated records found for fund %d';
    private const DUPLICATES_ITEM = 'fund id #%d has the same name or alias of fund #%d';
    private const DUPLICATES_FOUND = 'possible %d duplicate records found';
    private const ERROR_SENDING_MESSAGE = 'error while sending message to kafka';

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
        $fund                   = Fund::find($this->fundId);
        $fundIdentifiers        = $this->getFundIdentifiers($fund);
        $potentialDuplicates    = $this->getPotentialDuplicates($fund, $fundIdentifiers);

        $duplicateCount = $potentialDuplicates->count();
        if($duplicateCount > 0){

            Log::warning(sprintf(self::DUPLICATES_FOUND, $duplicateCount));
            foreach ($potentialDuplicates as $duplicate)
            {
                $this->sendMessages($fund->id, $duplicate->id);
                Log::warning(sprintf(self::DUPLICATES_ITEM, $fund->id, $duplicate->id));
            }

            return;
        }

        Log::info(sprintf(self::NO_DUPLICATES_FOUND, $fund->id));
    }

    protected function getFundIdentifiers(Fund $fund) : array
    {
        $fundIdentifiers = [ $fund->name ];
        foreach ($fund->aliases as $alias){
            $fundIdentifiers[] = $alias->name;
        }

        return $fundIdentifiers;
    }

    protected function getPotentialDuplicates(Fund $fund, array $fundIdentifiers) : Collection
    {
        $parent_id = $fund->id;
        return Fund::where(function ($query) use ($fundIdentifiers) {
                $query->whereIn('name', $fundIdentifiers)
                    ->orWhereHas('aliases', function ($builder) use ($fundIdentifiers) {
                        $builder->whereIn('name', $fundIdentifiers)
                            ->whereNull('deleted_at');
                    });
            })
            ->whereNull('deleted_at')
            ->whereNotIn('id', function ($subquery) use ($parent_id) {
                $subquery->select('duplicate_id')
                    ->from('duplicated_fund_candidate')
                    ->where('parent_id', '=', $parent_id)
                    ->where('resolved', '=', false);
            })
            ->where('id', '!=', $parent_id)
            ->get();
    }

    protected function sendMessages(int $fundId, int $duplicateId) : void
    {
        /** @var \Junges\Kafka\Producers\ProducerBuilder $producer */
        $producer = Kafka::publishOn('candidate-fund-duplicate')
            ->withBodyKey( 'candidate', [
                'parent_id'     => $fundId,
                'duplicate_id'  => $duplicateId
            ]);

        try {
            $producer->send();
        } catch (\Exception $e) {
            Log::error(self::ERROR_SENDING_MESSAGE);
            Log::error($e->getMessage());
        }
    }
}
