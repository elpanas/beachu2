<?php
$dati = loginUtente($db,$content['username'],$content['password']);

if ($dati != null)
    inserisceSessione($db,$dati['idu']);  
else
    $dati['idu'] = 0;
   
$risposta = $dati;