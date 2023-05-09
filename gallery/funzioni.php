<?php 
require_once("config.php");
// funzione che effettua il caricamento dei file, * presenti nella directory $dir, che rientrano* 
// nei formati ammessi

function caricaDirectory($dir) {

    $dh= opendir($dir) or die("Errore nell'apertura della directory ". $dir); //ottiene un puntatore alla cartella: ritorna T= handle F= F
    $contenuto = array(); // contenuto cartella

    while( ($file = readdir($dh)) != FALSE) // H 9:54

        if(!is_dir($file) && controllaFormato($file))// se non è cartella ed ha estensione giust
            $contenuto[] = $file; // aggiungo un nuovo elemento a $contenuto 

    closedir($dh); // chiudo handle
    return $contenuto; //restituisco array contenuto
}

function generaLinkImmagine($indice_immagine, $file) {

    return "<a href=\"visualizza.php?immagine=" 
    . $indice_immagine. "\">" 
    . "<img src=\"" .DIR_IMMAGINI. "/" 
    . $file . "\" width=\"80\" height= \"60\"/>"
    . "</a>"; // thumbanil generato lato client
}

// funzione che genera un link verso l'immagine con indice $indice_immaginee testo specificato 
// in $testo
function generaLinkTestuale($indice_immagine, $testo = "") {

    return "<a href=\"visualizza.php?immagine=" 
    . $indice_immagine. "\">" 
    . $testo 
    . "</a>";
}

function controllaFormato($nomefile) {

    global $formati_immagine; // presente in config: codifica estensione corretta
    foreach($formati_immagine as $formato) // scorre un array 
        if(strrpos($nomefile, $formato))// controllo nome di file e formato. // strrpos: posizione di sottostringa all'interno di stringa
            return TRUE;

        return FALSE; // nessun formato trovato
    }
    
function controllaTipo($tipo) {// controlla tipo mime

    global $tipi_immagine;
    foreach($tipi_immagine as $formato) 
    if(strpos($tipo, $formato) === 0)// 0: senso numerico 
        return TRUE;

    return FALSE; // nessun tipo trovato
    }
    
?>
