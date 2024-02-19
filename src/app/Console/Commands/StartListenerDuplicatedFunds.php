<?php

namespace App\Console\Commands;

use App\Models\FundDuplicatesCandidate;
use App\Models\Fund;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Contracts\KafkaConsumerMessage;

/**
 * Create the listener for kafka events
 */
class StartListenerDuplicatedFunds extends Command
{
    protected const string KAFKA_MESSAGE_RECEIVED  = 'new message received for topic %s : %s';
    protected const string KAFKA_MESSAGE_ERROR     = 'error while processing message from topic %s : %s';
    protected const string KAFKA_MESSAGE_LISTENING = 'listening for kafka messages for topic %s';

    protected const string KAFKA_TOPIC = 'candidate-fund-duplicate';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event-listener:duplicated-funds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start an event listener to events of duplicated funds.';


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->output->info(sprintf(self::KAFKA_MESSAGE_LISTENING, self::KAFKA_TOPIC));
        Log::info(sprintf(self::KAFKA_MESSAGE_LISTENING, self::KAFKA_TOPIC));

        $consumer = Kafka::createConsumer([self::KAFKA_TOPIC])
            ->withAutoCommit()
            ->withHandler(
                function (KafkaConsumerMessage $message) {
                    $this->bindMessage($message);
                }
            )
            ->build();

        $consumer->consume();
    }


    public function bindMessage(KafkaConsumerMessage $message): void
    {
        $messageBody = $message->getBody();

        Log::info(
            sprintf(self::KAFKA_MESSAGE_RECEIVED, self::KAFKA_TOPIC, json_encode($messageBody))
        );
        $this->output->info(
            sprintf(self::KAFKA_MESSAGE_RECEIVED, self::KAFKA_TOPIC, json_encode($messageBody))
        );

        $parentId    = $messageBody['candidate']['parent_id'] ?? null;
        $duplicateId = $messageBody['candidate']['duplicate_id'] ?? null;

        if ($parentId == null && $duplicateId == null) {
            // this should also implement a dead letter queue
            Log::error(
                sprintf(self::KAFKA_MESSAGE_ERROR, self::KAFKA_TOPIC, json_encode($messageBody))
            );
            $this->output->error(
                sprintf(self::KAFKA_MESSAGE_ERROR, self::KAFKA_TOPIC, json_encode($messageBody))
            );
            return;
        }

        $parent    = Fund::query()->find($parentId);
        $duplicate = Fund::query()->find($duplicateId);

        $duplicatedCandidate = new FundDuplicatesCandidate();
        $duplicatedCandidate->parent()->associate($parent);
        $duplicatedCandidate->duplicate()->associate($duplicate);
        $duplicatedCandidate->save();
    }
}
