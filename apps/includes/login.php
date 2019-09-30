<?php
$dati = loginUtente($db,$content['username'],$content['password']);


if ($dati != null)
    inserisceSessione($db,$dati['Id']);  
else
    $dati['Id'] = 0;
   
$risposta = $dati;