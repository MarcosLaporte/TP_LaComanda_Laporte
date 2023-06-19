<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
include_once __DIR__ . "/../models/Usuario.php";

class UsuarioController extends Usuario
{
	public static function CargarUsuario(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();

		$usuario = new Usuario();
		$usuario->usuario = $params['usuario'];
		$usuario->clave = $params['clave'];
		$usuario->rol = $params['rol'];
		$usuario->CrearUsuario();

		$payload = json_encode(array("msg" => "Usuario creado con exito"));
	    $response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function TraerUsuarios(Request $request, Response $response, array $args)
	{
		$users = Usuario::TraerTodos();
		
		$payload = json_encode(array("list" => $users));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}
}