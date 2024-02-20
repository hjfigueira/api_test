<?php

namespace App\Jobs\Services;

use App\Models\Fund;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Producers\ProducerBuilder;

class CheckDuplicatedFundService
{
    private const string NO_DUPLICATES_FOUND   = 'no new duplicated records found for fund %d';
    private const string DUPLICATES_ITEM       = 'fund id #%d has the same name or alias of fund #%d';
    private const string DUPLICATES_FOUND      = 'possible %d duplicate records found';
    private const string ERROR_SENDING_MESSAGE = 'error while sending message to kafka';


    /**
     * Execute the job.
     */
    public function handle(?int $fundId): void
    {
        if ($fundId == null) {
            $fundCheckCollection = Fund::all();
        } else {
            $fundCheckCollection = Fund::query()->where('id', $fundId)->get();
        }

        /** @var Fund $fund */
        foreach ($fundCheckCollection as $fund) {
            $this->checkDuplicatesForFund($fund);
        }
    }


    protected function checkDuplicatesForFund(Fund $fund) : void
    {
        $fundIdentifiers     = $this->getFundIdentifiers($fund);
        $potentialDuplicates = $this->getPotentialDuplicates($fund, $fundIdentifiers);

        $duplicateCount = $potentialDuplicates->count();
        if ($duplicateCount > 0) {
            Log::warning(sprintf(self::DUPLICATES_FOUND, $duplicateCount));

            /** @var Fund $duplicate */
            foreach ($potentialDuplicates as $duplicate) {
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

        /** @var Fund $alias */
        foreach ($fund->aliases as $alias) {
            $fundIdentifiers[] = $alias->name;
        }

        return $fundIdentifiers;
    }


    protected function getPotentialDuplicates(Fund $fund, array $fundIdentifiers) : Collection
    {
        $parent_id = $fund->id;
        return Fund::query()->where(
            function ($query) use ($fundIdentifiers) {
                $query->whereIn('name', $fundIdentifiers)
                    ->orWhereHas(
                        'aliases',
                        function ($builder) use ($fundIdentifiers) {
                            $builder->whereIn('name', $fundIdentifiers)
                                ->whereNull('deleted_at');
                        }
                    );
            }
        )
            ->whereNull('deleted_at')
            ->whereNotIn(
                'id',
                function ($subquery) use ($parent_id) {
                    $subquery->select('duplicate_id')
                        ->from('fund_duplicated_candidate')
                        ->where('parent_id', '=', $parent_id)
                        ->where('resolved', '=', false);
                }
            )
            ->where('id', '!=', $parent_id)
            ->get();
    }


    protected function sendMessages(int $fundId, int $duplicateId) : void
    {
        /*
         * @var ProducerBuilder $producer
         */
        $producer = Kafka::publishOn('duplicate_fund_warning')
            ->withBodyKey(
                'candidate',
                [
                    'parent_id'    => $fundId,
                    'duplicate_id' => $duplicateId,
                ]
            );

        try {
            $producer->send();
        } catch (\Exception $e) {
            Log::error(self::ERROR_SENDING_MESSAGE);
            Log::error($e->getMessage());
        }
    }
}
