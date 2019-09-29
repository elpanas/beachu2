<?php
$dati = loginUtente($db,$content['username'],$content['password']);

if ($dati != null)
    inserisceSessione($dati['idu']);  
else
    $dati['idu'] = 0;
   
$risposta = $dati;