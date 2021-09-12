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

// id_user et id_centroid seront récupérés à la connexion de l'utilisateur et seront stocker dans le client pour être envoyé dans la requête
// MCD_name, MCD_tel, used_range, received_sample, tested_sample, non-conforming_sample seront envoyées par le client après avoir fait le fetching depuis une api externe
function addReport($data){

    if(isset($data["id_user"]) && isset($data["id_centroid"]) && isset($data["allocated_range"])  && isset($data["date"]) 
    && isset($data["used_range"]) && isset($data["received_sample"]) && isset($data["tested_sample"]) && isset($data["non-conforming_sample"])){
        $mcd_name="";
        $mcd_tel="";
        $starting_time="";
        $ending_time="";
        $comment="";
        //TODO traiter les données : allocated range, date etc
        $date=new \DateTime($data["date"]);

        //données secondaires
        if(isset($data["MCD_name"])){
            $mcd_name=$data["MCD_name"];
        }
        if(isset($data["MCD_tel"])){
            $mcd_tel=$data["MCD_tel"];
        }
        if(isset($data["starting_time"])){
            $starting_time=$data["starting_time"];
        }
        if(isset($data["ending_time"])){
            $ending_time=$data["ending_time"];
        }
        if(isset($data["comment"])){
            $comment=$data["comment"];
        }

        $database = new Database();
        $db = $database->getConnexion();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $context=[
            "MCD_name"=>$mcd_name,
            "MCD_tel"=>$mcd_tel,
            "allocated_range"=>$data["allocated_range"],
            "starting_time"=>$starting_time,
            "ending_time"=>$ending_time,
            "comment"=>$comment,
            "used_range"=>$data["used_range"],
            "received_sample"=>$data["received_sample"],
            "tested_sample"=>$data["tested_sample"],
            "non_conforming_sample"=>$data["non-conforming_sample"],
            "id_user"=>$data["id_user"],
            "id_centroid"=>$data["id_centroid"],
            "date"=>$date
        ];
            try{
               $stmt=$db->prepare("INSERT INTO district_data(MCD_name,MCD_tel,allocated_range,starting_time,ending_time,comment,used_range,received_sample,tested_sample,non_conforming_sample,id_user,id_centroid,date) 
               VALUES(:MCD_name,:MCD_tel,:allocated_range,:starting_time,:ending_time,:comment,:used_range,:received_sample,:tested_sample,:non_conforming_sample,:id_user,:id_centroid,:date)");
               $stmt->execute($context);
               http_response_code(201);
               echo json_encode(array("response"=>"creation du rapport réussie"),JSON_PRETTY_PRINT);
   
            }catch(PDOException $e){
               echo "Erreur : " . $e->getMessage();
           }


    }else{
        if(empty($data["id_user"])) array_push($missing_fields,"id_user");
        if(empty($data["id_centroid"])) array_push($missing_fields,"id_centroid");
        if(empty($data["allocated_range"])) array_push($missing_fields,"allocated_range");
        if(empty($data["date"])) array_push($missing_fields,"date");
        if(empty($data["used_range"])) array_push($missing_fields,"used_range");
        if(empty($data["received_sample"])) array_push($missing_fields,"received_sample");
        if(empty($data["tested_sample"])) array_push($missing_fields,"tested_sample");
        if(empty($data["non-conforming_sample"])) array_push($missing_fields,"non-conforming_sample");
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
                if(empty($_GET["id_district_data"])){
                    $stmt=$db->prepare("SELECT * FROM district_data");
                    $stmt->execute();
                    if($stmt->rowCount() > 0){
                        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        http_response_code(200);
                        echo json_encode(array("response"=>$reports),JSON_PRETTY_PRINT);
                    }else{
                        http_response_code(404);
                        echo json_encode(array("response"=>"pas de donnees"));
                        exit(1);
                    }
                    }else{
                        $id_district_data=$_GET["id_district_data"];
                        $stmt=$db->prepare("SELECT * FROM district_data WHERE id_district_data=:id_district_data");
                        $stmt->execute(
                            ["id_district_data"=>$id_district_data]
                        );
                        if($stmt->rowCount()>0){
                            $report = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo json_encode(array("response"=>$report),JSON_PRETTY_PRINT);
                        }else{
                            http_response_code(404);
                            echo json_encode(array("response"=>"rapport non trouvé"));
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
            addReport($data);
    }else{
            addReport($_POST);
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
                            if(isset($_GET["id_district_data"]) && !empty($_GET["id_district_data"])){
            //récupération des données
            if(json_decode(file_get_contents("php://input"))){
                    $data=json_decode(file_get_contents("php://input"),True);
                    if(array_key_exists(0,$data)){
                          $data=$data[0];
                    }
            }else{
                    $data=$_POST;
            }


      $id_district_data=$_GET["id_district_data"];
      
      $cleaned_data=[];
      foreach($data as $key=>$value){
            $cleaned_data[$key]=htmlspecialchars($value);
      }

      //récupération de l'entité à mettre à jour

        $stmt=$db->prepare("SELECT * FROM district_data WHERE id_district_data=:id_report");
        $stmt->bindValue(':id_report',$id_district_data, PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount()>0){
            $report=$stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            http_response_code(404);
            echo json_encode(["response"=>"ce rapport n'existe pas"]);
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
        $stmt = $db->prepare("UPDATE district_data SET ".$fields ." WHERE id_district_data = :id_report");
        foreach ($fields_values as $i=>$value) {
            $stmt->bindValue(':'.$keys[$i], $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':id_report', $id_district_data, PDO::PARAM_INT);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array("response"=>"mise à jour effectuée avec succès."));

    }else{
        http_response_code(400);
        echo json_encode(["response"=>"autorisation non accordée à cet utilisateur"]);
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
                if(isset($_GET["id_district_data"]) && !empty($_GET["id_district_data"])){
                    $id_report=$_GET["id_district_data"];
        
                    $stmt = $db->prepare("DELETE FROM district_data WHERE id_district_data=:id_report");
                    $stmt->bindValue(':id_report', $id_report, PDO::PARAM_INT);
                    $stmt->execute();
                    http_response_code(200);
                    echo json_encode(array("response"=>"suppression réussie"));
                }else{
                    http_response_code(400);
                    echo json_encode(array("response"=>"données incorrectes, veuillez entrer l'id du rapport"));
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

