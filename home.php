<?php
//session_start();
// si la var de sesion no existe, no se autenticò el usuario
if(!isset($_SESSION["nombre_usuario"])){
	header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home Tienda</title>
</head>
<body>
	<h1>Home Tienda</h1>
	<h5>Hola, <?=$_SESSION["nombre_completo"]?></h5>
	<a href="cerrar_sesion.php">Cerrar Sesión</a>
</body>
</html>