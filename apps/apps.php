<?php
require 'functions/f_database.php';

switch($content['azione']) {
    
    // gestore
    case 'login':
    include 'includes/login.php';    
    break;

    case 'listastabilimenti':
    $risposta = estraeElenco($db,$content['idu']);
    break;

    case 'eliminastabilimento':
    $risposta = eliminaStabilimento($db,$content['ids']);
    break;

    case 'inseriscistabilimento':
    $risposta = inserisceStabilimento($db,$content);
    break;        

    case 'aggiornaombrelloni':
    $risposta = aggiornaOmbrelloni($db,$content['ids'],$content['ombrelloni']);
    break;

    case 'estraestabilimento':
    $risposta = estraeStabilimento($db,$content['ids']);
    break;

    case 'inserisciutente':
    $risposta = inserisceUtente($db,$content);    
    break;

    case 'aggiornautente':
    $risposta = aggiornaUtente($db,$content);
    break;   

    // utente
    case 'cercaposizione':
    case 'cercalocalita':
    $risposta = cercaStabilimenti($db,$content);
    break;
}

header('Content-Type:application/json');
echo json_encode($risposta);
