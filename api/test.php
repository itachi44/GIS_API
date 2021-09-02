<?php

echo 'test ok';
$request_method = $_SERVER["REQUEST_METHOD"];



//TODO vérifier si les paramètres dans l'url arrivent normalement

if (isset($_GET["request"])){
    echo $_GET["request"];
}
