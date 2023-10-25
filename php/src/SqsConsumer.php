<?php

namespace App;

require("vendor/autoload.php");

use Aws\Sqs\SqsClient;

class SqsConsumer {

    private $client = null;
    private $queue_url = null;

    public function __construct(array $options, string $queue)
    {
        $this->client = new SqsClient($options);
        $this->queue_url = $this->client->getQueueUrl([
            'QueueName' => $queue,
        ])['QueueUrl'];
        echo "+++ boot php consumer.\n\n";
    }

    public function receive(): ?array
    {
        $hoge = [
            'MaxNumberOfMessages' => 1,
            'MessageAttributeNames' => ['All'],
            'QueueUrl' => $this->queue_url,
        ];
        return $this->client->receiveMessage($hoge)->get('Messages');
    }

    public function consume(array $messages): void
    {
        echo sprintf("php has received sqs message. : %s\n", $messages['Body']);
        $this->client->deleteMessage([
            'QueueUrl' => $this->queue_url,
            'ReceiptHandle' => $messages['ReceiptHandle'],
        ]);
    }
}
