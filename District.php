<?php

class District{

	// Connexion
	private $connexion;
	private $table = "centroids79districts" ;

	// object properties

	public $longitude ;
	public $latitude ;
	public $district_sanitaire ;

	public function __construct($db){
		$this->connexion = $db ;

	}


	public function getDistricts(){
		$query = "SELECT * FROM ".$this->table ;
		$stmt = $this->connexion->prepare($query) ;
		$stmt->execute() ;
		return $stmt ;
	}


	

}


?>