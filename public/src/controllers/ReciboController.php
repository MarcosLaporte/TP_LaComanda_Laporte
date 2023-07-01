<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include_once(__DIR__ . "\..\models\Recibo.php");
include_once(__DIR__ . "\..\models\Pedido.php");
include_once(__DIR__ . "\..\models\Mesa.php");

class ReciboController extends Recibo
{
	public function Add(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();
		$pedido = Pedido::TraerPorId($params['idPedido'])[0];

		$recibo = new Recibo();
		$recibo->fecha = $pedido->fecha;
		$recibo->idPedido = $params['idPedido'];
		$recibo->cliente = $params['cliente'];
		$recibo->formaDePago = $params['formaDePago'];
		$recibo->importe = $pedido->precio;
		$numeroRecibo = $recibo->CrearRecibo();

		Mesa::ModificarEstado($pedido->idMesa, MESA_PAGANDO);
		self::ShowPDF($numeroRecibo);
		return $response->withHeader('Content-Type', 'application/pdf');
	}

	public static function Pay(Request $request, Response $response, array $args)
	{
		$params = $request->getParsedBody();
		$recibo = Recibo::TraerPorId($params['numeroRecibo'])[0];
		$pedido = Pedido::TraerPorId($recibo->idPedido)[0];

		Pedido::PedidoPago($pedido->id);
		$cookieMesa = "Mesa" . $pedido->idMesa;
		setcookie($cookieMesa, " ", time() - 3600, "/", "localhost", false, true);
		$payload = json_encode(array("msg" => "Cuenta pagada. Muchas gracias!"));
		$response->getBody()->write($payload);

		Mesa::ModificarEstado($pedido->idMesa, MESA_VACIA);
		self::DownloadPDF($params['numeroRecibo']);
		return $response->withHeader('Content-Type', 'application/json');
	}

	private static function ShowPDF($numeroRecibo)
	{
		$recibo = Recibo::TraerPorId($numeroRecibo)[0];
		$objPdf = $recibo->CrearPdf();
		$objPdf->Output();
	}

	private static function DownloadPDF($numeroRecibo)
	{
		$recibo = Recibo::TraerPorId($numeroRecibo)[0];
		$objPdf = $recibo->CrearPdf();
		$nombreArchivo = "recibo_" . $recibo->numero . ".pdf";
		$objPdf->Output("D", $nombreArchivo);
	}
}