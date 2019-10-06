<?php
// converte le coordinate nella località corrispondente
$elenco = estraePerCoordinate($db,$lat,$long); // estrae i disponibili dal db
$output = creaElenco($elenco,false); // crea un array con l'insieme degli stabilimenti forniti
$text = $output['testo'];

if ($output['inlinek'] != null) $encodedMarkup = $output['inlinek'];
