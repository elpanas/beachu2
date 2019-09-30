<?php
require 'functions/f_database.php';

switch($content['azione']) {
    case 'sessione':
    include 'includes/sessione.php';
    break;

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

    case 'inserisciutente':
    $risposta = inserisceUtente($db,$content);
    break;

    case 'aggiornautente':
    $risposta = aggiornaUtente($db,$content);
    break;    
}

echo json_encode($risposta);
