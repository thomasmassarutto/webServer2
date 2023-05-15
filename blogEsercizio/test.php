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
$table="rubrica";


?>