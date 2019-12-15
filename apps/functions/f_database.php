<?php
// aggiorna gli ombrelloni
function aggiornaOmbrelloni($db,            // input: oggetto per comunicare col database
                            $ids,            // input: id stabilimento
                            $ombrelloni) {  // input: ombrelloni disponibili   
      
    $query = "UPDATE stabilimenti
              SET disponibili = $ombrelloni
              WHERE id = $ids";

    if ($db->query($query))
        return 1;
    else
        return 0;
}

function aggiornaUtente($db,
                        $content) { //input: dati json

    $output = 0;

    $query = "UPDATE utenti
              SET nome = ".$content['nome'].",
                  cognome = ".$content['cognome'].",                  
                  telefono = ".$content['telefono'].",
                  email = ".$content['mail']."
                WHERE id = ".$content['id'];

    if ($db->query($query)) $output = 1;

    return $output;
}

// Estrae gli stabilimenti distanti al max 3 Km
function cercaStabilimenti($db,
                           $content) {

    if ($content["azione"] == "cercalocalita")
    {
        $localita = $content["localita"];
        $provincia = $content["provincia"];
        $where = "localita LIKE '%$localita%' AND provincia LIKE '%$provincia%'";
    }
    else
    {
        $lat = $content['latitudine'];
        $long = $content['longitudine'];

        $latRad = $lat * pi() / 180;
        $longRad = $long * pi() / 180;
        // $latCos = cos($latRad);
        // $distanza = pow((5/110.25),2);
        $where = "(6363 * SQRT( POW($latRad - RADIANS(latitudine),2) + POW($longRad - RADIANS(longitudine),2) )) < 3";

        // $where = "(POW(latitudine-$lat,2) + POW( (longitudine-$long) * $latCos ,2)) < $distanza";
    }
        
    $elenco = null; // output: dati degli stabilimenti 
    $i = 0;
    $query = "SELECT * FROM stabilimenti 
              WHERE $where
              ORDER BY localita, nome";
	
    if($result = $db->query($query)) // effettua la query   
    { 
        if($result->num_rows > 0) // verifica che esistano record nel db	    		
	        while($row = $result->fetch_assoc())  // converte in un array associativo	    
		        $elenco[$i++] = array('Id' => $row['id'],
                                      'Nome' => $row['nome'],                                      
                                      'Localita' => $row['localita'],  
                                      'Provincia' => $row['provincia'],   
                                      'Ombrelloni' => $row['ombrelloni'],                                                  
                                      'Disponibili' => $row['disponibili'],
                                      'Latitudine' => $row['latitudine'],
                                      'Longitudine' => $row['longitudine'],
                                      'Telefono' => $row['telefono']);
    
        $result->free(); // libera la memoria
    }
	
    return $elenco; // array 
}

// elimina lo stabilimento
function eliminaStabilimento($db,          // input: oggetto per comunicare col database
                             $id) {   // input: dati json   
      
    $query = "DELETE
              FROM stabilimenti
              WHERE id = $id";

    if ($db->query($query))
        return 1;
    else
        return 0;
}

// restituisce una lista degli stabilimenti di un dato utente
function estraeElenco($db,          // input: oggetto per comunicare col database
                      $id) {        // input: id gestore

    $elenco = null; // output: dati degli stabilimenti 
    $i = 0;
    $query = "SELECT * FROM stabilimenti 
              WHERE idu = $id                  
              ORDER BY localita, nome";
	
    if($result = $db->query($query)) // effettua la query    
        if($result->num_rows > 0) // verifica che esistano record nel db	    		
	        while($row = $result->fetch_assoc())  // converte in un array associativo	    
		        $elenco[$i++] = array('Id' => $row['id'],
                                      'Nome' => $row['nome'],                                      
                                      'Localita' => $row['localita'],  
                                      'Provincia' => $row['provincia'],
                                      'Ombrelloni' => $row['ombrelloni'],  	              
                                      'Disponibili' => $row['disponibili'],
                                      'Latitudine' => $row['latitudine'],
                                      'Longitudine' => $row['longitudine'],
                                      'Telefono' => $row['telefono']);
    
    $result->free(); // libera la memoria
	
    return $elenco; // array 
}

// restituisce una lista degli stabilimenti di un dato utente
function estraeStabilimento($db,          // input: oggetto per comunicare col database
                            $ids) {        // input: id gestore

    $elenco = null; // output: dati degli stabilimenti 
    $i = 0;
    $query = "SELECT * FROM stabilimenti 
              WHERE id = $ids";
	
    if($result = $db->query($query)) // effettua la query    
        if($result->num_rows > 0) // verifica che esistano record nel db	    		
	        while($row = $result->fetch_assoc())  // converte in un array associativo	    
		        $elenco = array('Id' => $row['id'],
                                'Nome' => $row['nome'],                                      
                                'Localita' => $row['localita'],  
                                'Provincia' => $row['provincia'],
                                'Ombrelloni' => $row['ombrelloni'],  	              
                                'Disponibili' => $row['disponibili'],
                                'Latitudine' => $row['latitudine'],
                                'Longitudine' => $row['longitudine'],
                                'Telefono' => $row['telefono']);
    
    $result->free(); // libera la memoria
	
    return $elenco; // array 
}

function inserisciLibero($db,
                         $content) {

    $nome = $content['nome'];
    $cognome = $content['cognome'];
    $numero = $content['numero'];
    $inizio = $content['inizio'];
    $fine = $content['fine'];

    $query = "INSERT INTO temporanei(nome,
                                     cognome,
                                     numero,
                                     inizio,
                                     fine)
              VALUES('$nome',
                     '$cognome',
                      $numero,
                     STR_TO_DATE('$inizio','%d %m %Y'),
                     STR_TO_DATE('$fine','%d %m %Y'))
              ON DUPLICATE KEY UPDATE numero = $numero,
                                      inizio = STR_TO_DATE('$inizio','%d %m %Y'),
                                      fine = STR_TO_DATE('$fine','%d %m %Y')";

    if ($db->query($query))
        return 1;
    else
        return 0;
}

function inserisceSessione($db,$idu) {
    $db->query("UPDATE utenti SET sessione = NOW() WHERE id = $idu");
}

// inserisce un nuovo utente
function inserisceStabilimento($db,          // input: oggetto per comunicare col database
                               $content) {   // input: dati json   
    $output = 0;

    $query = "INSERT
              INTO stabilimenti (nome,                                 
                                 localita, 
                                 provincia,                              
                                 latitudine,
                                 longitudine,
                                 idu,
                                 ombrelloni,
                                 disponibili)
              VALUES ('".$content['nome']."',                                                          
                      '".$content['localita']."', 
                      '".$content['provincia']."',
                      ".$content['latitudine'].", 
                      ".$content['longitudine'].",                      
                      ".$content['idu'].",  
                      ".$content['ombrelloni'].",
                      ".$content['ombrelloni'].")
              ON DUPLICATE KEY UPDATE ombrelloni = ".$content['ombrelloni'];

    if ($db->query($query)) $output = 1;
    
    return $output;
}

function inserisceUtente($db,
                         $content) { //input: dati json

    $output = 0;

    $nome = $content['nome'];
    $cognome = $content['cognome'];
    $username = $content['username'];
    $password = $content['password'];  
    $telefono = $content['telefono'];
    $email = $content['email'];

    $query = "INSERT INTO utenti (nome,
                                  cognome,
                                  username,
                                  password,
                                  telefono,
                                  email)
              VALUES('$nome',
                     '$cognome',
                     '$username',
                     '$password',
                     '$telefono',
                     '$email')";

    if ($db->query($query)) $output = 1;

    return $output;
}

// controlla se l'utente è registrato ma deve inserire la password
function loginUtente($db,      // input: oggetto per comunicare col database
                     $user,    // input: username telegram  
                     $psw) {                    

    $dati = null; // output: array associativo con i dati
    
    $query = "SELECT id                                        
              FROM utenti
              WHERE username = '$user' AND password = '$psw'";
   
    if($result = $db->query($query))        
        if ($result->num_rows > 0)
            while($row = $result->fetch_assoc())
                $dati = array('Id' => $row['id']);   
 
    $result->free(); // libera la memoria

    return $dati; 
}