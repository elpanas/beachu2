<?php
  require __DIR__ . '/vendor/autoload.php';

  $options = array(
    'cluster' => "getenv('PUSHER_CLUSTER')",
    'useTLS' => true
  );
  $pusher = new Pusher\Pusher(
    "getenv('PUSHER_KEY')",
    "getenv('PUSHER_SECRET')",
    "getenv('PUSHER_APP_ID')",
    $options
  );

  $data['message'] = 'hello world';
  $pusher->trigger('my-channel', 'my-event', $data);
?>
