<?php

function LeerArchivoSELECT($seleccion) {
	
	$lineas = file("areas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lineas as $linea)
		if (strtolower ($linea) == strtolower ($seleccion))
			echo "<option value=\"{$linea}\" selected>{$linea}</option>\n";
		else
			echo "<option value=\"{$linea}\">{$linea}</option>\n";
}

function EjecutarSQL ($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL) {

	$conexion = mysqli_connect($servidor, $usuario, $contrasena, $basedatos);
	if (!$conexion) {
    	die("Fallo: " . mysqli_connect_error());
	}

	$resultado = mysqli_query($conexion, $sentenciaSQL);
	mysqli_close($conexion);

}

function ConsultarSQL ($servidor, $usuario, $contrasena, $basedatos, $sentenciaSQL) {

	$conexion = mysqli_connect($servidor, $usuario, $contrasena, $basedatos);
	if (!$conexion) {
    	die("Fallo: " . mysqli_connect_error());
	}

	$resultado = mysqli_query($conexion, $sentenciaSQL);
	
	for ($registros = array (); $fila = mysqli_fetch_assoc($resultado); $registros[] = $fila);	
	
	mysqli_close($conexion);
	
	return $registros;

}


?>