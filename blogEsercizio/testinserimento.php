<?php
 require_once ("config.php");
 require_once ("./aws/vendor/autoload.php");
 require_once ("./aws/vendor/c.php");
 

 use Aws\DynamoDb\DynamoDbClient;
 use Aws\Exception\AwsException;

function registra($titolo, $testo) {
    // connessione al server
    global $KEY;
    global $SECRETKEY;
    global $NOME_DATABASE;

    $credentials= new Aws\Credentials\Credentials ($KEY, $SECRETKEY); 

    $client = new Aws\DynamoDb\DynamoDbClient(['version' => 'latest', 
                                                'region' => 'eu-central-1', 
                                                'credentials' => $credentials]);

    // quanti elementi ha il db
    $rows=nrrows();
    $Id= $rows +1; 
    //creazione elemento
    $post= array(
                'Id'=>array('N'=> strval($Id)),
                'DataPubblicazione'=>array('S'=>date("Y-m-d, G:i:s")),
                'Titolo'=>array('S'=>$titolo),
                'Testo'=>array('S'=>$testo)
                );
          
    // inserimento dell' elemnto post
    $rs=$client->putItem(array(
        'TableName' => $NOME_DATABASE,
        'Item' => $post)
        );
    
}

function nrrows() {
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

$test="test7";
registra($test, $test);
?>