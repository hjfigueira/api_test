<?php

namespace App\Console\Commands;

use App\Models\DuplicatedFundCandidate;
use App\Models\Fund;
use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Contracts\KafkaConsumerMessage;

class StartListenerDuplicatedFunds extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'event-listener:duplicated-funds';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Start an event listener to events of duplicated funds.';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $consumer = Kafka::createConsumer(['candidate-fund-duplicate'])
            ->withAutoCommit()
            ->withHandler(function(KafkaConsumerMessage $message) {
                $this->bindMessage($message);
            })
            ->build();

        $consumer->consume();
    }

    public function bindMessage(KafkaConsumerMessage $message)
    {
        $messageBody    = $message->getBody();
        $parentId       = $messageBody['candidate']['parent_id'];
        $duplicateId    = $messageBody['candidate']['duplicate_id'];

        $parent     = Fund::find($parentId);
        $duplicate  = Fund::find($duplicateId);

        $duplicatedCandidate = new DuplicatedFundCandidate();
        $duplicatedCandidate->parent()->associate($parent);
        $duplicatedCandidate->duplicate()->associate($duplicate);
        $duplicatedCandidate->save();
    }

}
