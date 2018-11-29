<?php

$msg = "";
$errores = array();

if($_POST){

	$data = array();
	$data["nametype"] = filter_input(INPUT_POST, "nametype", FILTER_SANITIZE_STRING);
	$data["reference"] = filter_input(INPUT_POST, "reference", FILTER_SANITIZE_NUMBER_INT);

	foreach ($data as $nombreCampo => $valor) {
		if($valor === FALSE){
			$errores[$nombreCampo] = "El valor del campo no es valido";
		}
	}
	if ($data['create'] == 1){

		$product = new Product($data['nametype'],$data['reference']);
		$mensaje = Product::insert_product($product); 

	}
	if ($data['edit'] == 1){
		$product = new Product($data['nametype'],$data['reference']);
		$mensaje = Product::edit_product($product);
	}

}


?>