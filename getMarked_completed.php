<?php
header("Acces-Control-Allow-Origin: *") ;
header("Content-Type: application/json; charset=UTF-8");
if($_SERVER['REQUEST_METHOD'] == 'GET') {

	include_once 'Databases.php' ;
	include_once 'marked_completed.php' ;

	$database = new Database() ;
	$db = $database->getConnexion() ; 


	$marked = new Marked_inProgress($db) ;

    $stmt = $marked->getMarked() ;

	if($stmt->rowCount() > 0){
		$tableauMarked = [] ;
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row) ;

			$mark = [
                "latitude" => $latitude,
				"longitude" => $longitude,
				"district"=> $district
			];

			$tableauMarked['marked_locations'][] = $mark;
		}
		http_response_code(200);

		//On encode en json et on envoie
		echo json_encode($tableauMarked);

	} else {

		$mark = [
			"latitude" => 0,
			"longitude" => 0,
			"district"  => null
		];
		$tableauMarked['marked_locations'][] = $mark;

		echo json_encode($tableauMarked);
	}


}


?>


