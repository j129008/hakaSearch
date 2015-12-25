<html>
    <head>
        <meta charset="utf-8" />
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
    $params = [
        'index'  => 'ir',
        'type'   => 'news',
        'id'     => 'AVHPv-vL7fIJYUpygO6E'
    ];
    print_r($client->get($params));
    ?>
    </body>
</html>
