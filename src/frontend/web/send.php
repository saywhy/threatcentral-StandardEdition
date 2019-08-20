<?php
	require_once __DIR__ . '/../../vendor/autoload.php';
	use PhpAmqpLib\Connection\AMQPStreamConnection;
	use PhpAmqpLib\Message\AMQPMessage;


	$connection = new AMQPStreamConnection('47.93.119.144', 5672, 'guest', 'guest');
	$channel = $connection->channel();



	$channel->queue_declare('gaolei', false, false, false, true);
	// $channel->close();


	$msg_json = [
		'name' => 'gaolei',
		'type' => 'handsome man',
		'height' => 172,
		'language' => '中文',
		'test' => '会乱码吗？',
	];

	$msg = new AMQPMessage(json_encode($msg_json));
	$channel->basic_publish($msg, '', 'gaolei');

	var_dump($msg_json);
// $queue
// $passive
// $durable
// $exclusive
// $auto_delete


// $nowait
// $arguments
// $ticket 
?>

