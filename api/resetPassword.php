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


function resetPassword($data)
{
    if (isset($data["email"])) {
        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare("SELECT * FROM user WHERE email=:email");
        $stmt->execute(["email" => $data["email"]]);
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $email = $user["email"];
            $token = bin2hex(random_bytes(50));
            $st = $db->prepare("INSERT into pass_reset(email,token) VALUES(:email,:token)");
            $st->execute(["email" => $email, "token" => $token]);

            //The user will receive the link to the password reset form page and click on it.
            $FromName = "GIS-IPD";
            $FromEmail = "no_reply@pasteur.sn";
            $ReplyTo = "diopbamba86@gmail.com"; //TODO changer
            $credits = "All rights are reserved | GIS-IPD ";
            $msg = "
            Le lien de réinitialisation de votre mot de passe
             <br> http://http://localhost:8081/password-reset.php?token=" . $token . " <br> Réinitialisez votre mot de passe en cliquant sur ce lien ou en l'ouvrant dans un nouvel onglet.<br><br> <br> <br> <center>" . $credits . "</center>";

            $headers  = "MIME-Version: 1.0\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\n";
            $headers .= "From: " . $FromName . " <" . $FromEmail . ">\n";
            $headers .= "Reply-To: " . $ReplyTo . "\n";
            $headers .= "X-Sender: <" . $FromEmail . ">\n";
            $headers .= "X-Mailer: PHP\n";
            $headers .= "X-Priority: 1\n";
            $headers .= "Return-Path: <" . $FromEmail . ">\n";
            $subject = "Vous avez reçu l'e-mail de réinitialisation du mot de passe";
            if (mail($email, $subject, $msg, $headers, '-f' . $FromEmail)) {
                http_response_code(200);
                echo json_encode(["response" => "ok"]);
            } else {
                http_response_code(500);
                echo json_encode(["response" => "erreur"]);
            }
        } else {
            http_response_code(404);
            echo json_encode(["response" => "cet utilisateur n'existe pas!"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["response" => "adresse email manquant!"]);
    }
}

switch ($request_method) {
    case 'POST':
        if (json_decode(file_get_contents("php://input"))) {
            $data = json_decode(file_get_contents("php://input"), True);
            if (array_key_exists(0, $data)) {
                $data = $data[0];
            }
            resetPassword($data);
        } else {
            resetPassword($_POST);
        }

        break;

    default:
        header("HTTP/1.0 405 Méthode non autorisée");
        break;
}
