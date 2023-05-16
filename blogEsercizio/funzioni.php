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

    $client = new Aws\DynamoDb\DynamoDbClient([
                                                'version' => 'latest', 
                                                'region' => 'eu-central-1', 
                                                'credentials' => $credentials
                                             ]);

    // quanti elementi ha il db
    $rows=numeroPost();
    // l' id del nuovo post
    $Id= $rows +1; 
    //creazione elemento: post: array di array
    $post= array(
                'Id'=>array('N'=> strval($Id)),
                'DataPubblicazione'=>array('S'=>date("Y-m-d, G:i:s")),
                'Titolo'=>array('S'=>$titolo),
                'Testo'=>array('S'=>$testo)
                );
    // inserimento del post nel db
    $rs=$client->putItem(array(
                                'TableName' => $NOME_DATABASE,
                                'Item' => $post)
                        );
}

// ritorna un array con dei post, i post sono selezionati in base all id
// inizia a contare dal post con id piu alto e scende: leggi(5, 3) -> [post5, post4, post3]
// "funziona a ritroso"
// parte da $idIniziale e seleziona $quanti post  
function leggi($idIniziale, $quanti= NULL) {// return array di post
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
    // id: variabile contatore che viene decrementata per scendere negli id dei post
    // condizioni: id>0 per non leggere indici nulli
    //             id > (idIniziale - quanti) -> id >  id di termine: limite inf. degli id 
    for (   $id = $idIniziale; 
            $id > 0 && $id > ($idIniziale - $quanti); // 
            $id--) {
        // prepara i parametri di ricerca per l'elemento nel database
        $params = [// params: array associativo
            'TableName' => $NOME_DATABASE,
            'Key' => [
                        'Id' => ['N' => strval($id)]// id: Key di ricerca, convertito a  stringa
                    ]
        ];

        //richiesta a db per ottenere l elemento dal database
        $result = $client->getItem($params);
        // elemeneti corrispondenti vengono aggiunti all array dei post
        if (isset($result['Item'])) {
            $posts[] = $result['Item'];
        }
    }
    // array di posts
    return $posts;
}

//Conta le righe del db per sapere quanti post ci sono 
function numeroPost() {// return int
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

// controlla che utente e password siano corretti
// utente e passwrd corretti sono variabili globali in config.php
function utenteValido($utente, $password) {// return bool

    global $UTENTE, $PASSWORD;
    return ( $utente == $UTENTE && $password == $PASSWORD );
}

?>