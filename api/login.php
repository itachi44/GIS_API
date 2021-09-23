<?php

include_once '../Databases.php';
require "../vendor/firebase/php-jwt/src/JWT.php";
include "config.php";

use \Firebase\JWT\JWT;

// TODO user doit êter caster en objet
function Auth($user)
{
  $now = new DateTimeImmutable();
  $iat = $now->getTimestamp();
  $exp = $iat + 60 * 60 * 24 * 30; //1mois
  $key = GIS_KEY;

  $payload = array(
    "id_user" => $user->id_user,
    "email" => $user->email,
    "pwd" => $user->password,
    "first_name" => $user->first_name,
    "last_name" => $user->last_name,
    "telephone" => $user->telephone,
    "id_team" => $user->id_team,
    "iat" => $iat,
    "exp" => $exp,
  );
  $jwt = JWT::encode($payload, $key, 'HS512');

  return array(
    "token" => $jwt,
    "expires" => $exp
  );
}

function logUser($data)
{

  if (array_key_exists(0, $data)) {
    $data = $data[0];
  }
  if (isset($data["email"]) && isset($data["password"])) {
    $email = htmlspecialchars($data["email"]);
    $password = htmlspecialchars($data["password"]);
    //TODO crypter le mot de passe
    $database = new Database();
    $db = $database->getConnexion();
    $stmt = $db->prepare("SELECT * FROM user WHERE email=:email");
    $stmt->execute([
      "email" => $email
    ]);
    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      http_response_code(404);
      echo json_encode(array("response" => "cet utilisateur n'existe pas."));
    }
    //TODO on le convertit en objet php s'il existe
    $user = (object) $user;
    if ($user) {
      $hashed_password = $user->password;
      if (password_verify($password, $hashed_password)) {
        $token = Auth($user);
        if ($token) {
          http_response_code(200);
          echo json_encode($token, JSON_PRETTY_PRINT);
        } else {
          http_response_code(500);
          echo json_encode("erreur serveur", JSON_PRETTY_PRINT);
        }
      } else {
        http_response_code(404);
        echo json_encode(array("response" => "Mot de passe incorrect"), JSON_PRETTY_PRINT);
      }
    } else {
      http_response_code(404);
      echo json_encode(array("response" => "Cet utilisateur n\'existe pas"), JSON_PRETTY_PRINT);
    }
  } else {
    http_response_code(400);
    echo json_encode(array("response" => "données invalides.."), JSON_PRETTY_PRINT);
  }
}

if ($request_method == 'POST') {
  if (json_decode(file_get_contents("php://input"))) {
    $data = json_decode(file_get_contents("php://input"), True);
    if (array_key_exists(0, $data)) {
      $data = $data[0];
    }
    logUser($data);
  } else {
    logUser($_POST);
  }
}
