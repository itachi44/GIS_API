<?php
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'Databases.php';

//récupérer le district posté par ajax puis rechercher la latitude et longitude et les renvoyer
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method == 'POST') {
    //on récupère le district
    if (file_get_contents('php://input', true)) {
        $data = json_decode(file_get_contents('php://input', true));
    } else {
        $data = $_POST;
    }
    //on récupère les infos (lat, lng)
    if (isset($data->district)) {
        $district_name = strtoupper($data->district);
        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt1 = $db->prepare("SELECT latitude,longitude FROM centroids79districts WHERE district_sanitaire = :district");
        $stmt1->execute(["district" => $district_name]);
        $stmt2 = $db->prepare("SELECT latitude,longitude FROM centroids79districts WHERE code_district = :district");
        $stmt2->execute(["district" => $district_name]);
        if ($stmt1->rowCount() == 1) {
            $tab = [];
            $row = $stmt1->fetch(PDO::FETCH_ASSOC);
            $infos = [
                "latitude" => $row["latitude"],
                "longitude" => $row["longitude"],
                "zoom" => 15
            ];
            $tab['coordinates'] = $infos;
            http_response_code(200);
            echo json_encode($tab);
        }
        //si on ne trouve rien dans cette table on cherche dans la table code district
        else if ($stmt2->rowCount() == 1) {
            $tab = [];
            $row = $stmt2->fetch(PDO::FETCH_ASSOC);
            $infos = [
                "latitude" => $row["latitude"],
                "longitude" => $row["longitude"],
                "zoom" => 15
            ];
            $tab['coordinates'] = $infos;
            http_response_code(200);

            //On encode en json et on envoie
            echo json_encode($tab);
        } else {
            http_response_code(404);
            echo json_encode(["response" => "infos non trouvées"]);
        }
    }
}
