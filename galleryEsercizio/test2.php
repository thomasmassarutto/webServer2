<?php
require 'aws/vendor/autoload.php';
require_once ("./aws/vendor/c.php");


use Aws\S3\S3Client;
displayImageFromS3(1,1);

function displayImageFromS3($bucketName, $image){
global $KEY, $SECRETKEY;

$bucket = 'tommygallerybucket';
$key = 'craiyon_095919_deep_space_with_galaxies__stars__planets_or_solar_systems_in_the_background.png'; // Il percorso del file nel bucket

$credentials= new Aws\Credentials\Credentials (

    $KEY, 
    $SECRETKEY); 

// Configura il client S3
$s3= new Aws\S3\S3Client([  'version' => 'latest',
                            'region' => 'eu-central-1', 
                            'credentials'=> $credentials]);

// Recupera il contenuto del file dal bucket S3
$result = $s3->getObject([
    'Bucket' => $bucket,
    'Key' => $key,
]);

// Imposta l'intestazione Content-Type per indicare che il contenuto è un'immagine
header('Content-Type: image/jpeg'); // Sostituisci "image/jpeg" con il tipo MIME corretto dell'immagine

// Stampa i byte dell'immagine come risposta HTTP
echo $result['Body'];
}
?>