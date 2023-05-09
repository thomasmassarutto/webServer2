<?php
require 'aws/vendor/autoload.php';
require_once ("./aws/vendor/c.php");

use Aws\S3\S3Client;


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
    

    }

inserisciImmagineSuS3( 'pallaarancio.png', '/var/www/html/galleryEsercizio/pallaarancio.png' );

?>