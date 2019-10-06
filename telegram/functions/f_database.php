<?php
// controlla se lo stabilimento è nella lista preferiti dell'utente
function controllaPreferito($db,        // input: oggetto per comunicare col database
                            $ids,       // input: id dello stabilimento nel db
                            $user) {    // input: username telegram

    $esito = false; // output: indica se i parametri esistono nel db
    $query = "SELECT id FROM preferiti 
              WHERE idstab = $ids AND 
                    idutente = (SELECT id FROM utenti WHERE username = '$user')";
   
    if($result = $db->query($query))        
        if ($result->num_rows > 0)
            $esito = true;
 
    $result->free(); // libera la memoria       

    return $esito;
}

// restituisce una lista degli stabilimenti disponibili a meno di 3km
function estraePerCoordinate($db,          // input: oggetto per comunicare col database
                             $lat,
                             $long) {  // input: luogo dove cercare gli stabilimenti

    $elenco = null; // output: dati degli stabilimenti 
    $i = 0;

    $query = "SELECT * FROM stabilimenti 
              WHERE 6363 * SQRT( POW(RADIANS($lat) - RADIANS(latitudine),2) + POW(RADIANS($long) - RADIANS(longitudine),2) ) < 300 AND 
                    disponibili > 0
                    ORDER BY disponibili DESC";
	
    if($result = $db->query($query)) // effettua la query    
        if($result->num_rows > 0) // verifica che esistano record nel db	    		
	        while($row = $result->fetch_assoc())  // converte in un array associativo	    
		        $elenco[$i++] = array('latitudine' => $row['latitudine'],
                                      'longitudine' => $row['longitudine'],
                                      'localita' => $row['localita'],
                                      'provincia' => $row['provincia'],
				      	              'stabilimento' => $row['nome'],
				      	              'disponibili' => $row['disponibili'],
				      	              'id' => $row['id']);
    
    $result->free(); // libera la memoria
	
    return $elenco; // array 
}

// restituisce una lista degli stabilimenti disponibili in una data località
function estraePerLocalita($db,          // input: oggetto per comunicare col database
                           $localita) {  // input: luogo dove cercare gli stabilimenti

    $elenco = null; // output: dati degli stabilimenti 
    $i = 0;

    $query = "SELECT * FROM stabilimenti 
              WHERE localita LIKE '%$localita%' AND 
                    disponibili > 0
                    ORDER BY disponibili DESC";
	
    if($result = $db->query($query)) // effettua la query    
        if($result->num_rows > 0) // verifica che esistano record nel db	    		
	        while($row = $result->fetch_assoc())  // converte in un array associativo	    
		        $elenco[$i++] = array('latitudine' => $row['latitudine'],
                                      'longitudine' => $row['longitudine'],
                                      'localita' => $row['localita'],
                                      'provincia' => $row['provincia'],
				      	              'stabilimento' => $row['nome'],
				      	              'disponibili' => $row['disponibili'],
				      	              'id' => $row['id']);
    
    $result->free(); // libera la memoria
	
    return $elenco; // array 
}

// estrae i preferiti di un utente dal database
function estraePreferiti($db,       // input: oggetto per comunicare col database
                         $user){    // input: username telegram

    $elenco = null; // output: indica se i parametri esistono nel db
    $i = 0;    
    $user = $db->real_escape_string($user); // elimina caratteri extra dal parametro
    $query = "SELECT distinct s.nome as nome,
    			     s.id as id,
			         s.localita as localita,
                     s.provincia as provincia,
                     s.latitudine as latitudine,
                     s.longitudine as longitudine,
			         s.disponibili as disponibili
              FROM preferiti as p JOIN 
                   utenti as u JOIN
                   stabilimenti as s
              WHERE u.username = '$user' AND
                    p.idutente = u.id AND
                    p.idstab = s.id";

    if($result = $db->query($query)) // effettua la query        
        if($result->num_rows > 0) // verifica che esistano record nel db				
            while($row = $result->fetch_assoc())  // converte in un array associativo						
                $elenco[$i++] = array('latitudine' => $row['latitudine'],
                                      'longitudine' => $row['longitudine'],
                                      'stabilimento' => $row['nome'],
                                      'localita' => $row['localita'],
                                      'provincia' => $row['provincia'],
				                      'disponibili' => $row['disponibili'],
				                      'id' => $row['id']);

    $result->free(); // libera la memoria

    return $elenco; 
}

// restituisce la disponibilità di un singolo stabilimento
function estraeStab($db,    // input: oggetto database
                    $id) {  // input: id dello stabilimento

    $dati = null; // output: dati dello stabilimento
	
	$query = "SELECT * FROM stabilimenti WHERE id = $id";    
        
    if($result = $db->query($query)) // effettua la query        
        if($result->num_rows > 0) // verifica che esistano record nel db		 
            while($row = $result->fetch_assoc()) // converte in un array associativo
                {
                $dati = array('latitudine' => $row['latitudine'],
                              'longitudine' => $row['longitudine'],
                              'disponibili' => $row['disponibili'],
                              'provincia' => $row['provincia'],
			                  'localita' => $row['localita'],
			                  'nome' => $row['nome'],                              
                              'id' => $row['id']);
                
                $dati['indirizzo'] .= $row['localita'].",".$row['provincia'];
                }
    
        $result->free(); // libera la memoria
        
    return $dati;
}

// inserisce uno stabilimento tra i preferiti
function inseriscePreferito($db,    // input: oggetto per comunicare col database
                            $user,  // input: username telegram
                            $idp) { // input: id dello stabilimento preferito

    $user = $db->real_escape_string($user); 
    $db->query("INSERT INTO utenti SET username = '$user'");
    $esito = $db->query("INSERT INTO preferiti (idstab,idutente) 
                         VALUES ($idp,(SELECT id FROM utenti WHERE username = '$user'))");
	
    return $esito; // output: indica il buon/cattivo esito della query
}