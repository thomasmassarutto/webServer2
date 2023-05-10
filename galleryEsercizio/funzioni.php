<?php 
// Includi il file di configurazione
require_once("config.php");
require_once './aws/vendor/autoload.php';
require_once ("./aws/vendor/c.php");

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Definisci una funzione che genera un array contenente i nomi dei file presenti nella directory
// es: elements{[pippo.png], [pluto.png]}
function caricaDirectoryDaS3($bucketName) {
    // ""
    global $KEY, $SECRETKEY;
    //array elements
    $contenuto= array();
    //API S3:
    // creo credenziali
    $credentials= new Aws\Credentials\Credentials ($KEY, $SECRETKEY); 
    // creo istanza s3
    $s3= new Aws\S3\S3Client([  'version' => 'latest',
                                'region' => 'eu-central-1', 
                                'credentials'=> $credentials]);

    // elenco di oggetti nel bucket, accedo con ->
    // elenco: oggetto di tipo Aws\result
    $elenco=$s3->ListObjects(array('Bucket' => $bucketName));    
    // se elenco contiene oggetti
    // accedo a campo 'contents' di oggetto elenco
    // contents: array che contiene nomi di elementi
    $oggetti= $elenco->get('Contents');
    // per ogni membro di $oggetti lo rappresento temporaneamente con
    // $immagine e assegno il valore della chiave 'key'
    // all indice corrispondente di $contenuto
        foreach( $oggetti as $immagine ) {
            // aggiungo elemento ad array
            $contenuto[] = $immagine['Key'];
        } 

    // ritorno array di nomi
    return $contenuto;
    } 

// 
function generaLinkImmagineDaS3($indice_immagine, $file, $bucketName) {
    global $KEY, $SECRETKEY;
    $credentials= new Aws\Credentials\Credentials (
        $KEY, 
        $SECRETKEY); 
    $s3= new Aws\S3\S3Client([  'version' => 'latest',
                                'region' => 'eu-central-1', 
                                'credentials'=> $credentials]);
    
    // Genera l'URI S3 per l'immagine specificata
    $s3_uri = 's3://' . $bucketName . '/' . $file;
    
    // Recupera l'URL firmato per l'URI S3 dell'immagine
    $signed_url = $s3->createPresignedRequest(
        $s3->getCommand('GetObject', [
            'Bucket' => $bucketName,
            'Key' => $file,
        ]),
        '+30 second' // Durata del link firmato
    )->getUri()->__toString();
    
    echo 
    "<a href=\"visualizza.php?immagine=" 
    . $indice_immagine. "\">" 
    . "<img src=\"" .$signed_url. "\" 
            width=\"80\" height= \"60\"/>" //thumbanil
    . "</a>";
}


function generaImmagineDaS3($file, $bucketName) {
    global $KEY, $SECRETKEY;

    // Configura le credenziali per l'autenticazione
    $credentials= new Aws\Credentials\Credentials (
        $KEY, 
        $SECRETKEY
    ); 

    // Configura il client S3
    $s3= new Aws\S3\S3Client([
        'version' => 'latest',
        'region' => 'eu-central-1', 
        'credentials'=> $credentials
    ]);

    // Genera l'URI S3 per l'immagine specificata
    $s3_uri = 's3://' . $bucketName . '/' . $file;

    // Recupera l'URL firmato per l'URI S3 dell'immagine
    $signed_url = $s3->createPresignedRequest(
        $s3->getCommand('GetObject', [
            'Bucket' => $bucketName,
            'Key' => $file,
        ]),
        '+30 minutes' // Durata del link firmato
    )->getUri()->__toString();

    // Restituisci l'URL all'immagine con la firma temporanea
    return $signed_url;
}

// genera un pezzo di codice html che porta a 
// visualizza.php, *indiceimmagina*
function generaLinktestualeDaS3($indice_immagine, $testo = ""){

    return "<a href=\"visualizza.php?immagine=" 
    . $indice_immagine. "\">" 
    . $testo 
    . "</a>";
}

// inserisce un immagine su s3
function inserisciImmagineSuS3($imageName , $imagePath){
    
    global $KEY, $SECRETKEY;
    $credentials= new Aws\Credentials\Credentials (
        $KEY, 
        $SECRETKEY); 
    
    // Configura il client S3
    $s3= new Aws\S3\S3Client([  'version' => 'latest',
                                'region' => 'eu-central-1', 
                                'credentials'=> $credentials]);

    $result = $s3->putObject([
                              'Bucket' => 'tommygallerybucket',
                               'Key' => $imageName,
                               'SourceFile' => $imagePath,
                                ]);
                                return true;
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
