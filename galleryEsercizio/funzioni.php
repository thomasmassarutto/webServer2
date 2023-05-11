<?php 
// Includi il file di configurazione
require_once("config.php");
require_once ("./aws/vendor/autoload.php");
require_once ("./aws/vendor/c.php");

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Definisci una funzione che genera un array contenente i nomi dei file presenti nella directory
// es: $contenuto{[pippo.png], [pluto.png]}
function caricaDirectoryDaS3($bucketName) {
    // "importo" le key
    global $KEY, $SECRETKEY;
    //creo un array che conterra i nomi delle foto
    $contenuto= array();
    //API S3:
    // creo credenziali
    $credentials= new Aws\Credentials\Credentials ($KEY, $SECRETKEY); 
    // creo istanza s3
    $s3= new Aws\S3\S3Client([  'version' => 'latest',
                                'region' => 'eu-central-1', 
                                'credentials'=> $credentials]);

    // elenco di oggetti nel bucket, accedo a $s3 con -> per 
    // ricavare la lista degli oggetti al suo interno
    // $elenco: contine $oggetti
    $elenco=$s3->ListObjects(array('Bucket' => $bucketName));    
    // accedo a campo 'contents' degli elementi ($oggetti) presenti in $elenco
    // oggetti: elementi di $elenco
    $oggetti= $elenco->get('Contents');
    // per ogni membro di $oggetti lo rappresento temporaneamente con
    // $immagine e assegno il valore della chiave 'key'
    // all indice corrispondente di $contenuto
    foreach( $oggetti as $immagine ) {
        // aggiungo elemento ad array
        $contenuto[] = $immagine['Key'];
    } 

    // ritorno array dei nomi delle immagini
    return $contenuto;
} 

// genera un elemento HTML <a></a> contenente:
// link a visualizza.php?immagine=*indice_immagine*
// miniatura lato client dell'immagine
function generaImmagineDaS3($indiceImmagine, $nomeFile, $nomeBucket) {

    global $KEY, $SECRETKEY;
    $credentials= new Aws\Credentials\Credentials ($KEY, $SECRETKEY);
    $s3= new Aws\S3\S3Client([  
        'version' => 'latest',
        'region' => 'eu-central-1', 
        'credentials'=> $credentials
    ]);

    // genera l'url prefirmato per visualizzare l'immagine, valido 20 mins
    $urlImmagine = generaLinkFirmato($nomeFile, $nomeBucket);
    //ritorno l'elemento completo   
    return  "<a href=\"visualizza.php?immagine=$indiceImmagine\">" 
            . "<img src=\"$urlImmagine\"  width=\"80\" height=\"60\"/>" // thumbanil generato lato client
            . "</a>";
}
   
// genera il link firmato per accedere l'url per accedere all'immagoine nel bucket privato
// occio che scade dopo 20 minuti
// https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/s3-presigned-url.html
function generaLinkFirmato($file, $nomeBucket) {
    global $KEY, $SECRETKEY;
    $credentials = new Aws\Credentials\Credentials($KEY, $SECRETKEY);
    $s3 = new Aws\S3\S3Client([
        'version' => 'latest',
        'region' => 'eu-central-1', 
        'credentials' => $credentials
    ]);

    // Genera una URL firmata che scade dopo 20 minuti
    $cmd = $s3->getCommand('GetObject', [
            'Bucket' => $nomeBucket,
            'Key' => $file
    ]);

    $request = $s3->createPresignedRequest($cmd, '+20 minutes');
    $presignedUrl = (string)$request->getUri();
        
    return $presignedUrl;

}

// genera un pezzo di codice html che porta a 
// visualizza.php, *indiceimmagine*
function generaLinktestualeDaS3($indice_immagine, $testo = ""){

    return "<a href=\"visualizza.php?immagine=" 
    . $indice_immagine. "\">" 
    . $testo 
    . "</a>";
}

// inserisce un immagine su bucket
function inserisciImmagineSuS3($imageName , $imagePath){
    
    global $KEY, $SECRETKEY;
    $credentials= new Aws\Credentials\Credentials (
        $KEY, 
        $SECRETKEY); 

    $s3= new Aws\S3\S3Client([  'version' => 'latest',
                                'region' => 'eu-central-1', 
                                'credentials'=> $credentials]);
    // utilizzo putObject per inserire un oggetto nel bucket
    $result = $s3->putObject([
                              'Bucket' => 'tommygallerybucket',
                               'Key' => $imageName,
                               'SourceFile' => $imagePath,
                                ]);
    // controllo se l'oggetto è stato correttamente inserito
    $exists = $s3->doesObjectExist('tommygallerybucket', $imageName);
    
    if ($exists) {
        return true;
    } else {
        return false;
    }
}

// Definisci una funzione che controlla se il nome file $nomefile rientra nei formati ammessi
function controllaFormato($nomefile) {
    // Usa la variabile globale $formati_immagine per controllare se il nome file rientra nei formati ammessi
    global $formati_immagine;
    foreach($formati_immagine as $formato)
        if(strrpos($nomefile, $formato))
            return TRUE;

    // Se il nome file non rientra in nessun formato ammesso, restituisci FALSE
    return FALSE;
}

// Definisci una funzione che controlla se il tipo MIME $tipo rientra nei tipi di immagine ammessi
function controllaTipo($tipo) {
    // Usa la variabile globale $tipi_immagine per controllare se il tipo MIME rientra nei tipi di immagine ammessi
    global $tipi_immagine;
    foreach($tipi_immagine as $formato) 
        if(strpos($tipo, $formato) === 0)
            return TRUE;

    // Se il tipo MIME non rientra in nessun tipo di immagine ammesso, restituisci FALSE
    return FALSE;
}

?>
