<?php

class Encuesta
{
	public $puntMesa;
	public $puntResto;
	public $puntMozo;
	public $puntCocina;
	public $comentarios;

	public function CrearEncuesta()
	{
		$objAccesoDatos = AccesoDatos::ObtenerInstancia();
		$req = $objAccesoDatos->PrepararConsulta("INSERT INTO encuestas (id, mesa, restaurante, mozo, cocina, comentarios) VALUES (:mesa, :restaurante, :mozo, :cocina, :comentarios)");
		$req->bindValue(':mesa', $this->puntMesa, PDO::PARAM_INT);
		$req->bindValue(':restaurante', $this->puntResto, PDO::PARAM_INT);
		$req->bindValue(':mozo', $this->puntMozo, PDO::PARAM_INT);
		$req->bindValue(':cocina', $this->puntCocina, PDO::PARAM_INT);
		$req->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
		$req->execute();

	}
}