<?php
require_once 'get.php';
?>

<html>
    <head>
        <meta charset="utf-8" />
    </head>
    <body>
        <form action="get.php" method="post">
        <label>
        IR搜尋
        <input type="text" name="keyword">
        </label>
        <input type="submit" value="search">
        </form>
    </body>
</html>
