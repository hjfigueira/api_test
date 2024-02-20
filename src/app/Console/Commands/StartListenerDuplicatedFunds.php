<?php

namespace App\Console\Commands;

use App\Console\Service\ListenToDuplicateFundsService;
use App\Models\FundDuplicatesCandidate;
use App\Models\Fund;
use App\Repositories\FundDuplicatesRepository;
use Carbon\Exceptions\Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Exceptions\KafkaConsumerException;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Contracts\KafkaConsumerMessage;

/**
 * Create the listener for kafka events
 */
class StartListenerDuplicatedFunds extends Command
{
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
    public function handle(ListenToDuplicateFundsService $listener): void
    {
        $listener->setOutput($this->output);
        $listener->bindListener();
    }
}
