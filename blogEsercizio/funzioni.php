<?php
 require_once ("config.php");
 require_once ("./aws/vendor/autoload.php");
 require_once ("./aws/vendor/c.php");
 use Aws\DynamoDb\DynamoDbClient;
 use Aws\Exception\AwsException;
 
  // inserisce un post nel database
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
    $rows=numeroPost();
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

// ritorna un array con dei post 
function leggi($idIniziale, $quanti= NULL) {
    // connessione al server
    global $KEY;
    global $SECRETKEY;
    global $NOME_DATABASE;

    $credentials = new Aws\Credentials\Credentials($KEY, $SECRETKEY);

    $client = new Aws\DynamoDb\DynamoDbClient([
        'version' => 'latest',
        'region' => 'eu-central-1',
        'credentials' => $credentials
    ]);
    
    // creo array dei post presenti
    $posts = array();
    // ciclo per trovare il post col numero giusto
    for (   $id = $idIniziale; 
            $id > 0 && $id > ($idIniziale - $quanti); 
            $id--) {
        // prepara i parametri di ricerca per l'elemento nel database
        $params = [// params: aray associativo
            'TableName' => $NOME_DATABASE,
            'Key' => [
                        'Id' => ['N' => strval($id)]// id: Key di ricerca, convertito a  stringa
                    ]
        ];

        //richiesta per ottenere l'elemento dal database
        $result = $client->getItem($params);
        // se lo trova, viene aggiunto all'elenco di post che mi servono
        if (isset($result['Item'])) {
            $posts[] = $result['Item'];
        }
    }
    // array di array
    return $posts;
}


//Conta le righe del db per sapere quanti post ci sono 
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
    
function utenteValido($utente, $password) {

    global $UTENTE, $PASSWORD;
    return ( $utente == $UTENTE && $password == $PASSWORD );
}

?>