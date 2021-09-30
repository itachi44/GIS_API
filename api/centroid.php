<?php
include_once '../Databases.php';
require "../vendor/firebase/php-jwt/src/JWT.php";
include "config.php";

use \Firebase\JWT\JWT;

global $decoded_data;
$headers = apache_request_headers();
if (array_key_exists("authorization", $headers)) {
    $token = $headers["authorization"];
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




switch ($request_method) {
    case 'GET':
        $database = new Database();
        $db = $database->getConnexion();
        if ($decoded_data->email) {
            $stmt = $db->prepare("SELECT * FROM user WHERE email=:email");
            $stmt->execute(["email" => $decoded_data->email]);
            if ($stmt->rowCount() > 0) {
                if (isset($_GET["code_district"])) {
                    $code_district = $_GET["code_district"];

                    $stmt = $db->prepare("SELECT * FROM centroids79districts WHERE code_district=:code_district");
                    $stmt->execute(
                        ["code_district" => $code_district]
                    );
                    if ($stmt->rowCount() > 0) {
                        $district = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo json_encode(array("response" => $district), JSON_PRETTY_PRINT);
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

    default:
        header("HTTP/1.0 405 Méthode non autorisée");
        break;
}
