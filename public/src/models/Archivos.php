<?php

class Archivo
{
	public static function GuardarImagenDePeticion($directorio, $nuevoNombre, $imageKey = 'imagen')
	{
		if (!is_dir($directorio))
			mkdir($directorio, 0777, true);

		if (!isset($_FILES[$imageKey]))
			return "N/A";

		$tmpName = $_FILES[$imageKey]["tmp_name"];
		$destino = $directorio . $nuevoNombre . '.jpg';

		if (move_uploaded_file($tmpName, $destino))
			return $destino;
		else
			return "N/A";
	}

	public static function MoverImagen($origen, $destino, $archivo)
	{
		if (!is_dir($destino))
			mkdir($destino, 0777, true);

		return rename($origen . $archivo, $destino . $archivo);
	}
}