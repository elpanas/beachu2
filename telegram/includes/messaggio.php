<?
// memorizza i valori che interessano
$chatID = $content["message"]["chat"]["id"];
$username = $content["message"]["chat"]["username"];
$longitudine = isset($content["message"]["location"]["longitude"]) ? $content["message"]["location"]["longitude"] : '';
$latitudine = isset($content["message"]["location"]["latitude"]) ? $content["message"]["location"]["latitude"] : '';
$messaggio = isset($content["message"]["text"]) ? $content["message"]["text"] : '';
$url = API_URL . 'sendMessage'; // url del bot telegram
aggiornaSessione($db,$username);
$dati_utente = estraeUtente($db,$username);

if($dati_utente != NULL) // estrae i dati dell'utente
    {
    $loggato = $dati_utente['loggato'];
    $flag_psw = $dati_utente['attesa_psw'];

    if ($flag_psw) // se attende la password    
        {       
        $output = gestioneLogin($db,$username,$dati_utente,$messaggio); 
        $loggato = $output['loggato'];
        $text = $output['testo'];
        }
    } 
else
    $flag_psw = $loggato = false;                       
    	
switch(true) {
    case $messaggio == '/start':
	include 'includes/istruzioni.php';
	break;
	
	case ($longitudine != NULL && $latitudine != NULL):
	include 'includes/geoloc.php';
	break;	       

    case $messaggio == '/preferiti':
    if (!$loggato && !$flag_psw) 
        {
        $output = gestioneLogin($db,$username,$dati_utente,$messaggio);
        $text = $output['testo'];
        }
    else
        include 'includes/preferiti.php';
    break;
            
    case $messaggio == '/reset':
    if (!$loggato && !$flag_psw) 
        {
        $output = gestioneLogin($db,$username,$dati_utente,$messaggio);
        $text = $output['testo'];
        }
    else
        include 'includes/resetpsw.php';
    break;

    case !$flag_psw: // ha inserito la località  
    include 'includes/localita.php'; 
    }  