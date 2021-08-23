<?php
header("Acces-Control-Allow-Origin: *") ;
header("Content-Type: application/json; charset=UTF-8");
if($_SERVER['REQUEST_METHOD'] == 'GET') {

	include_once 'Databases.php' ;
	include_once 'District.php' ;

	$database = new Database() ;
	$db = $database->getConnexion() ; 


	$district = new District($db) ;

	$stmt = $district->getDistricts() ;

	if($stmt->rowCount() > 0){
		$tableauDistrict = [] ;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row) ;

			$dist = [
				"longitude" => $longitude,
				"latitude" => $latitude,
				"district_sanitaire" => $district_sanitaire,
			];

			$tableauDistrict['centroids79districts'][] = $dist;
		}
		http_response_code(200);

		//On encode en json et on envoie
		echo json_encode($tableauDistrict);

	} else {

		// handle the error
		http_response_code(405);
		echo json_encode(["message" => "La methode n'est pas autorisée"]) ;
	}


}


?>