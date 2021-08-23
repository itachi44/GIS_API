<?php

    include_once 'Databases.php' ;
    include_once 'District.php' ;
    $database = new Database() ;
	$db = $database->getConnexion() ;
    $district = new District($db);

    if(isset($_POST['latitude']) && isset($_POST['longitude']) ){
      $lat=$_POST['latitude'];
      $lng=$_POST['longitude'];
        try {
            $stmt = $db->prepare("DELETE FROM Marked_inProgress WHERE latitude= :lat AND longitude=:lng");
            $stmt->execute([':lat' => $lat,':lng' =>$lng]);
            } catch(Exception $e) {
            echo $e;
        } 
       
        }

        else{
          echo "error";
        }


    
    

?>
 