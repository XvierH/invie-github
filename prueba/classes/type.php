<?php
require_once("../config.inc.php");

class Type{
	
	private $name;
	private $reference;

	public function __construct($name,$reference){
		$this->name = $name;
		$this->reference = $reference;
	}

	public function __get($nombreAttr){
		if(property_exists($this, $nombreAttr)){
			return $this->$nombreAttr;
		}
	}

	public static function insert_type(Type $type){
		$objBD = new BaseDatos();
		
		try{
			// 3. INSERCION EN BD
			$sqlCrear = "INSERT INTO type(name,reference) VALUES (?,?)";
			$psUpdate = $objBD->conn->prepare($sqlCrear);
			$psUpdate->bindParam(1, $type->name, PDO::PARAM_STR, 100);
			$psUpdate->bindParam(1, $type->reference, PDO::PARAM_STR, 100);
			$psUpdate->execute();
			return $msg = "Marca creado con exito";
		}catch(PDOException $e){
			return $msg = "Hubo un error al crear: ".$e->getMessage();
		}
	}

	public static function update_type(type $type){
		$validate = self::select_type();
		if (is_integer($validate)){
			try{
			// 3. INSERCION EN BD
				$sqlUpdate = "UPDATE  type SET (name,reference) VALUES (?,?) WHERE id = ?";
				$psUpdate = $objBD->conn->prepare($sqlCrear);
				$psUpdate->bindParam(1, $type->name, PDO::PARAM_STR, 100);
				$psUpdate->bindParam(2, $type->size, PDO::PARAM_STR, 100);
				$psUpdate->execute();
				return $msg = "Marca actualizado con exito";
			}catch(PDOException $e){
				return $msg = "Hubo un error al crear: ".$e->getMessage();
			}
		}else{
			return $msg = "No existe la Marca";
		}
	}

	public static function delete_type(type $type){
		$validate = self::select_type();
		if (is_integer($validate)){
			try{
			// 3. INSERCION EN BD
				$sqlUpdate = "DELETE FROM type WHERE id = ?";
				$psUpdate = $objBD->conn->prepare($sqlCrear);
				$psUpdate->bindParam(1, $validate, PDO::PARAM_STR, 100);
				$psUpdate->execute();
				return $msg = "Marca eliminado con exito";
			}catch(PDOException $e){
				return $msg = "Hubo un error al crear: ".$e->getMessage();
			}
		}else{
			return $msg = "No existe el Marca";
		}
	}

	public static function select_type($name){
		$objBD = new BaseDatos();
		
		// 2. VALIDACION CONTRA BD
		$sqlValidar = "SELECT id FROM type WHERE name = ?";
		$psValidar = $objBD->conn->prepare($sqlValidar);
		$psValidar->bindParam(1, $name, PDO::PARAM_STR, 100);
		$psValidar->execute();
		$resValidacion = $psValidar->fetchAll();
		if(!empty($resValidacion[0]["id"])){
			return $resValidacion[0]["id"];
		}else{
			return $mensaje = "dontexist";
		}
	}
}