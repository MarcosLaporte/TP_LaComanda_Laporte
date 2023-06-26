<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
interface IPdo
{
	public static function GetAll(Request $request, Response $response, array $args);
	public static function GetOne(Request $request, Response $response, array $args);
	public static function Add(Request $request, Response $response, array $args);
	public static function Delete(Request $request, Response $response, array $args);
	public static function Modify(Request $request, Response $response, array $args);
}