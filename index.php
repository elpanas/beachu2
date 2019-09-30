<?php 
require 'config.php';
require 'db.php';

$inputhttp = file_get_contents("php://input"); // legge le info in input
$content = json_decode($inputhttp,true); // converte il formato json in array associativo

switch(true) {
    case (isset($content['message']) || isset($content['callback_query'])): // telegram
    include 'telegram/telegram.php';
    break;

    case (isset($content['azione'])): // app
    include 'apps/apps.php';
    break;

    /* case 'web': 
    break; */

    default:
    echo $content['nome'];
    break;   
}

// chiude la connessione al database
$db->close();