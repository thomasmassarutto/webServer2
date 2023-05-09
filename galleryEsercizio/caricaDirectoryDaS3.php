<?php
require './aws/vendor/autoload.php';
require_once ("./aws/vendor/c.php");
use Aws\S3\S3Client;

caricaDirectoryDaS3('tommygallerybucket');

// Definisci una funzione che genera un array contenente i file presenti nella directory
function caricaDirectoryDaS3($bucketName) {


    global $KEY, $SECRETKEY;
    
    //preliminari 

    //array elements
    $elements= Array();
    // credenziali
    $credentials= new Aws\Credentials\Credentials ($KEY, $SECRETKEY); 
    
    $s3= new Aws\S3\S3Client([  'version' => 'latest',
                                'region' => 'eu-central-1', 
                                'credentials'=> $credentials]);
    
    $elenco=$s3->ListObjects(array('Bucket' => $bucketName));    
    if($elenco->get("Contents")){
        // cerco elementi
        foreach($elenco->get("Contents") as $object) {
            // aggiungo elemento ad array
            $elements[] = $object['Key'];
        }
    }
    
     echo $elements[0];
     echo $elements[1];
     echo $elements[2];

    return $elements;
    } 
?>