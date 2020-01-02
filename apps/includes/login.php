<?php
$risposta = loginUtente($db,$content['username'],$content['password']);

if ($risposta != null)
    {
    inserisceSessione($db,$risposta['Id']);  
    header('HTTP/1.0 202 Accepted');
    }
else
    {
    $risposta['Id'] = 0;
    header('HTTP/1.0 401 Unauthorized');
    }