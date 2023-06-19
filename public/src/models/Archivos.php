<?php

class Archivo
{
	public static function GuardarJson($path, $arrayDatos)
	{
		$refArchivo = fopen($path, "w");
		if ($refArchivo) {
			$datosJson = json_encode($arrayDatos);
			fputs($refArchivo, $datosJson);
			return fclose($refArchivo);
		}

		return false;
	}

	public static function LeerJson($path)
	{
		$arrayDatos = array();
		if (file_exists($path)) {
			$refArchivo = fopen($path, "r");
			$datosJson = file_get_contents($path);
			if ($datosJson)
				$arrayDatos = json_decode($datosJson);

			fclose($refArchivo);
		} else {
			echo "El archivo '$path' no existe.<br>";
		}

		return $arrayDatos;
	}

	public static function GuardarImagenDePeticion($directorio, $nuevoNombre, $imageKey = 'imagen')
	{
		if (!is_dir($directorio))
			mkdir($directorio, 0777, true);

		$tmpName = $_FILES[$imageKey]["tmp_name"];
		$destino = $directorio . $nuevoNombre . '.jpg';

		return move_uploaded_file($tmpName, $destino);
	}

	public static function MoverImagen($origen, $destino, $archivo)
	{
		if (!is_dir($destino))
			mkdir($destino, 0777, true);

		return rename($origen . $archivo, $destino . $archivo);

	}
}