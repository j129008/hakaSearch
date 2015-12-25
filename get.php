<html>
    <head>
        <meta charset="utf-8" />
        <title>Search Result</title>
    </head>
    <body>
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
        foreach($results['hits']['hits'] as $term){
             print($term['_source']['t1']);
             print(" ".$term['_source']['t2']);
             print("<br>");
             print("作者: ".$term['_source']['author']);
             print("<br>");
             print($term['_source']['contain']);
             print("<br><br>");
        }
    }
}

?>
    </body>
</html>
