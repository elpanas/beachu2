<?php
require 'includes/config.php';
require 'functions/f_database.php';
require 'functions/f_messaggio.php';
require 'functions/f_gestionelogin.php';
require 'functions/f_mapbox.php';

$encodedMarkup = creaMenuKeyboard(); // inizializza la variabile per i menu
$coordinate = $text = NULL;

if (isset($content['message'])) // è stato ricevuto un messaggio normale        
    include 'includes/messaggio.php';  
elseif(isset($content['callback_query'])) // è stato ricevuto un messaggio proveniente da un bottone inline        
    include 'includes/callback.php'; 

$data = creaMsg($chatID,$text,$encodedMarkup,$coordinate);	// compone il messaggio
inviaMsg($data,$url,true);  // invia il messaggio    