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
/*
if ($content["username"] == $username && $content["password"] == $password)
{
  $risposta["id"] = 1;
  $risposta["username"] = $username;
  echo json_encode($risposta);
}*/

switch($content["azione"])
  {
  case "listastabilimenti":
    $risposta[0]["id"] = 1;
    $risposta[0]["nome"] = "Lido Nettuno";
    $risposta[0]["indirizzo"] = "Lungomare manfredonia";
    $risposta[0]["localita"] = "Siponto";
    $risposta[0]["ombrelloni"] = 186;  
    echo json_encode($risposta);
    break; 
  }
?>
