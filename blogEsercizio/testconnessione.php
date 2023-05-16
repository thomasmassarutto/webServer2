<?php
 require_once ("./aws/vendor/autoload.php");
 require_once ("./aws/vendor/c.php");

  global $KEY;
  global $SECRETKEY;

 use Aws\DynamoDb\DynamoDbClient;
 use Aws\Exception\AwsException;

 $credentials= new Aws\Credentials\Credentials ($KEY, $SECRETKEY); 

 $client = new Aws\DynamoDb\DynamoDbClient(['version' => 'latest', 
                                            'region' => 'eu-central-1', 
                                            'credentials' => $credentials]);

  $result = $client->scan([
    'TableName' => 'blog',
]);

if (!empty($result['Items'])) {
    foreach ($result['Items'] as $item) {
        $data = $item['DataPubblicazione']['S'];
        $titolo = $item['Titolo']['S'];
        $testo = $item['Testo']['S'];

        echo "Data: $data\n";
        echo "Titolo: $titolo\n";
        echo "Testo: $testo\n";
        echo "\n";
    }
} else {
    echo "La tabella è vuota.";
}

?>