<?php
include_once __DIR__ . '/../Databases.php';
include __DIR__ . "/config.php";


echo "Running DB update\n";

function curl_get_contents($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

$database = new Database();
$db = $database->getConnexion();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//récupérer la liste des codes districts 

$st = $db->prepare("SELECT * FROM centroids79districts");
$st->execute();
if ($st->rowCount() > 0) {
    $data = $st->fetchAll(PDO::FETCH_ASSOC);
    $data = $data;
}
$districts = [];
if (is_array($data) || is_object($data)) {
    foreach ($data as $key => $value) {
        array_push($districts, $data[$key]["code_district"]);
    }
}


function insertData($data, $id)
{

    $context = [
        "tested_sample" => $data["nbr_teste"],
        "positive_sample" => $data["nbr_positif"],
        "non_conforming_sample" => $data["nbr_non_conforme"],
        "id" => $id
    ];
    $database = new Database();
    $db = $database->getConnexion();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $st1 = $db->prepare("SELECT * FROM district_data");
    $st1->execute();
    if ($st1->rowCount()) {
        $reports = $st1->fetchAll(PDO::FETCH_ASSOC);
    }
    if ($reports) {
        foreach ($reports as $key => $value) {
            if ($value["id_centroid"] == $id) {
                $stmt = $db->prepare("UPDATE district_data SET tested_sample=:tested_sample, positive_sample=:positive_sample,non_conforming_sample=:non_conforming_sample WHERE id_centroid=:id");
                $stmt->execute($context);
            }
        }
    }
}

function getIdDistrict($district_code)
{
    $code = $district_code;
    $database = new Database();
    $db = $database->getConnexion();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare("SELECT * from centroids79districts WHERE code_district=:code");
    $stmt->execute(["code" => $code]);
    if ($stmt->rowCount()) {
        $infos = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return $infos["id_district"];
}

foreach ($districts as $key => $district) {
    //récupération des données
    $api_url = "http://10.1.7.13/gisApi/infoDistrict.php?district=" . $district;
    $json_data = curl_get_contents($api_url);

    $response_data = json_decode($json_data);
    //traitement des données
    $infos = (array) $response_data->data;
    $id_district = getIdDistrict($district);
    insertData($infos, $id_district);
}

$database = new Database();
$db = $database->getConnexion();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//récupérer la liste des codes districts 

$st = $db->prepare("SELECT * FROM centroids79districts");
$st->execute();
if ($st->rowCount() > 0) {
    $data = $st->fetchAll(PDO::FETCH_ASSOC);
    $data = $data;
}
$districts = [];
if (is_array($data) || is_object($data)) {
    foreach ($data as $key => $value) {
        array_push($districts, "msas_" . $data[$key]["code_district"]);
    }
}

function insertData2($data, $id)
{
    $database = new Database();
    $db = $database->getConnexion();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach ($data as $key => $value) {
        $context = [
            "model" => $data[$key]->model,
            "nb_tablets" => $data[$key]->count,
            "id_district_data" => $id
        ];
        //TODO insert if not exists
        $stmt = $db->prepare("REPLACE into resource(model, number_of_tablets_used,id_district_data) VALUES(:model,:nb_tablets,:id_district_data)");
        $stmt->execute($context);
    }
}

function getIdDistrict2($district_code)
{
    $code = strtoupper(explode("_", $district_code)[1]);
    $database = new Database();
    $db = $database->getConnexion();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare("SELECT * from centroids79districts WHERE code_district=:code");
    $stmt->execute(["code" => $code]);
    if ($stmt->rowCount()) {
        $infos = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    return $infos["id_district"];
}

foreach ($districts as $key => $district) {
    //récupération des données
    $api_url = "https://teranga-mobile.pasteur.sn/api/v1/stats/mobile_device/aggregate/count?team_uid=" . strtolower($district);
    $json_data = curl_get_contents($api_url);

    $response_data = json_decode($json_data);

    //traitement des données
    if ($response_data->status == "ok") {
        $resources = [];
        foreach ($response_data->data as $key => $value) {
            array_push($resources, $response_data->data[$key]);
        }
    }

    $id_district = getIdDistrict2($district);
    insertData2($resources, $id_district);
}

echo "Done\n";
