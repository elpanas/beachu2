<?php
function gestioneLogin($db,            // input: oggetto database
                       $username,      // input: username telegram
                       $dati_utente,   // input: dati dell'utente
                       $msg) {         // input: messaggio ricevuto

    $output = array('loggato' => false, // output: flag loggato e testo del messaggio da inviare
                    'testo' => '');

    if ($dati_utente == NULL) // l'utente non � registrato
        { 
        inserisceUtente($db,$username);  // inserisce l'utente nel db     
        $output['testo'] = 'Hai attivato la funzione preferiti. Il prossimo messaggio crea una password';
        }
    elseif ($dati_utente['password'] == NULL) // l'utente c'� ma non la password
        {
        if (strpos($msg,'/') !== false) // l'utente ha inserito una password inacettabile "/"
            $output['testo'] = 'Non sei ancora registrato/a
                     Inserisci una password priva dello slash iniziale "/"';
        else
            {
            inseriscePassword($db,$dati_utente['idu'],$messaggio); // inserisce la psw nel db            
            $output['testo'] = 'Registrazione completata!';
            $output['loggato'] = true;
            }
        }
    elseif (!$dati_utente['attesa_psw'])
            {
            cambiaFlagAttesa($db,$dati_utente['idu']); // imposta il flag a 1
            $output['testo'] = 'Inserire la password'; // l'utente � registrato ma non loggato
            }
    elseif ($dati_utente['password'] != hash('sha1',$messaggio)) // la psw c'�, � un login ma la psw � errata
            $output['testo'] = 'Password errata';
    else       
        { 
        inserisceSessione($db,$dati_utente['idu']);  // l'utente � registrato e la psw � corretta, quindi crea la sessione
        cambiaFlagAttesa($db,$dati_utente['idu']); // imposta il flag a 0
        $output['testo'] = 'Login effettuato!';
        $output['loggato'] = true;
        }

    return $output;
    }