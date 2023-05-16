<?php
require_once("config.php");
require_once("./aws/vendor/autoload.php");
require_once("./aws/vendor/c.php");

use Aws\DynamoDb\DynamoDbClient;
use Aws\Exception\AwsException;

function leggi($idIniziale, $idFinale) {
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
    for ($id = $idFinale; $id >= $idIniziale; $id--) {
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



$contenuto = leggi(3, 5);

foreach ($contenuto as $post) {
    echo "<div class=\"post\">\n<h3>", $post['Titolo']['S'], "</h3>\n";
    echo "<p>", $post['Testo']['S'], "</p>\n";
    echo "<p class=\"info\">Pubblicato il: ", $post['DataPubblicazione']['S'], " da ", $UTENTE, "</p>\n</div>\n";
}

?>
