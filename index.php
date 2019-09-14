<?php
/*
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
  $pusher->trigger('my-channel', 'my-event', $data); */

$username = "Luca";
$password = "123456";

$inputhttp = file_get_contents("php://input"); // legge le info in input
$content = json_decode($inputhttp,true); // converte il formato json in array associativo

if ($content["username"] == $username && $content["password"] == $password)
  echo "OK";
else
  echo "NO";
?>
