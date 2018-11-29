<?php
require_once("../config.inc.php");

class Product{
	
	private $name;
	private $size;
	private $obs;
	private $type;
	private $quantity;
	private $date;

	public function __construct($name,$size,$obs,$type,$quantity,$date){
		$this->name = $name;
		$this->size = $size;
		$this->obs = $obs;
		$this->type = $type;
		$this->quantity = $quantity;
		$this->date = $date;
	}

	public function __get($nombreAttr){
		if(property_exists($this, $nombreAttr)){
			return $this->$nombreAttr;
		}
	}

	public static function insert_product(Product $product){
		$objBD = new BaseDatos();
		
		try{
			// 3. INSERCION EN BD
			$sqlCrear = "INSERT INTO product(nameproduct,size,obs,type,quantity,date_ship) VALUES (?,?,?,?,?,?)";
			$psUpdate = $objBD->conn->prepare($sqlCrear);
			$psUpdate->bindParam(1, $product->name, PDO::PARAM_STR, 100);
			$psUpdate->bindParam(2, $product->size, PDO::PARAM_STR, 100);
			$psUpdate->bindParam(3, $product->obs, PDO::PARAM_STR, 100);
			$psUpdate->bindParam(4, $product->type, PDO::PARAM_STR, 100);
			$psUpdate->bindParam(5, $product->quantity, PDO::PARAM_STR, 100);
			$psUpdate->bindParam(6, $product->date, PDO::PARAM_STR, 100);
			$psUpdate->execute();
			return $msg = "Producto creado con exito";
		}catch(PDOException $e){
			return $msg = "Hubo un error al crear: ".$e->getMessage();
		}
	}

	public static function update_product(Product $product){
		$validate = self::select_product($product->name);
		if (is_integer($validate)){
			try{
			// 3. INSERCION EN BD
				$sqlUpdate = "UPDATE  product SET (nameproduct,size,obs,type,quantity,date_ship) VALUES (?,?,?,?,?,?) WHERE id = ?";
				$psUpdate = $objBD->conn->prepare($sqlCrear);
				$psUpdate->bindParam(1, $product->name, PDO::PARAM_STR, 100);
				$psUpdate->bindParam(2, $product->size, PDO::PARAM_STR, 100);
				$psUpdate->bindParam(3, $product->obs, PDO::PARAM_STR, 100);
				$psUpdate->bindParam(4, $product->type, PDO::PARAM_STR, 100);
				$psUpdate->bindParam(5, $product->quantity, PDO::PARAM_STR, 100);
				$psUpdate->bindParam(6, $product->date, PDO::PARAM_STR, 100);
				$psUpdate->bindParam(7, $validate, PDO::PARAM_STR, 100);
				$psUpdate->execute();
				return $msg = "Producto actualizado con exito";
			}catch(PDOException $e){
				return $msg = "Hubo un error al crear: ".$e->getMessage();
			}
		}else{
			return $msg = "No existe el producto";
		}
	}

	public static function delete_product(Product $product){
		$validate = self::select_product();
		if (is_integer($validate)){
			try{
			// 3. INSERCION EN BD
				$sqlUpdate = "DELETE FROM product WHERE id = ?";
				$psUpdate = $objBD->conn->prepare($sqlCrear);
				$psUpdate->bindParam(1, $validate, PDO::PARAM_STR, 100);
				$psUpdate->execute();
				return $msg = "Producto eliminado con exito";
			}catch(PDOException $e){
				return $msg = "Hubo un error al crear: ".$e->getMessage();
			}
		}else{
			return $msg = "No existe el producto";
		}
	}

	public static function select_product($name){
		$objBD = new BaseDatos();
		
		// 2. VALIDACION CONTRA BD
		$sqlValidar = "SELECT id FROM product WHERE nameproduct = ?";
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