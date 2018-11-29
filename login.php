<?php
session_start();
require_once("config.inc.php");
$msg = "";
$errores = array();

if($_POST){

	$data = array();
	$data["nombre_usuario"] = filter_input(INPUT_POST, "nombre_usuario", FILTER_SANITIZE_STRING);
	$data["password"] = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

	foreach ($data as $nombreCampo => $valor) {
		if($valor === FALSE){
			$errores[$nombreCampo] = "El valor del campo no es valido";
		}
	}

	if(count($errores) == 0){
		$objBD = new BaseDatos();

		try{
			// consultar la tabla de usuarios, trayendo el hash almacenado previamente
			$sqlConsultarUsu = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
			$psConsultarUsu = $objBD->conn->prepare($sqlConsultarUsu);
			$psConsultarUsu->bindParam(1, $data["nombre_usuario"], PDO::PARAM_STR, 100);
			$psConsultarUsu->execute();
			$res = $psConsultarUsu->fetchAll();
			if(count($res) == 0){
				$errores["nombre_usuario"] = "El nombre usuario o contrasena no son validos";
			}else{
				$hashPw = $res[0]["password"];
				// verificar que el hash almacenado y la contrasena sean validos
				if(password_verify($data["password"], $hashPw)){
					// actualizar el hash de la contrasena
					if(password_needs_rehash($hashPw, PASSWORD_DEFAULT)){
						$sqlActualizarPw = "UPDATE usuarios SET password = ? WHERE nombre_usuario = ?";
						$psActualizarPw = $objBD->conn->prepare($sqlActualizarPw);
						$data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
						$psActualizarPw->bindParam(1, $data["password"]);
						$psActualizarPw->bindParam(2, $data["nombre_usuario"]);
						$psActualizarPw->execute();
					}
					// guardar la var sesion
					$_SESSION["nombre_usuario"] = $data["nombre_usuario"];
					$_SESSION["nombre_completo"] = $res[0]["nombres"]." ".$res[0]["apellidos"];
					// redireccionar a la pagina de usuario autenticado
					header("Location: home.php");
					//$msg = "Usuario autenticado";
				}else{
					$errores["nombre_usuario"] = "El nombre usuario o contrasena no son validos";
				}
			}
		}catch(PDOException $e){
			$errores["general"] = "Hubo un error al consultar: ".$e->getMessage();
		}
		

	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Acceso a Tienda</title>
</head>
<body>
	<h1>Login - Tienda</h1>
	<?=$msg?>
	<ul>
		<?php foreach ($errores as $campo => $valor) :?>
			<li><?="Hubo error en $campo: $valor"?></li>
		<?php endforeach; ?>
	</ul>
	<form method="POST">
		<div>
			<label for="nombre_usuario">Nombre Usuario</label>
			<input type="text" name="nombre_usuario" id="nombre_usuario">
		</div>
		<div>
			<label for="password">Password</label>
			<input type="password" name="password" id="password">
		</div>
		<div>
			<button type="submit">Ingresar</button>
		</div>
	</form>
</body>
</html>