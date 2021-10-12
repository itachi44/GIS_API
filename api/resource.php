<?php
include_once '../Databases.php';
require "../vendor/firebase/php-jwt/src/JWT.php";
include "config.php";

use \Firebase\JWT\JWT;

global $decoded_data;
$headers = apache_request_headers();
if (array_key_exists("Authorization", $headers) || array_key_exists("authorization", $headers)) {
    if (array_key_exists("Authorization", $headers)) {
        $token = $headers["Authorization"];
    } else if (array_key_exists("authorization", $headers)) {
        $token = $headers["authorization"];
    }
    try {
        $decoded_data = JWT::decode($token, GIS_KEY, array("HS512"));
        $decoded_data = json_decode(json_encode($decoded_data));
    } catch (Exception $e) {
        if ($e->getMessage() == "Expired token") {
            http_response_code(405);
            echo json_encode(array("response" => "ce token est expiré!"));
            exit(1);
        }
    }
} else {
    http_response_code(405);
    echo json_encode("problème avec la clé. Contacter le fournisseur");
    exit(1);
}

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
        array_push($districts, "msas_" . $data[$key]["code_district"]);
    }
}

function insertData($data, $id)
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

function getIdDistrict($district_code)
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

    $id_district = getIdDistrict($district);
    insertData($resources, $id_district);
}




function addResource($data)
{
    $missing_fields = [];
    if (isset($data["id_district_data"])) {
        $internet_volume = "";
        $number_of_tablets_used = 0;
        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!empty($data["id_district_data"])) {
            $database = new Database();
            $db = $database->getConnexion();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $st = $db->prepare("SELECT * FROM district_data WHERE id_district_data=:id_district_data");
            $st->execute([
                "id_district_data" => $data["id_district_data"]
            ]);
            if ($st->rowCount() == 0) {
                http_response_code(400);
                echo json_encode(["response" => "ce rapport n'existe pas!"]);
                exit(1);
            }
        }

        if (isset($data["internet_volume"])) {
            $internet_volume = $data["internet_volume"];
        }
        if (isset($data["number_of_tablets_used"])) {
            $number_of_tablets_used = $data["number_of_tablets_used"];
        }
        try {
            $stmt = $db->prepare("INSERT INTO resource(internet_volume,number_of_tablets_used,id_district_data) VALUES(:internet_volume,:number_of_tablets_used,:id_district_data) WHERE NOT EXISTS (
                SELECT * FROM resource WHERE id_district = :id_district
            )");
            $stmt->bindValue(':internet_volume', $internet_volume, PDO::PARAM_STR);
            $stmt->bindValue(':number_of_tablets_used', $number_of_tablets_used, PDO::PARAM_INT);
            $stmt->bindValue(':id_district_data', $data["id_district_data"], PDO::PARAM_INT);
            $stmt->execute();
            http_response_code(201);
            echo json_encode(array("response" => "creation de la resource réussie"), JSON_PRETTY_PRINT);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        if (empty($data["id_district_data"])) array_push($missing_fields, "id_district_data");
        http_response_code(400);
        echo json_encode("données incorrectes. " . implode(",", $missing_fields));
        exit(1);
    }
}

switch ($request_method) {
    case 'GET':
        $database = new Database();
        $db = $database->getConnexion();
        if ($decoded_data->email) {
            $stmt = $db->prepare("SELECT * FROM user WHERE email=:email");
            $stmt->execute(["email" => $decoded_data->email]);
            if ($stmt->rowCount() > 0) {
                if (empty($_GET["id_resource"])) {
                    $stmt = $db->prepare("SELECT * FROM resource");
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        http_response_code(200);
                        echo json_encode(array("response" => $resources), JSON_PRETTY_PRINT);
                    } else {
                        http_response_code(404);
                        echo json_encode(array("response" => "pas de donnees"));
                    }
                } else {
                    $id_resource = $_GET["id_resource"];
                    $stmt = $db->prepare("SELECT * FROM resource WHERE id_resource=:id_resource");
                    $stmt->execute(
                        ["id_resource" => $id_resource]
                    );
                    if ($stmt->rowCount() > 0) {
                        $resource = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo json_encode(array("response" => $resource), JSON_PRETTY_PRINT);
                    } else {
                        http_response_code(404);
                        echo json_encode(array("response" => "resource non trouvé"));
                    }
                }
            } else {
                http_response_code(400);
                echo json_encode(array("response" => "permission non accordée à cet utilisateur."));
            }
        } else {
            http_response_code(400);
            echo json_encode(["response" => "veuillez vous authentifier"]);
        }

        break;

    case 'POST':
        if (json_decode(file_get_contents("php://input"))) {
            $data = json_decode(file_get_contents("php://input"), True);
            if (array_key_exists(0, $data)) {
                $data = $data[0];
            }
            addResource($data);
        } else {
            addResource($_POST);
        }

        break;

    case 'PUT':
        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($decoded_data->email) {
            $stmt = $db->prepare("SELECT * FROM user WHERE email=:email");
            $stmt->execute(["email" => $decoded_data->email]);
            if ($stmt->rowCount() > 0) {
                if (isset($_GET["id_resource"]) && !empty($_GET["id_resource"])) {

                    //récupération des données
                    if (json_decode(file_get_contents("php://input"))) {
                        $data = json_decode(file_get_contents("php://input"), True);
                        if (array_key_exists(0, $data)) {
                            $data = $data[0];
                        }
                    } else {
                        $data = $_POST;
                    }

                    if (!empty($data)) {
                        if (!empty($data["id_district_data"])) {
                            $database = new Database();
                            $db = $database->getConnexion();
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $st = $db->prepare("SELECT * FROM district_data WHERE id_district_data=:id_district_data");
                            $st->execute([
                                "id_district_data" => $data["id_district_data"]
                            ]);
                            if ($st->rowCount() == 0) {
                                http_response_code(400);
                                echo json_encode(["response" => "ce rapport n'existe pas!"]);
                                exit(1);
                            }
                        }

                        $id_resource = $_GET["id_resource"];

                        $cleaned_data = [];
                        foreach ($data as $key => $value) {
                            $cleaned_data[$key] = htmlspecialchars($value);
                        }

                        //récupération de l'entité à mettre à jour

                        $stmt = $db->prepare("SELECT * FROM resource WHERE id_resource=:id_resource");
                        $stmt->bindValue(':id_resource', $id_resource, PDO::PARAM_INT);
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            $resource = $stmt->fetch(PDO::FETCH_ASSOC);
                        } else {
                            http_response_code(404);
                            echo json_encode(["response" => "cette ressource n'existe pas"]);
                            exit(1);
                        }



                        //mise à jour
                        $keys = array_keys($cleaned_data);
                        $fields_values = array_values($cleaned_data);
                        $keys_str = [];
                        foreach ($keys as $i => $key) {
                            if ($i + 1 != count($keys)) {
                                $key = $key . "=:" . $key . ",";
                                array_push($keys_str, $key);
                            } else {
                                $key = $key . "=:" . $key;
                                array_push($keys_str, $key);
                            }
                        }
                        $fields = implode("", $keys_str);
                        $stmt = $db->prepare("UPDATE resource SET " . $fields . " WHERE id_resource = :id_resource");
                        foreach ($fields_values as $i => $value) {
                            $stmt->bindValue(':' . $keys[$i], $value, PDO::PARAM_STR);
                        }
                        $stmt->bindValue(':id_resource', $id_resource, PDO::PARAM_INT);
                        $stmt->execute();
                        http_response_code(201);
                        echo json_encode(array("response" => "mise à jour effectuée avec succès."));
                    } else {
                        http_response_code(400);
                        echo json_encode(["response" => "aucune données à mettre à jour."]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["response" => "veuillez entrer l'id de la ressource"]);
                    exit(1);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["response" => "veuillez vous authentifier"]);
            exit(1);
        }

        break;

    case 'DELETE':
        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($decoded_data->email) {
            $stmt = $db->prepare("SELECT * FROM user WHERE email=:email");
            $stmt->execute(["email" => $decoded_data->email]);
            if ($stmt->rowCount() > 0) {
                if (isset($_GET["id_resource"]) && !empty($_GET["id_resource"])) {
                    $id_resource = $_GET["id_resource"];
                    $stmt = $db->prepare("DELETE FROM resource WHERE id_resource=:id_resource");
                    $stmt->bindValue(':id_resource', $id_resource, PDO::PARAM_INT);
                    $stmt->execute();
                    http_response_code(200);
                    echo json_encode(array("response" => "suppression réussie"));
                } else {
                    http_response_code(400);
                    echo json_encode(array("response" => "données incorrectes, veuillez entrer l'id de la resource"));
                    exit(1);
                }
            }
        } else {
            http_response_code(400);
            echo json_encode(["response" => "veuillez vous authentifier"]);
            exit(1);
        }

        break;

    default:
        header("HTTP/1.0 405 Méthode non autorisée");
        break;
}
