<?
$callback = $content['callback_query']['data'];
$id_query = $content['callback_query']['id'];
$chatID = $content['callback_query']['message']['chat']['id']; 
$username = $content['callback_query']['message']['chat']['username'];
$id_preferito = str_replace('/p','',$callback,$count_p);    // elimina la parte extra che identifica il tipo di valore
$id_stabilimento = str_replace('/s','',$callback,$count_s); // elimina la parte extra che identifica il tipo di valore 
$url = API_URL . 'answerCallbackQuery'; // url del bot telegram
$data = array('callback_query_id' => $id_query,
              'text' => '');

switch(true) {	
	case $count_p > 0: // inserisce lo stabilimento nella lista dell'utente  
	if (!controllaPreferito($db,$id_preferito,$username))
	    $data['text'] = (inseriscePreferito($db,$username,$id_preferito)) ? 'Preferito aggiunto' : 'Errore'; 
	else
		$data['text'] = 'Preferito già presente';

	inviaMsg($data,$url,true); // invia il messaggio
	break;
		
	case $count_s > 0: // info dello stabilimento prescelto
	include 'includes/stabilimento.php';
	break;    
	
	default: // ha inserito la località
	include 'includes/localita.php';
	}