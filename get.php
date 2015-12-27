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
margin-bottom: 0px;
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
<div style="padding-bottom: 100px" class="container-fluid col-md-8 col-md-offset-1">
        <form action="get.php"  method="post">
            <div class="form-inline">
                <div class="form-group">
                    <label>
                    <input type="text" class="form-control" name="keyword">
                    </label>
                    <input type="submit" class="btn btn-primary" value="SEARCH">
                </div>
            </div>
        </form>
<hr class="divider">
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
        $keyword = $_POST['keyword'];
        $keywordList = array();

        $query = "";
        foreach(explode(" ", $keyword) as $token){
            if( (strcmp($token, "AND") == 0 ) or ( strcmp($token, "OR") == 0 ) or ( strcmp( $token, "NOT" ) == 0 ) or ( strcmp( $token, ")" ) == 0 ) or ( strcmp( $token, "(" ) == 0 )){
                $query = $query." ".$token;
                continue;
            }
            $query = $query.' "'.$token.'" ';
            array_push($keywordList, $token);
        }

        $params['body'] = array(
            'from' => 0,
            'size' => 1200,
            'query' => array(
                'query_string' => array(
                    'query' => $query
                )
            )
        );


        $results = $client->search($params);

        $hits = $results['hits']['total'];
        print '<div style="color: gray">搜尋 "'.$keyword.'" 總共有 '.$hits.' 項結果</div><br>';
        foreach($results['hits']['hits'] as $term){
            $txt = $term['_source']['file'];
            $t1 = $term['_source']['t1'];
            $t2 = $term['_source']['t2'];
            $author = $term['_source']['author'];
            $contain = $term['_source']['contain'];

            $snippet = "";
            foreach($keywordList as $key){
                $key = str_replace('"', '', $key);
                $t1 = str_replace($key, '<strong>'.$key.'</strong>', $t1);
                $t2 = str_replace($key, '<strong>'.$key.'</strong>', $t2);
                $author = str_replace($key, '<strong>'.$key.'</strong>', $author);
                $contain = str_replace($key, '<strong>'.$key.'</strong>', $contain);
                foreach(explode("。", $contain) as $sentence){
                    if( strpos($sentence, $key) != false ){
                        $snippet = $snippet.$sentence."。";
                    }
                }
            }

            print('<div class="all">');
            print("<h3>".$t1);
            if(strlen($t2)>0){
                print(" - ".$t2);
            }
            print("</h3>");
            print('<div style="color:006621">'.$txt.'</div>');
            print('<div class="snippet">');
            print($snippet);
            print('</div>');
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
                $("h3", this).css("color","609");
                if($t.is(\':visible\') == false){
                    $(".contain",this).slideDown();
                    $(".snippet",this).slideUp();
                }else{
                    $(".contain",this).slideUp();
                    $(".snippet",this).slideDown();
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
