<?php

$msg = "";
$errores = array();

if($_POST){

	$data = array();
	$data["nameproduct"] = filter_input(INPUT_POST, "nameproduct", FILTER_SANITIZE_STRING);
	$data["size"] = filter_input(INPUT_POST, "size", FILTER_SANITIZE_STRING);
	$data["obs"] = filter_input(INPUT_POST, "obs", FILTER_SANITIZE_STRING);
	$data["type"] = filter_input(INPUT_POST, "type", FILTER_SANITIZE_STRING);
	$data["quantity"] = filter_input(INPUT_POST, "quantity", FILTER_SANITIZE_NUMBER_INT);
	$data["date"] = filter_input(INPUT_POST, "date", FILTER_UNSAFE_RAW);
	$data["create"] = filter_input(INPUT_POST, "create", FILTER_UNSAFE_RAW);
	$data["edit"] = filter_input(INPUT_POST, "edit", FILTER_UNSAFE_RAW);

	foreach ($data as $nombreCampo => $valor) {
		if($valor === FALSE){
			$errores[$nombreCampo] = "El valor del campo no es valido";
		}
	}
	if ($data['create'] == 1){

		$product = new Product($data['nameproduct'],$data['size'],$data['obs'],$data['type'],$data['quantity'],$data['date']);
		$mensaje = Product::insert_product($product); 

	}
	if ($data['edit'] == 1){
		$product = new Product($data['nameproduct'],$data['size'],$data['obs'],$data['type'],$data['quantity'],$data['date']);
		$mensaje = Product::edit_product($product);
	}

}


?>