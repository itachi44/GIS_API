<?php
header("Acces-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include_once 'Databases.php';
    include_once 'District.php';

    if (isset($_GET["district"])) {
        $district = $_GET["district"];
        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //récupérer l'id district
        $stmt = $db->prepare("SELECT * from centroids79districts WHERE district_sanitaire=:district");
        $stmt->execute(["district" => $district]);
        if ($stmt->rowCount() > 0) {
            $district_infos = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_district = $district_infos["id_district"];
            $district_code = strtolower($district_infos["code_district"]);
        }

        //récupérer les infos du MCD
        $mcd_stmt = $db->prepare("SELECT * from MCD WHERE id_district=:id_district");
        $mcd_stmt->execute(["id_district" => $id_district]);
        if ($mcd_stmt->rowCount() > 0) {
            $mcd_infos = $mcd_stmt->fetch(PDO::FETCH_ASSOC);
            print_r($mcd_infos);
            exit();
        }
        //récupérer les infos resources
        $resource_stmt = $db->prepare("SELECT * from resource WHERE id_district_data=:district");
        $resource_stmt->execute(["district" => $id_district]);
        if ($resource_stmt->rowCount() > 0) {
            $resource_infos = $resource_stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        //récupérer les infos du rapport
        $report_stmt = $db->prepare("SELECT * from district_data WHERE id_centroid=:district");
        $report_stmt->execute(["district" => $id_district]);
        if ($report_stmt->rowCount() > 0) {
            $report_infos = $report_stmt->fetch(PDO::FETCH_ASSOC);
        }

        //récupérer les infos du user et son équipe
        $team_stmt = $db->prepare("SELECT * from team WHERE team_name=:team");
        $team_stmt->execute(["team" => "team_" . $district_code]);
        if ($team_stmt->rowCount() > 0) {
            $team_infos = $team_stmt->fetch(PDO::FETCH_ASSOC);
            $id_team = $team_infos["id_team"];

            $user_stmt = $db->prepare("SELECT * from user WHERE id_team=:id_team");
            $user_stmt->execute(["id_team" => $id_team]);
            if ($user_stmt->rowCount() > 0) {
                $user_infos = $user_stmt->fetch(PDO::FETCH_ASSOC);
                $user_infos["team"] = "team_" . $district_code;
            }
        }


        $data = [
            "mcd_data" => $mcd_infos,
            "resource_data" => $resource_infos,
            "report_data" => $report_infos,
            "user_data" => $user_infos
        ];
        http_response_code(200);
        echo json_encode(["response" => $data]);
    } else {
        http_response_code(400);
        echo json_encode(["response" => "id district maquant!!"]);
        exit();
    }
}
