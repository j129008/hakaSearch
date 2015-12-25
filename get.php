<html>
    <head>
        <meta charset="utf-8" />
        <title>Search Result</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<style>
h3{
color: blue;
font-weight: bold;
font-size: 18px;
}
h3:hover{
text-decoration: underline;
}
body{
padding-top: 15px;
}
strong{
color: red;
}
</style>
    </head>
    <body>
<div class="container-fluid col-md-8 col-md-offset-1">
        <form action="get.php" class="form-inline" method="post">
        <div class="form-group">
            <label>
            IR 搜尋：
            <input type="text" class="form-control" name="keyword">
            </label>
        </div>
        <input type="submit" class="btn btn-primary" value="SEARCH">
        </form>
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
            'from' => 0,
            'size' => 1200,
            'query' => array(
                'match' => array(
                    '_all' => array(
                        'query' => $_POST['keyword'],
                        'type' => 'phrase'
                    )
                )
            )
        );
        $results = $client->search($params);
        $keyword = $_POST['keyword'];
        foreach($results['hits']['hits'] as $term){
            $t1 = $term['_source']['t1'];
            $t2 = $term['_source']['t2'];
            $author = $term['_source']['author'];
            $contain = $term['_source']['contain'];

            $t1 = str_replace($keyword, '<strong>'.$keyword.'</strong>', $t1);
            $t2 = str_replace($keyword, '<strong>'.$keyword.'</strong>', $t2);
            $author = str_replace($keyword, '<strong>'.$keyword.'</strong>', $author);
            $contain = str_replace($keyword, '<strong>'.$keyword.'</strong>', $contain);
            print('<div class="all">');
            print("<h3>".$t1);
            print(" ".$t2."</h3>");
            print('<div class="contain">');
            print("<h4>作者: ".$author."</h4>");
            print($contain);
            print("<br><br>");
            print("</div>");
            print("</div>");
        }
        print('
        <script>
        $(".all").click(
            function(){
                var $t = $(".contain", this);
                if($t.is(\':visible\') == false){
                    $(".contain",this).slideDown();
                }else{
                    $(".contain",this).slideUp();
                }
            }
        );
        </script>'
        );
        print('<script>$(".contain").hide();</script>');
    }
}

?>
</div>
    </body>
</html>
