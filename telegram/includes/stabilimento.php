<?php 
inviaMsg($data,$url,true); // invia la risposta al callback

$dati_stab = estraeStab($db,$id_stabilimento); // estrae i dati dello stabilimento
$coordinate['latitudine'] = $dati_stab['latitudine'];
$coordinate['longitudine'] = $dati_stab['longitudine'];

// controlla se è già presente tra i preferiti e se l'utente è loggato
if (!controllaPreferito($db,$id_stabilimento,$username))
    {
        $inline_keyboard = array('inline_keyboard' => array(array(array('text' => 'Aggiungi ai preferiti',
                                                                                  'callback_data' => '/p'.$id_stabilimento))));
		
        $encodedMarkup = json_encode($inline_keyboard); // converte l'array in formato json
    }

$url = API_URL . 'sendLocation'; // url del bot telegram