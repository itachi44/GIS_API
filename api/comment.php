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
//TODO recupérer l'email du user dans le token verifier s'il est dans la base pour pouvoir faire la suppression, la mise à jour et le récupération de données user
    
function addComment($data){

    if(isset($data["comment_content"]) && isset($data["id_user"])){
        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if(!empty($data["id_user"])){
            $database = new Database();
            $db = $database->getConnexion();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $st=$db->prepare("SELECT * FROM user WHERE id_user=:id_user");
            $st->execute([
                "id_user"=>$data["id_user"]
            ]);
            if($st->rowCount()==0){
                http_response_code(400);
                echo json_encode(["response"=>"cet utilisateur n'existe pas!"]);
                exit(1);
    
            }
    }
            $comment=(object)[
                "id_user"=>$data["id_user"],
                "comment_content"=>$data["comment_content"]
            ];
            try{
               $stmt=$db->prepare("INSERT INTO comment(comment_content,id_user) VALUES(:comment_content,:id_user)");
               $stmt->bindValue(':comment_content', $comment->comment_content, PDO::PARAM_STR);
               $stmt->bindValue(':id_user', $comment->id_user, PDO::PARAM_INT);
               $stmt->execute();
               http_response_code(201);
               echo json_encode(array("response"=>"creation du commentaire réussie","comment"=>$comment),JSON_PRETTY_PRINT);
   
            }catch(PDOException $e){
               echo "Erreur : " . $e->getMessage();
           }


    }else{
        //TODO les données sont incorrects faire les controles et afficher l'erreur
        http_response_code(400);
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
                if(empty($_GET["id_comment"])){
                    $stmt=$db->prepare("SELECT * FROM comment");
                    $stmt->execute();
                    if($stmt->rowCount() > 0){
                        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        http_response_code(200);
                        echo json_encode(array("response"=>$comments),JSON_PRETTY_PRINT);
                    }else{
                        http_response_code(404);
                        echo json_encode(array("response"=>"pas de donnees"));
                        exit(1);
                    }
                    }else{
                        $id_comment=$_GET["id_comment"];
                        $stmt=$db->prepare("SELECT * FROM comment WHERE id_comment=:id_comment");
                        $stmt->execute(
                            ["id_comment"=>$id_comment]
                        );
                        if($stmt->rowCount()>0){
                            $comment = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo json_encode(array("response"=>$comment),JSON_PRETTY_PRINT);
                        }else{
                            http_response_code(404);
                            echo json_encode(array("response"=>"commentaire non trouvé"));
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
            addComment($data);
    }else{
            addComment($_POST);
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
            if(isset($_GET["id_comment"]) && !empty($_GET["id_comment"])){
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
        if(!empty($data["id_user"])){
            $database = new Database();
            $db = $database->getConnexion();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $st=$db->prepare("SELECT * FROM user WHERE id_user=:id_user");
            $st->execute([
                "id_user"=>$data["id_user"]
            ]);
            if($st->rowCount()==0){
                http_response_code(400);
                echo json_encode(["response"=>"cet utilisateur n'existe pas!"]);
                exit(1);
    
            }
    }
      $id_comment=$_GET["id_comment"];
      
      $cleaned_data=[];
      foreach($data as $key=>$value){
            $cleaned_data[$key]=htmlspecialchars($value);
      }

      //récupération de l'entité à mettre à jour

        $stmt=$db->prepare("SELECT * FROM comment WHERE id_comment=:id_comment");
        $stmt->bindValue(':id_comment',$id_comment, PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount()>0){
            $comment=$stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            http_response_code(404);
            echo json_encode(["response"=>"ce commentaire n'existe pas"]);
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
        $stmt = $db->prepare("UPDATE comment SET ".$fields ." WHERE id_comment = :id_comment");
        foreach ($fields_values as $i=>$value) {
            $stmt->bindValue(':'.$keys[$i], $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':id_comment', $id_comment, PDO::PARAM_INT);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array("response"=>"mise à jour effectuée avec succès."));
    }else{
        http_response_code(400);
        echo json_encode(["response"=>"pas de données à mettre à jour"]);
    }

    }else{
        http_response_code(400);
        echo json_encode(["response"=>"veuillez entrer l'id du commentaire à mettre à jour"]);
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
                if(isset($_GET["id_comment"]) && !empty($_GET["id_comment"])){
                    $id_comment=$_GET["id_comment"];
                    $stmt = $db->prepare("DELETE FROM comment WHERE id_comment=:id_comment");
                    $stmt->bindValue(':id_comment', $id_comment, PDO::PARAM_INT);
                    $stmt->execute();
                    http_response_code(200);
                    echo json_encode(array("response"=>"suppression réussie"));
                }else{
                    http_response_code(400);
                    echo json_encode(array("response"=>"données incorrectes, veuillez entrer l'id du commentaire"));
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

