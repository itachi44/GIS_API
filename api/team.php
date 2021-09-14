<?php
include_once '../Databases.php' ;
require "../vendor/firebase/php-jwt/src/JWT.php";
include "config.php";
use \Firebase\JWT\JWT;

global $decoded_data;
    $headers=apache_request_headers();
    if(array_key_exists("Authorization",$headers)){
            $token= $headers["Authorization"];
            try{
                $decoded_data = JWT::decode($token,GIS_KEY, array("HS512"));
                $decoded_data=json_decode(json_encode($decoded_data));
            }catch(Exception $e ){
            if($e->getMessage() == "Expired token"){
                http_response_code(405);
                echo json_encode(array("response"=>"ce token est expiré!"));
                exit(1);
            } 
            }
        }else{
            http_response_code(405);
            echo json_encode("problème avec la clé. Contacter le fournisseur");
            exit(1);
          }
    
function addTeam($data){
    $missing_fields=[];
    if(isset($data["team_name"])){
        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //vérifier si l'équipe n'existe pas encore
        $stmt=$db->prepare("SELECT * FROM team WHERE team_name=:team_name");
        $stmt->execute([
            "team_name"=>$data["team_name"]
        ]);
        if($stmt->rowCount()>0){
            http_response_code(400);
            echo json_encode(["response"=>"cette équipe existe déja!"]);
            exit(1);

        }else{
            try{
               $stmt=$db->prepare("INSERT INTO team(team_name) VALUES(:team_name)");
               $stmt->bindValue(':team_name', $data["team_name"], PDO::PARAM_STR);
               $stmt->execute();
               http_response_code(201);
               echo json_encode(array("response"=>"creation de l'équipe réussie","user"=>$data["team_name"]),JSON_PRETTY_PRINT);
   
            }catch(PDOException $e){
               echo "Erreur : " . $e->getMessage();
           }
        }


    }else{
        if(empty($data["team_name"])) array_push($missing_fields,"team_name");
        http_response_code(400);
        echo json_encode("données incorrectes. ".implode(",",$missing_fields));
        exit(1);
    }

}

switch ($request_method) {
    case 'GET':
        $database = new Database();
        $db = $database->getConnexion();
        if($decoded_data->email){
            $stmt=$db->prepare("SELECT * FROM user WHERE email=:email");
            $stmt->execute(["email"=>$decoded_data->email]);
            if($stmt->rowCount() > 0){
                if(empty($_GET["id_team"])){
                    $stmt=$db->prepare("SELECT * FROM team");
                    $stmt->execute();
                    if($stmt->rowCount() > 0){
                        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        http_response_code(200);
                        echo json_encode(array("response"=>$teams),JSON_PRETTY_PRINT);
                    }else{
                        http_response_code(404);
                        echo json_encode(array("response"=>"pas de donnees"));
                        exit(1);
                    }
                    }else{
                        $id_team=$_GET["id_team"];
                        $stmt=$db->prepare("SELECT * FROM team WHERE id_team=:id_team");
                        $stmt->execute(
                            ["id_team"=>$id_team]
                        );
                        if($stmt->rowCount()>0){
                            $team = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo json_encode(array("response"=>$team),JSON_PRETTY_PRINT);
                        }else{
                            http_response_code(404);
                            echo json_encode(array("response"=>"équipe non trouvée"));
                            exit(1);
                        }
                    }
            }else{
                http_response_code(400);
                echo json_encode(array("response"=>"permission non accordée à cet utilisateur."));
                exit(1);
            }
        }else{
            http_response_code(400);
            echo json_encode(["response"=>"veuillez vous authentifier"]);
            exit(1);
        }

        break;

    case 'POST':
        if (json_decode(file_get_contents("php://input"))){
            $data=json_decode(file_get_contents("php://input"),True);
            if(array_key_exists(0,$data)){
                  $data=$data[0];
            }
            addTeam($data);
    }else{
            addTeam($_POST);
    } 

        break;
    
    case 'PUT':
        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if($decoded_data->email){
            $stmt=$db->prepare("SELECT * FROM user WHERE email=:email");
            $stmt->execute(["email"=>$decoded_data->email]);
            if($stmt->rowCount() > 0){
            if(isset($_GET["id_team"]) && !empty($_GET["id_team"])){
            //récupération des données
            if(json_decode(file_get_contents("php://input"))){
                    $data=json_decode(file_get_contents("php://input"),True);
                    if(array_key_exists(0,$data)){
                          $data=$data[0];
                    }
            }else{
                    $data=$_POST;
            }
    if(!empty($data)){


      $id_team=$_GET["id_team"];
      
      $cleaned_data=[];
      foreach($data as $key=>$value){
            $cleaned_data[$key]=htmlspecialchars($value);
      }

      //récupération de l'entité à mettre à jour

        $stmt=$db->prepare("SELECT * FROM team WHERE id_team=:id_team");
        $stmt->bindValue(':id_team',$id_team, PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount()>0){
            $team=$stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            http_response_code(404);
            echo json_encode(["response"=>"cette équipe n'existe pas"]);
            exit(1);
        }

      

       //mise à jour
        $keys=array_keys($cleaned_data);
        $fields_values=array_values($cleaned_data);
        $keys_str=[];
        foreach ($keys as $i=>$key) {
            if($i+1!=count($keys)){
                $key=$key."=:".$key.",";
                array_push($keys_str,$key);
            }else{
                $key=$key."=:".$key;
                array_push($keys_str,$key);
            }
            
        }
        $fields=implode("",$keys_str);
        $stmt = $db->prepare("UPDATE team SET ".$fields ." WHERE id_team = :id_team");
        foreach ($fields_values as $i=>$value) {
            $stmt->bindValue(':'.$keys[$i], $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':id_team', $id_team, PDO::PARAM_INT);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array("response"=>"mise à jour effectuée avec succès."));
    }else{
        http_response_code(400);
        echo json_encode(array("response"=>"pas de données à mettre à jour"));
        exit(1);
    }

    }else{
        http_response_code(400);
        echo json_encode(["response"=>"données incorrectes, veuillez entrer l'id de l'équipe"]);
        exit(1);
    }
            }

        }else{
            http_response_code(400);
            echo json_encode(["response"=>"veuillez vous authentifier"]);
            exit(1);
        }

    break;

    case 'DELETE':
        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if($decoded_data->email){
            $stmt=$db->prepare("SELECT * FROM user WHERE email=:email");
            $stmt->execute(["email"=>$decoded_data->email]);
            if($stmt->rowCount() > 0){
                if(isset($_GET["id_team"]) && !empty($_GET["id_team"])){
                    $id_team=$_GET["id_team"];
        
                    $stmt = $db->prepare("DELETE FROM team WHERE id_team=:id_team");
                    $stmt->bindValue(':id_team', $id_team, PDO::PARAM_INT);
                    $stmt->execute();
                    http_response_code(200);
                    echo json_encode(array("response"=>"suppression réussie"));
                }else{
                    http_response_code(400);
                    echo json_encode(array("response"=>"données incorrectes, veuillez entrer l'id de l'équipe"));
                    exit(1);
                }

            }

    }else{
        http_response_code(400);
        echo json_encode(["response"=>"veuillez vous authentifier"]);
        exit(1);
    }

        break;
    
    default:
      header("HTTP/1.0 405 Méthode non autorisée");
      break;
}

