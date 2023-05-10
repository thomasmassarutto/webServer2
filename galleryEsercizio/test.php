<?php

require_once("config.php");
require_once './aws/vendor/autoload.php';
require_once ("./aws/vendor/c.php");

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

function generaLinkImmagine($file, $nomeBucket) {
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

$file = 'craiyon_095919_deep_space_with_galaxies__stars__planets_or_solar_systems_in_the_background.png';
$nomeBucket = 'tommygallerybucket';
$urlImmagine = generaLinkImmagine($file, $nomeBucket);
echo $urlImmagine;
echo "<img src=\"$urlImmagine\" width=\"80\" height=\"60\"/>";
echo "<a href=\"$urlImmagine\">aaa</a>";

?>