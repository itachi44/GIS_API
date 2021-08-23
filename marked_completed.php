<?php

class Marked_inProgress{

	// Connexion
	private $connexion;
	private $table = "Marked_completed" ;

	// object properties

	public $longitude ;
	public $latitude ;
	public $district;


	public function __construct($db){
		$this->connexion = $db ;

	}


	public function getMarked(){
		$query = "SELECT * FROM ".$this->table ;
		$stmt = $this->connexion->prepare($query) ;
		$stmt->execute() ;
		return $stmt ;
	}

	

}


?>