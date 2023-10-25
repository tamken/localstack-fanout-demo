<?php

require("vendor/autoload.php");

use App\SqsConsumer;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$options = [
    'profile' => $_ENV['AWS_PROFILE'],
    'region' => $_ENV['AWS_REGION'],
    'endpoint' => $_ENV['AWS_ENDPOINT_URL'],
];
$queue = $_ENV['AWS_QUEUE'];

try {
    $consumer = new SqsConsumer($options, $queue);
} catch (Exception $e) {
    $param = [
        "message" => $e->getMessage(),
        "queue" => $queue,
    ];
    echo sprintf("localstackが起動していないか、キューが存在していないみたいです.\n%s\n", json_encode($param));
    exit(1);
}

while(true) {
    $messages = $consumer->receive();
    if (!empty($messages)) {
        $consumer->consume($messages[0]);
    }
    sleep(1);
}
