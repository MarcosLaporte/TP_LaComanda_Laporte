<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include_once __DIR__ . "\..\models\Usuario.php";
include_once __DIR__ . "\..\models\AutentificadorJWT.php";
include_once __DIR__ . "\..\db\AccesoDatos.php";
include_once __DIR__ . "\..\interfaces\IPdo.php";

class UsuarioController extends Usuario implements IPdo
{
	public static function Add(Request $request, Response $response, array $args)
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

	public static function GetAll(Request $request, Response $response, array $args)
	{
		$users = Usuario::TraerTodos();

		$payload = json_encode(array("list" => $users));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function GetOne(Request $request, Response $response, array $args)
	{
		$idUser = $args['id'];
		$usuario = Usuario::TraerPorId($idUser);

		$payload = json_encode(array("user" => $usuario));
		$response->getBody()->write($payload);

		return $response->withHeader('Content-Type', 'application/json');
	}

	public static function Delete(Request $request, Response $response, array $args)
	{

	}

	public static function Modify(Request $request, Response $response, array $args)
	{

	}

	public static function Login(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();

		if (isset($params['id']) && isset($params['usuario']) && isset($params['clave'])) {
			$usuario = Usuario::TraerPorId($params['id']);
			if (!empty($usuario)) {
				if (
					!strcasecmp($params['usuario'], $usuario[0]->usuario)
					&& password_verify($params['clave'], $usuario[0]->clave)
				) {
					$payload = json_encode(array('msg' => "OK", 'rol' => $usuario[0]->rol));

					$jwt = AutentificadorJWT::CrearToken(array('id' => $usuario[0]->id, 'rol' => $usuario[0]->rol, 'fecha' => date('Y-m-d'), 'hora' => date('H:i:s')));
					setcookie("token", $jwt, time() + 1800, '/', "localhost", false, true);
					self::AlmacenarLog($jwt);
				} else {
					//Borra cookie existente
					setcookie("token", " ", time() - 3600, "/", "localhost", false, true);
					$payload = json_encode(array('msg' => "Los datos del usuario #{$params['id']} no coinciden."));
				}
			} else {
				$payload = json_encode(array('msg' => "No existe un usuario con ese id."));
			}
		} else {
			$response->getBody()->write(json_encode(array("msg" => "Ingrese los datos para el login!")));
		}

		$response->getBody()->write($payload);
		return $response->withHeader('Content-Type', 'application/json');
	}

	private static function AlmacenarLog($jwt)
	{
		try {
			AutentificadorJWT::VerificarToken($jwt);
			$data = AutentificadorJWT::ObtenerData($jwt);
			$objAccesoDatos = AccesoDatos::ObtenerInstancia();
			$req = $objAccesoDatos->PrepararConsulta("INSERT INTO logs (idUser, fecha, hora) VALUES (:idUser, :fecha, :hora)");
			$req->bindValue(':idUser', $data->id, PDO::PARAM_INT);
			$req->bindValue(':fecha', $data->fecha, PDO::PARAM_STR);
			$req->bindValue(':hora', $data->hora, PDO::PARAM_STR);
			$req->execute();
		} catch (Exception $ex) {
			throw new Exception("Error al almacenar el log.");
		}
	}
}