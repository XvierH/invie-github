<?php
require_once("config.inc.php");

$msg = "";
$errores = array();

// 1. VALIDACION
if($_POST){

	$data = array();
	$data["nombres"] = filter_input(INPUT_POST, "nombres", FILTER_SANITIZE_STRING);
	$data["apellidos"] = filter_input(INPUT_POST, "apellidos", FILTER_SANITIZE_STRING);
	$data["nombre_usuario"] = filter_input(INPUT_POST, "nombre_usuario", FILTER_SANITIZE_STRING);
	$data["password"] = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

	foreach ($data as $nombreCampo => $valor) {
		if($valor === FALSE){
			$errores[$nombreCampo] = "El valor del campo no es valido";
		}
	}

	if(count($errores) == 0){
		$objBD = new BaseDatos();
		
		// 2. VALIDACION CONTRA BD
		$sqlValidar = "SELECT COUNT(1) AS Total FROM usuarios WHERE nombre_usuario = ?";
		$psValidar = $objBD->conn->prepare($sqlValidar);
		$psValidar->bindParam(1, $data["nombre_usuario"], PDO::PARAM_STR, 100);
		$psValidar->execute();
		$resValidacion = $psValidar->fetchAll();
		if($resValidacion[0]["Total"] >= 1){
			$errores["nombre_usuario"] = "El usuario ya existe";
		}else{
			try{
				// 3. INSERCION EN BD
				$sqlCrear = "INSERT INTO usuarios(nombres,apellidos,nombre_usuario, password) VALUES (?,?,?,?)";
				$psCrear = $objBD->conn->prepare($sqlCrear);
				$psCrear->bindParam(1, $data["nombres"], PDO::PARAM_STR, 50);
				$psCrear->bindParam(2, $data["apellidos"], PDO::PARAM_STR, 50);
				$psCrear->bindParam(3, $data["nombre_usuario"], PDO::PARAM_STR, 100);

				// cifrar la contrasena
				$data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);

				$psCrear->bindParam(4, $data["password"]);
				$psCrear->execute();
				$msg = "Usuario creado con exito";
			}catch(PDOException $e){
				$errores["general"] = "Hubo un error al crear: ".$e->getMessage();
			}

		}
	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Crear Usuario</title>
</head>
<body>

	<h1>Crear Usuario</h1>
	<h3><?=$msg?></h3>
	<ul>
		<?php foreach($errores as $campo => $err): ?>
		<li><?="Error campo $campo: $err"?></li>
		<?php endforeach;?>
	</ul>
	
	<form method="POST">
		<div>
			<label for="nombres">Nombres</label>
			<div>
				<input type="text" name="nombres" id="nombres">
			</div>
		</div>
		<div>
			<label for="apellidos">Apellidos</label>
			<div>
				<input type="text" name="apellidos" id="apellidos">
			</div>
		</div>
		<div>
			<label for="nombre_usuario">Nombre Usuario</label>
			<div>
				<input type="text" name="nombre_usuario" id="nombre_usuario">
			</div>
		</div>
		<div>
			<label for="password">Password</label>
			<div>
				<input type="password" name="password" id="password">
			</div>
		</div>
		<div>
			<button type="submit">Enviar</button>
		</div>
	</form>

</body>
</html>