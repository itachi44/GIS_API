<?php
include_once '../Databases.php';
require "../vendor/firebase/php-jwt/src/JWT.php";
include "config.php";

use \Firebase\JWT\JWT;

global $decoded_data;
$headers = apache_request_headers();
if (array_key_exists("Authorization", $headers)) {
    $token = $headers["Authorization"];
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
}

function addUser($data)
{
    $missing_fields = [];
    if (
        isset($data["last_name"]) && isset($data["first_name"]) && isset($data["email"])
        && isset($data["password"]) && isset($data["telephone"]) && isset($data["team"])
    ) {
        $data["team"] = strtolower(trim(explode(":", $data["team"])[0]));
        //check constraint of team id
        if (!empty($data["team"])) {
            $database = new Database();
            $db = $database->getConnexion();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $st = $db->prepare("SELECT id_team FROM team WHERE team_name=:team");
            $st->execute([
                "team" => $data["team"]
            ]);
            if ($st->rowCount() > 0) {
                $team = $st->fetch(PDO::FETCH_ASSOC);
                $id_team = $team["id_team"];
            } else if ($st->rowCount() == 0) {
                http_response_code(400);
                echo json_encode(["response" => "cette équipe n'existe pas!"]);
                exit(1);
            }
        }
        //vérifier si l'utilisateur n'existe pas encore
        $stmt = $db->prepare("SELECT * FROM user WHERE email=:email");
        $stmt->execute([
            "email" => $data["email"]
        ]);
        if ($stmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(["response" => "cet utilisateur existe déja!"]);
            exit(1);
        } else {
            $user = (object)[
                "last_name" => $data["last_name"],
                "first_name" => $data["first_name"],
                "email" => $data["email"],
                "password" => $data["password"],
                "id_team" => $id_team
            ];
            try {
                $stmt = $db->prepare("INSERT INTO user(first_name,last_name,email,password,id_team) VALUES(:first_name,:last_name,:email,:password,:id_team)");
                $stmt->bindValue(':first_name', $user->first_name, PDO::PARAM_STR);
                $stmt->bindValue(':last_name', $user->last_name, PDO::PARAM_STR);
                $stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
                $stmt->bindValue(':password', password_hash($user->password, PASSWORD_DEFAULT), PDO::PARAM_STR);
                $stmt->bindValue(':id_team', $user->id_team, PDO::PARAM_INT);
                $stmt->execute();
                http_response_code(201);
                //récupérer l'objet dans la BD
                $st = $db->prepare("SELECT * FROM user WHERE email=:email");
                $st->execute([
                    "email" => $data["email"]
                ]);
                if ($st->rowCount() > 0) {
                    $user = $st->fetch(PDO::FETCH_ASSOC);
                }
                echo json_encode(array("response" => "creation de l'utilisateur réussie", "user" => $user), JSON_PRETTY_PRINT);
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }
    } else {
        if (empty($data["last_name"])) array_push($missing_fields, "last_name");
        if (empty($data["first_name"])) array_push($missing_fields, "first_name");
        if (empty($data["email"])) array_push($missing_fields, "email");
        if (empty($data["password"])) array_push($missing_fields, "password");
        if (empty($data["telephone"])) array_push($missing_fields, "telephone");
        if (empty($data["id_team"])) array_push($missing_fields, "id_team");
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
                if (empty($_GET["id_user"])) {
                    $stmt = $db->prepare("SELECT * FROM user");
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        http_response_code(200);
                        echo json_encode(array("response" => $users), JSON_PRETTY_PRINT);
                    } else {
                        http_response_code(404);
                        echo json_encode(array("response" => "pas de donnees"));
                        exit(1);
                    }
                } else {
                    $id_user = $_GET["id_user"];
                    $stmt = $db->prepare("SELECT * FROM user WHERE id_user=:id_user");
                    $stmt->execute(
                        ["id_user" => $id_user]
                    );
                    if ($stmt->rowCount() > 0) {
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo json_encode(array("response" => $user), JSON_PRETTY_PRINT);
                    } else {
                        http_response_code(404);
                        echo json_encode(array("response" => "utilisateur non trouvé"));
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
            addUser($data);
        } else {
            addUser($_POST);
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
                if (isset($_GET["id_user"]) && !empty($_GET["id_user"])) {

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
                        if (!empty($data["id_team"])) {
                            $database = new Database();
                            $db = $database->getConnexion();
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $st = $db->prepare("SELECT * FROM team WHERE id_team=:id_team");
                            $st->execute([
                                "id_team" => $data["id_team"]
                            ]);
                            if ($st->rowCount() == 0) {
                                http_response_code(400);
                                echo json_encode(["response" => "cette équipe n'existe pas!"]);
                                exit(1);
                            }
                        }
                        $id_user = $_GET["id_user"];

                        $cleaned_data = [];
                        foreach ($data as $key => $value) {
                            $cleaned_data[$key] = htmlspecialchars($value);
                        }

                        //récupération de l'entité à mettre à jour

                        $stmt = $db->prepare("SELECT * FROM user WHERE id_user=:id_user");
                        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        } else {
                            http_response_code(404);
                            echo json_encode(["response" => "cet utilisateur n'existe pas"]);
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
                        $stmt = $db->prepare("UPDATE user SET " . $fields . " WHERE id_user = :id_user");
                        foreach ($fields_values as $i => $value) {
                            $stmt->bindValue(':' . $keys[$i], $value, PDO::PARAM_STR);
                        }
                        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
                        $stmt->execute();
                        http_response_code(201);
                        echo json_encode(array("response" => "mise à jour effectuée avec succès."));
                    } else {
                        http_response_code(400);
                        echo json_encode(["response" => "données incorrectes"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["response" => "autorisation non accordée à cet utilisateur"]);
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
                if (isset($_GET["id_user"]) && !empty($_GET["id_user"])) {
                    $id_user = $_GET["id_user"];

                    $stmt = $db->prepare("DELETE FROM user WHERE id_user=:id_user");
                    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
                    $stmt->execute();
                    http_response_code(200);
                    echo json_encode(array("response" => "suppression réussie"));
                } else {
                    http_response_code(400);
                    echo json_encode(array("response" => "données incorrectes, veuillez entrer l'id de l'utilisateur"));
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
