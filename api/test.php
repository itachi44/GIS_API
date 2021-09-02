<?php

echo 'test ok';
$request_method = $_SERVER["REQUEST_METHOD"];

echo $request_method;

if (isset($_GET["request"])){
    echo $_GET["request"];
}
