<?php

namespace App\Console\Service;

use App\Models\Fund;
use App\Models\FundDuplicatesCandidate;
use App\Repositories\FundDuplicatesRepository;
use App\Repositories\FundRepository;
use Carbon\Exceptions\Exception;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Exceptions\KafkaConsumerException;
use Junges\Kafka\Facades\Kafka;

class ListenToDuplicateFundsService
{
    protected const string KAFKA_MESSAGE_RECEIVED  = 'new message received for topic %s : %s';
    protected const string KAFKA_MESSAGE_ERROR     = 'error while processing message from topic %s : %s';
    protected const string KAFKA_MESSAGE_LISTENING = 'listening for kafka messages for topic %s';
    protected const string KAFKA_MESSAGE_LISTENING_ERROR =
        'error while while creating consumer, topic %s might not have been created yet';

    protected const string KAFKA_TOPIC = 'duplicate_fund_warning';

    protected OutputStyle $output;

    public function __construct(
        protected FundDuplicatesRepository $fdRpo,
        protected FundRepository $fund
    )
    {
    }

    public function setOutput(OutputStyle &$output): void
    {
        $this->output = $output;
    }

    public function bindListener(): void
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

        try {
            $consumer->consume();
        } catch (Exception | KafkaConsumerException $e) {
            $this->output->info(sprintf(self::KAFKA_MESSAGE_LISTENING_ERROR, self::KAFKA_TOPIC));
            Log::info(sprintf(self::KAFKA_MESSAGE_LISTENING_ERROR, self::KAFKA_TOPIC));
        }
    }

    protected function bindMessage(KafkaConsumerMessage $message): void
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

        $parent    = $this->fund->findOneById($parentId);
        $duplicate = $this->fund->findOneById($duplicateId);

        if (!$this->fdRpo->hasInverseRelation($parent, $duplicate)) {
            $this->fdRpo->insertPossibleDuplicate($parent, $duplicate);
        }
    }
}
