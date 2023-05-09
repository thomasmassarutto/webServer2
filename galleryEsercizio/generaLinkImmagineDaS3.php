<?php
require 'aws/vendor/autoload.php';
require_once ("./aws/vendor/c.php");

use Aws\S3\S3Client;


function generaLinkImmagineDaS3($indice_immagine, $file, $bucketName) {
    global $KEY, $SECRETKEY;
    $credentials= new Aws\Credentials\Credentials (
        $KEY, 
        $SECRETKEY); 
    
    // Configura il client S3
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
            width=\"80\" height= \"60\"/>"
    . "</a>";
}

generaLinkImmagineDaS3(0, 'C1.png', 'tommygallerybucket');

?>