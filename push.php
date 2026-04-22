<?php
require __DIR__ . '/vendor/autoload.php';

$options = array(
    'cluster' => 'ap2',
    'useTLS' => true
);
$pusher = new Pusher\Pusher(
    'b2125d64edf5e1a092e2',
    '55824a6f034b1aac03a5',
    '1972669',
    $options
);

// Data to send
$data = ['message' => 'Hello from Core PHP!'];

// Trigger the event
$pusher->trigger('my-channel', 'my-event', $data);
