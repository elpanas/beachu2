<?php
$risposta = loginUtente($db,$content['username'],$content['password']);

if ($risposta != null)
    inserisceSessione($db,$risposta['Id']);  
else
    $risposta['Id'] = 0;