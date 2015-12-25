<meta charset="utf-8" />
<?php
require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;


$hosts = [
    'localhost:9200'
];

$client = ClientBuilder::create()
    ->setHosts($hosts)      // Set the hosts
    ->build();              // Build the client object

if(!empty($_POST)){
    if(isset($_POST['keyword'])){
        $params['body'] = array(
            'query' => array(
                'match' => array(
                    '_all' => array(
                        'query' => $_POST['keyword']
                    )
                )
            )
        );
        $results = $client->search($params);
        print_r($results);
    }
}

?>
