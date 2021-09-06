<?php

include_once '../Databases.php' ;
//récupérations des données
session_start();  
$request_method = $_SESSION["method"];
$_POST=$_SESSION["post"];
$_GET=$_SESSION["get"];
if(isset($_SESSION["data"])){
$data=$_SESSION["data"];
}
unset($_SESSION["method"]);
unset($_SESSION["post"]);
unset($_SESSION["get"]);
session_destroy();
use \Firebase\JWT\JWT;

// TODO user doit êter caster en objet
function Auth($user){
    $now=new DateTimeImmutable();
    $iat=$now->getTimestamp();
    $exp=$iat+60*60*24*30; //1mois
    $key=""; //TODO générer une clé

    $payload=array(
        "id_user"=> $user->id_user,
        "email"=>$user->email,
        "pwd"=> $user->password,
        "iat"=>$iat,
        "exp"=>$exp,
    );
    $jwt = JWT::encode($payload, $key, 'HS512');
    
    return array(
        "token"=>$jwt,
        "expires"=>$exp
    );
}

  function logUser($data){
    
      if(array_key_exists(0,$data)){
      $data=$data[0];
      }
      if(isset($data["email"]) && isset($data["password"])){
          $email=htmlspecialchars($data["email"]);
          $password=htmlspecialchars($data["password"]);
          //TODO crypter le mot de passe
          $database = new Database();
          $db = $database->getConnexion();
          $stmt=$db->prepare("SELECT * FROM user WHERE email=:email");
          $stmt->execute([
              "email"=>$email
          ]);
          if($stmt->rowCount() > 0){
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
          }
          //TODO on le convertit en objet php s'il existe
          $user=(object) $user;
          if($user){
              $hashed_password = $user->password;
              if(password_verify($password, $hashed_password)){
                $token=Auth($user);
                if($token){
                  http_response_code(200);
                  echo json_encode($token, JSON_PRETTY_PRINT);
                }else{
                  http_response_code(500);
                  echo json_encode("impossible de générer le token.", JSON_PRETTY_PRINT);
                }
              }else{
                echo json_encode("Mot de passe incorrect", JSON_PRETTY_PRINT);
              }
          }else{
            http_response_code(404);
            echo json_encode("cet utilisateur n'existe pas.", JSON_PRETTY_PRINT);

          }
      }else{
        http_response_code(400);
        echo json_encode("données invalides", JSON_PRETTY_PRINT);

      }


  }

  if($request_method=='POST'){
            if (!empty($data)){
                    logUser($data);
             }else{
                    logUser($_POST);
             }

  }else{

  }

