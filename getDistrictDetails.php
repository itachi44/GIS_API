<?php
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include_once 'Databases.php';
    include_once 'District.php';

    if (isset($_GET["id_district"])) {
        $id_district = $_GET["id_district"];
    } else {
        echo "id district maquant!!";
        exit();
    }



    //TODO : faire une requête vers la table MCD 

    //TODO : faire une requête vers la table resource

    //TODO : faire une requête vers la table district_data

    //TODO : faire une requête vers la table user et utiliser l'id team pour récupérer le team_name

    //TODO : agréger le tout


}
