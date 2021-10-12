<?php
class Database
{
	// Connexion à la base de données
	private $host = "localhost:8889";
	private $db_name = "Districts";
	private $username = "root";
	private $password =  "root";
	public $connexion;


	public function getConnexion()
	{
		$this->connexion = null;

		try {
			$this->connexion = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->connexion->exec("set names utf8");
		} catch (PDOException $exception) {
			echo "Erreur de connexion : " . $exception->getMessage();
		}

		return $this->connexion;
	}
}
