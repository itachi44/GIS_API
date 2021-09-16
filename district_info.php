<?php
 header("Acces-Control-Allow-Origin: *") ;
 header("Content-Type: application/json; charset=UTF-8");
 include_once 'Databases.php' ;

 $_POST = json_decode(file_get_contents("php://input"), true);	
     if(isset($_POST["district"])){
     $district_name = strtoupper($_POST["district"]);
    $database = new Database() ;
	$db = $database->getConnexion() ;  

    $query = "SELECT latitude,longitude FROM centroids79districts WHERE district_sanitaire LIKE ".$district_name ;
    $stmt = $db->prepare($query) ;
    $stmt->execute();

	if($stmt->rowCount() > 0){
		$tableauDistrict = [] ;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row) ;

			$dist = [
				"longitude" => $longitude,
				"latitude" => $latitude,
			];

			$tableauDistrict['coordinates'][] = $dist;
		}
		http_response_code(200);

		//On encode en json et on envoie
		echo json_encode($tableauDistrict);

	} else {

		$mark = [
			"latitude" => 0,
			"longitude" => 0,
		];
		$tableauMarked['coordinates'][] = $mark;

		echo json_encode($tableauMarked);
	}

 }