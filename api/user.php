<?php
include_once '../Databases.php' ;
$database = new Database();
$db = $database->getConnexion();
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

//functions

function addUser($data){

    if(isset($data["last_name"]) && isset($data["first_name"]) && isset($data["email"])
     && isset($data["password"]) && isset($data["telephone"]) && isset($data["id_team"])){
        $database = new Database();
        $db = $database->getConnexion();
        //vérifier si l'utilisateur n'existe pas encore
        $stmt=$db->prepare("SELECT * FROM user WHERE email=:email");
        $stmt->execute([
            "email"=>$data["email"]
        ]);
        if($stmt->rowCount()>0){
            http_response_code(400);
            echo json_encode(["response"=>"cet utilisateur existe déja!"]);

        }else{
            $user=(object)[
                "last_name"=>$data["last_name"],
                "first_name"=>$data["first_name"],
                "email"=>$data["email"],
                "password"=>$data["password"],
                "id_team"=>$data["id_team"]
            ];
            try{
               $stmt=$db->prepare("INSERT INTO user(first_name,last_name,email,password,id_team)VALUES(:first_name,:last_name,:email,:password,:id_team)");
               $stmt->execute(
                   [
                       "first_name"=>$user->first_name,
                       "last_name"=>$user->last_name,
                       "email"=>$user->email,
                       "password"=>password_hash($user->password, PASSWORD_DEFAULT),
                       "id_team"=>$user->id_team
                   ]
               );
               http_response_code(201);
               echo json_encode(array("response"=>"creation de l'utilisateur réussi","user"=>$user),JSON_PRETTY_PRINT);
   
            }catch(PDOException $e){
               echo "Erreur : " . $e->getMessage();
           }
        }


    }else{
        //les données sont incorrects faire les controles et afficher l'erreur
        http_response_code(400);
    }

}
switch ($request_method) {
    case 'GET':
        if(empty($_GET["id_user"])){
        $stmt=$db->prepare("SELECT * FROM user");
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode(array("response"=>$users),JSON_PRETTY_PRINT);
        }else{
            http_response_code(404);
            echo json_encode(array("response"=>"pas de donnees"));
        }
        }else{
            $id_user=$_GET["id_user"];
            $stmt=$db->prepare("SELECT * FROM user WHERE id_user=:id_user");
            $stmt->execute(
                ["id_user"=>$id_user]
            );
            if($stmt->rowCount()>0){
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode(array("response"=>$user),JSON_PRETTY_PRINT);
            }else{
                http_response_code(404);
                echo json_encode(array("response"=>"utilisateur non trouvé"));
            }
        }
        break;

    case 'POST':
        //ne pas oublier de hasher le mot de passe
        if (!empty($data)){
            addUser($data);
     }else{
            addUser($_POST);
     }

        break;
    
    case 'PUT':

        break;

    case 'DELETE':

        break;
    
    default:
      header("HTTP/1.0 405 Méthode non autorisée");
      break;
        break;
}

