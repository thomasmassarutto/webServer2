<?php

require_once("config.php");
require_once './aws/vendor/autoload.php';
require_once ("./aws/vendor/c.php");

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

function generaLinkImmagine($indice_immagine, $file, $nomeBucket) {
    global $KEY, $SECRETKEY;
    $credentials = new Aws\Credentials\Credentials($KEY, $SECRETKEY);
    $s3 = new Aws\S3\S3Client([
        'version' => 'latest',
        'region' => 'eu-central-1', 
        'credentials' => $credentials
    ]);
    $urlS3 = $s3->getObjectUrl($nomeBucket, $file);

    return "<a href=\"visualizza.php?immagine=" 
        . $indice_immagine. "\">" 
        . "<img src=\"" . $urlS3 . "\" width=\"80\" height=\"60\"/>"
        . "</a>";
}

$indice_immagine = 0;
$file = 'craiyon_095917_deep_space_with_galaxies__stars__planets_or_solar_systems_in_the_background.png';
$nomeBucket = 'tommygallerybucket';
$link = generaLinkImmagine($indice_immagine, $file, $nomeBucket);
echo $link;
?>