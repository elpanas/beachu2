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

// controlla se l'utente è loggato ed elimina le sessioni scadute
function aggiornaSessione($db,       // input: oggetto per comunicare col database
                          $idu){    // input: id della chat
    
    $db->query("UPDATE utenti
                SET sessione = 0
                WHERE TIMEDIFF(NOW(),sessione) > '24:00:00' AND
                      id = $idu");
}

function aggiornaUtente($db,
                        $content) { //input: dati json
    $db->query("UPDATE utenti
                SET nome = ".$content['nome'].",
                    cognome = ".$content['cognome'].",
                    username = ".$content['username'].",
                    password = ".$content['password'].",
                    telefono = ".$content['telefono'].",
                    mail = ".$content['mail']."
                WHERE id = ".$content['id']);
}

// controlla se l'utente è registrato ma deve inserire la password
function controllaSessione($db,      // input: oggetto per comunicare col database                      
                           $idu) {                    

    $dati = 0; // output: array associativo con i dati    
    $query = "SELECT IF(TIMEDIFF(NOW(),sessione) > '24:00:00',0,1) as loggato
              FROM utenti
              WHERE id = $idu";
   
    if($result = $db->query($query))        
        if ($result->num_rows > 0)
            while($row = $result->fetch_assoc())
                $dati = $row['loggato'];
 
    $result->free(); // libera la memoria

    return $dati; 
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

    $elenco = 0; // output: dati degli stabilimenti 
    $i = 0;
    $query = "SELECT * FROM stabilimenti 
              WHERE idu = $id                   
                    ORDER BY localita, nome";
	
    if($result = $db->query($query)) // effettua la query    
        if($result->num_rows > 0) // verifica che esistano record nel db	    		
	        while($row = $result->fetch_assoc())  // converte in un array associativo	    
		        $elenco[$i++] = array('id' => $row['id'],
                                      'nome' => $row['nome'],
                                      'indirizzo' => $row['indirizzo'],
                                      'localita' => $row['localita'], 
                                      'ombrelloni' => $row['ombrelloni'],  	              
                                      'disponibili' => $row['disponibili'],
                                      'lat' => $row['latitudine'],
                                      'long' => $row['longitudine']);
    
    $result->free(); // libera la memoria
	
    return $elenco; // array 
}

function inserisceSessione($db,$idu) {
    $db->query("UPDATE utenti SET sessione = NOW() WHERE id = $idu");
}

// inserisce un nuovo utente
function inserisceStabilimento($db,          // input: oggetto per comunicare col database
                               $content) {   // input: dati json   

    $lat = (isset($content['latitudine'])) ? $content['latitudine'] : null;
    $long = (isset($content['longitudine'])) ? $content['longitudine'] : null;     

    $indirizzo = $content['strada'].",".
    
    $query = "INSERT
              INTO stabilimenti (nome,
                                 indirizzo,
                                 localita,
                                 latitudine,
                                 longitudine,
                                 idu,
                                 ombrelloni,
                                 disponibili)
              VALUES (".$content['nome'].",   
                      ".$content['indirizzo'].",                                                                
                      ".$content['localita'].",                      
                      $lat,
                      $long,
                      ".$content['idu'].",  
                      ".$content['ombrelloni'].",
                      ".$content['ombrelloni'].")";

    if ($db->query($query))
        return 1;
    else
        return 0;
}

function inserisceUtente($db,
                         $content) { //input: dati json

    $query = "INSERT INTO utenti (nome,
                                  cognome,
                                  username,
                                  password,
                                  telefono,
                                  mail)
              VALUES(".$content['nome'].",
                     ".$content['cognome'].",
                     ".$content['username'].",
                     ".$content['password'].",
                     ".$content['telefono'].",
                     ".$content['email'].")";

    if ($db->query($query))
        return 1;
    else
        return 0;
}


// controlla se l'utente è registrato ma deve inserire la password
function loginUtente($db,      // input: oggetto per comunicare col database
                     $user,    // input: username telegram  
                     $psw) {                    

    $dati = null; // output: array associativo con i dati
    $user = $db->real_escape_string($user); // elimina caratteri extra dal parametro
    $psw = $db->real_escape_string($psw);   // elimina caratteri extra dal parametro
    $query = "SELECT id,
                     telefono,
                     email                     
              FROM utenti
              WHERE username = '$user' AND password = '$psw'";
   
    if($result = $db->query($query))        
        if ($result->num_rows > 0)
            while($row = $result->fetch_assoc())
                $dati = array('idu' => $row['id'],
                              'tel' => $row['telefono'],
                              'email' => $row['email']);   
 
    $result->free(); // libera la memoria

    return $dati; 
}