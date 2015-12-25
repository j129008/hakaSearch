<html>
    <head>
        <meta charset="utf-8" />
        <title>Search Result</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
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
             print("<h3>".$term['_source']['t1']);
             print(" ".$term['_source']['t2']."</h3>");
             print("<h4>作者: ".$term['_source']['author']."</h4>");
             print($term['_source']['contain']);
             print("<br><br>");
        }
    }
}

?>
    </body>
</html>
