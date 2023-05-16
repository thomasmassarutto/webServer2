<?php
require_once("config.php");
require_once("./aws/vendor/autoload.php");
require_once("./aws/vendor/c.php");

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

function numeroPost() {
    // Connessione al server
    global $KEY;
    global $SECRETKEY;
    global $NOME_DATABASE;

    $credentials = new Aws\Credentials\Credentials($KEY, $SECRETKEY);

    $client = new Aws\DynamoDb\DynamoDbClient([
        'version' => 'latest',
        'region' => 'eu-central-1',
        'credentials' => $credentials
    ]);

    $params = [
        'TableName' => $NOME_DATABASE,
        'Select' => 'COUNT'
    ];

    $result = $client->scan($params);
    $count = $result['Count'];

    return $count;
}
echo "ciao";
echo numeroPost();
echo "ciao";
?>
