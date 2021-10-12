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



function addReport($data)
{
    $missing_fields = [];
    if (
        isset($data["id_user"]) && isset($data["id_district"]) && isset($data["date"])  && isset($data["starting_time"])
        && isset($data["ending_time"]) && isset($data["starting_range"]) && isset($data["ending_range"])
    ) {

        $comment = "";
        $starting_time = "";
        $ending_time = "";
        if (isset($data["comment"])) {
            $comment = $data["comment"];
        }
        $date = new DateTime($data["date"]);
        $date = $date->format('Y-m-d H:i:s');
        $starting_time = new DateTime($data["starting_time"]);
        $starting_time = $starting_time->format('H:i:s');
        $ending_time = new DateTime($data["ending_time"]);
        $ending_time = $ending_time->format('H:i:s');

        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if (!empty($data["id_user"])) {
            $database = new Database();
            $db = $database->getConnexion();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $st = $db->prepare("SELECT * FROM user WHERE id_user=:id_user");
            $st->execute([
                "id_user" => $data["id_user"]
            ]);
            if ($st->rowCount() == 0) {
                http_response_code(400);
                echo json_encode(["response" => "cet utilisateur n'existe pas!"]);
                exit(1);
            }
        }
        if (!empty($data["id_district"])) {
            $database = new Database();
            $db = $database->getConnexion();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $st = $db->prepare("SELECT * FROM centroids79districts WHERE id_district=:id_district");
            $st->execute([
                "id_district" => $data["id_district"]
            ]);
            if ($st->rowCount() == 0) {
                http_response_code(400);
                echo json_encode(["response" => "ce district n'existe pas!"]);
                exit(1);
            }
        }
        $context = [
            "allocated_range" => $data["starting_range"] . "-" . $data["ending_range"],
            "starting_time" => $starting_time,
            "ending_time" => $ending_time,
            "comment" => $comment,
            "id_user" => $data["id_user"],
            "id_centroid" => $data["id_district"],
            "date" => $date
        ];
        try {
            $stmt = $db->prepare("INSERT INTO district_data(allocated_range,starting_time,ending_time,comment,id_user,id_centroid,date) 
               VALUES(:allocated_range,:starting_time,:ending_time,:comment,:id_user,:id_centroid,:date)");
            $stmt->execute($context);
            http_response_code(201);
            echo json_encode(array("response" => "creation du rapport réussie"), JSON_PRETTY_PRINT);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        if (empty($data["id_user"])) array_push($missing_fields, "id_user");
        if (empty($data["id_district"])) array_push($missing_fields, "id_district");
        if (empty($data["starting_range"])) array_push($missing_fields, "starting_range");
        if (empty($data["ending_range"])) array_push($missing_fields, "ending_range");
        if (empty($data["starting_time"])) array_push($missing_fields, "starting_time");
        if (empty($data["ending_time"])) array_push($missing_fields, "ending_time");
        if (empty($data["date"])) array_push($missing_fields, "date");
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
                if (empty($_GET["id_report"])) {
                    if (isset($_GET["how"])) {
                        if ($_GET["how"] == "daily") {
                            $stmt = $db->prepare("SELECT * FROM district_data WHERE date_envoie<= NOW() AND date_envoie>=TO_DAYS(NOW()-'1') ORDER BY date_envoie DESC LIMIT 6");
                            $stmt->execute();
                            if ($stmt->rowCount() > 0) {
                                $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                http_response_code(200);
                                echo json_encode(array("response" => $reports), JSON_PRETTY_PRINT);
                            } else {
                                http_response_code(404);
                                echo json_encode(array("response" => "pas de donnees"));
                                exit(1);
                            }
                        }
                    } else {
                        $stmt = $db->prepare("SELECT * FROM district_data");
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            http_response_code(200);
                            echo json_encode(array("response" => $reports), JSON_PRETTY_PRINT);
                        } else {
                            http_response_code(404);
                            echo json_encode(array("response" => "pas de donnees"));
                            exit(1);
                        }
                    }
                } else {
                    $id_district_data = $_GET["id_report"];
                    $stmt = $db->prepare("SELECT * FROM district_data WHERE id_district_data=:id_district_data");
                    $stmt->execute(
                        ["id_district_data" => $id_district_data]
                    );
                    if ($stmt->rowCount() > 0) {
                        $report = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo json_encode(array("response" => $report), JSON_PRETTY_PRINT);
                    } else {
                        http_response_code(404);
                        echo json_encode(array("response" => "rapport non trouvé"));
                        exit(1);
                    }
                }
            } else {
                http_response_code(400);
                echo json_encode(array("response" => "permission non accordée à cet utilisateur."));
                exit(1);
            }
        } else {
            http_response_code(400);
            echo json_encode(["response" => "veuillez vous authentifier"]);
            exit(1);
        }

        break;

    case 'POST':
        if (json_decode(file_get_contents("php://input"))) {
            $data = json_decode(file_get_contents("php://input"), True);
            if (array_key_exists(0, $data)) {
                $data = $data[0];
            }
            addReport($data);
        } else {
            addReport($_POST);
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
                if (isset($_GET["id_report"]) && !empty($_GET["id_report"])) {
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
                        $id_district_data = $_GET["id_report"];
                        if (!empty($data["id_user"])) {
                            $database = new Database();
                            $db = $database->getConnexion();
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $st = $db->prepare("SELECT * FROM user WHERE id_user=:id_user");
                            $st->execute([
                                "id_user" => $data["id_user"]
                            ]);
                            if ($st->rowCount() == 0) {
                                http_response_code(400);
                                echo json_encode(["response" => "cet utilisateur n'existe pas!"]);
                                exit(1);
                            }
                        }
                        if (!empty($data["id_centroid"])) {
                            $database = new Database();
                            $db = $database->getConnexion();
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $st = $db->prepare("SELECT * FROM centroids79districts WHERE id_district=:id_centroid");
                            $st->execute([
                                "id_centroid" => $data["id_centroid"]
                            ]);
                            if ($st->rowCount() == 0) {
                                http_response_code(400);
                                echo json_encode(["response" => "ce district n'existe pas!"]);
                                exit(1);
                            }
                        }

                        $cleaned_data = [];
                        foreach ($data as $key => $value) {
                            $cleaned_data[$key] = $value;
                        }

                        //récupération de l'entité à mettre à jour

                        $stmt = $db->prepare("SELECT * FROM district_data WHERE id_district_data=:id_district_data");
                        $stmt->bindValue(':id_district_data', $id_district_data, PDO::PARAM_INT);
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            $report = $stmt->fetch(PDO::FETCH_ASSOC);
                        } else {
                            http_response_code(404);
                            echo json_encode(["response" => "ce rapport n'existe pas"]);
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
                        $context = [];
                        $stmt = $db->prepare("UPDATE district_data SET " . $fields . " WHERE id_district_data=:id_district_data");
                        foreach ($fields_values as $i => $value) {
                            $context[$keys[$i]] = $value;
                            //$stmt->bindValue(':'.$keys[$i], $value);
                        }
                        $context[':id_district_data'] = $id_district_data;
                        //$stmt->bindValue(':id_district_data', $id_district_data, PDO::PARAM_INT);
                        $stmt->execute($context);
                        http_response_code(200);
                        echo json_encode(array("response" => "mise à jour effectuée avec succès."));
                    } else {
                        http_response_code(400);
                        echo json_encode(["response" => "pas de données à mettre à jour"]);
                        exit(1);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["response" => "veuillez entrer l'id du rapport."]);
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
                if (isset($_GET["id_report"]) && !empty($_GET["id_report"])) {
                    $id_report = $_GET["id_report"];

                    $stmt = $db->prepare("DELETE FROM district_data WHERE id_district_data=:id_report");
                    $stmt->bindValue(':id_report', $id_report, PDO::PARAM_INT);
                    $stmt->execute();
                    http_response_code(200);
                    echo json_encode(array("response" => "suppression réussie"));
                } else {
                    http_response_code(400);
                    echo json_encode(array("response" => "données incorrectes, veuillez entrer l'id du rapport"));
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
