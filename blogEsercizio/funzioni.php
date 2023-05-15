<?php

function registra($titolo, $testo) {

    global $BLOGFILE;
    $contenuto = file($BLOGFILE); # legge contenuto file e lo deposita riga per riga. Non è un modo intelligente per gestire i files 
    $penultimo = explode("#", $contenuto[0]);# separatore, stringa. Il vettore viene spezzato li del separatore
    $ultimo = $penultimo[0]+1; # aggiorna ultimo 
    $fp = fopen($BLOGFILE, "w"); 
    $titolo = rendiConforme($titolo); # rendiconforme: rimuove cancelletti e a capo
    $testo = rendiConforme($testo);
    $post = $ultimo ."#".date("Y-m-d, G:i") . "#". $titolo . "#" . $testo . "\n";# costruzione del post: stringa vhe descrive il post
    fwrite($fp, $post); # aprire file
    if(count($contenuto) > 0)  
        foreach($contenuto as $post) fwrite($fp, $post);# riscrive riga per riga il contenuto
    fclose($fp); // chiudo il file
    }

    # rimuove caratteri che potrebbero dare fastidio alla formattazione
    # funzione non ottimizzata
function rendiConforme($testo) {

        $testo = nl2br(htmlentities(stripslashes($testo)));# sostituisce caratteri strani con il corrispettivo html. Non funziona con tutto 

        $testo = str_replace("\r\n", "", $testo); # dal $testo sostituisco "\r\n" con ""
        // sistemi windows
        $testo = str_replace("\n", "", $testo); 
        // sistemi unix
        $testo = str_replace("\r", "", $testo); 
        // sistemi MacOS
        $testo=str_replace("#","&hash;",$testo);
        return$testo;
    }

function leggi($da, $quanti = NULL) {

    global $BLOGFILE;
    $risultato = array(); #
    $contenuto = file($BLOGFILE); // leggo il contenuto

    if(is_null($quanti))
        $quanti = count($contenuto);

    for ($i = $da; ($i - $da < $quanti) && ($i <= count($contenuto)); $i++) {# 
        // estraggo un post dal file e lo aggiungo all'array 
        $risultato; 
        $post = explode("#", $contenuto[$i -1]);
        $risultato[] = $post;
    }
    
    return $risultato;
    }

function numeroPost() {

    global $BLOGFILE;
    $blog = file($BLOGFILE);// restituisco il numero di righe del file
    return count($blog); # conta le righe del file, carica tutto il file, non ottimizzato
    }
    
function utenteValido($utente, $password) {

    global $UTENTE, $PASSWORD;
    return($utente == $UTENTE && $password == $PASSWORD);
    }

?>