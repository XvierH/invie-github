<?php

class BaseDatos{
	
	private $conn;

	public function __construct(){
		try{
			global $paramBd;
			$dsn = "mysql:host=".$paramBd['host'].";dbname=".$paramBd['dbname'];
			$this->conn = new PDO($dsn, $paramBd["username"], $paramBd["password"]);
			$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $ex){
			echo "Hubo un error de conexion a BD";
			print_r($ex->getMessage());
			exit;
		}

	}

	public function __get($nombreAttr){
		if(property_exists($this, $nombreAttr)){
			return $this->$nombreAttr;
		}
	}

}