<?
// memorizza i valori che interessano
$chatID = $content["message"]["chat"]["id"];
$username = $content["message"]["chat"]["username"];
$longitudine = isset($content["message"]["location"]["longitude"]) ? $content["message"]["location"]["longitude"] : '';
$latitudine = isset($content["message"]["location"]["latitude"]) ? $content["message"]["location"]["latitude"] : '';
$messaggio = isset($content["message"]["text"]) ? $content["message"]["text"] : '';
$url = API_URL . 'sendMessage'; // url del bot telegram
$dati_utente = estraeUtente($db,$username);
             	
switch(true) {
    case $messaggio == '/start':
	include 'includes/istruzioni.php';
	break;
	
	case ($longitudine != null && $latitudine != null):
	include 'includes/geoloc.php';
	break;	       

    case $messaggio == '/preferiti': 
    include 'includes/preferiti.php';
    break;

    default: // ha inserito la località  
    include 'includes/localita.php'; 
    }  