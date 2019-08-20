<?php
	require_once __DIR__ . '/../../vendor/autoload.php';
	use PhpAmqpLib\Connection\AMQPStreamConnection;
	use PhpAmqpLib\Message\AMQPMessage;


	$connection = new AMQPStreamConnection('47.93.119.144', 5672, 'guest', 'guest');
	$channel = $connection->channel();





	$channel->queue_declare('gaolei', false, false, false, true);

	echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

	$callback = function($msg) {
		var_dump($msg->body);
	  // var_dump(json_decode($msg->body,true));
	};

	$channel->basic_consume('gaolei', '', false, true, false, false, $callback);

	while(count($channel->callbacks)) {
	    $channel->wait();
	}

	$channel->close();
	$connection->close();
?>