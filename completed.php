<?php

    include_once 'Databases.php' ;
    include_once 'District.php' ;
    $database = new Database() ;
	$db = $database->getConnexion() ;
    $district = new District($db);

    if(isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST["district"])){
      $lat=$_POST['latitude'];
      $lng=$_POST['longitude'];
      $district=$_POST["district"];
        try {
            $stmt = $db->prepare("INSERT INTO Marked_completed(latitude,longitude,district) VALUES (:lat,:lng,:dist)");
            $stmt->execute([':lat' => $lat,':lng' =>$lng,':dist'=>$district]);
            } catch(Exception $e) {
            echo $e;
        } 
       
        }
        else{
          echo "error";
        }
?>
 