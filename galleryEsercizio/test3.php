<?php
require 'aws/vendor/autoload.php';
require_once ("./aws/vendor/c.php");

use Aws\S3\S3Client;


function inserisciImmagineSuS3($image ){
    
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
                               'Key' => $image,
                               'SourceFile' => 'pallablu.png',
                                ]);
    

    }
inserisciImmagineSuS3( 'pallablu.png' );

?>