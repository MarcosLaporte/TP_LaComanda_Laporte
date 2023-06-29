CREATE DATABASE IF NOT EXISTS comanda_laporte;
USE comanda_laporte;

/*-----------------------------------------------*/
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios(
	id INT PRIMARY KEY AUTO_INCREMENT,
	usuario VARCHAR(50),
	clave VARCHAR(250),
	rol VARCHAR(13),
	CONSTRAINT `rol_check` CHECK (
		rol IN ('bartender', 'cervecero', 'cocinero', 'mozo', 'socio')
	)
) AUTO_INCREMENT = 101;

INSERT INTO usuarios(usuario, clave, rol) VALUES
	('mozo1', '$2y$10$.RLc7btiUb4zFHku4QV8ZuM0AQK9oMI7EdRqcQDzAFF56t0W7yNY2', 'mozo'),
	('cocinero1', '$2y$10$.RLc7btiUb4zFHku4QV8ZuM0AQK9oMI7EdRqcQDzAFF56t0W7yNY2', 'cocinero'),
	('socio1', '$2y$10$.RLc7btiUb4zFHku4QV8ZuM0AQK9oMI7EdRqcQDzAFF56t0W7yNY2', 'socio');
/*-----------------------------------------------*/
DROP TABLE IF EXISTS mesas;
CREATE TABLE mesas(
	id INT(5) PRIMARY KEY AUTO_INCREMENT,
	estado INT,
	CONSTRAINT `estadoMesa_check` CHECK (estado BETWEEN 0 AND 4)
	) AUTO_INCREMENT = 1;
	
INSERT INTO mesas(estado) VALUES (0),(0),(0),(0),(0);
/*-----------------------------------------------*/
DROP TABLE IF EXISTS productos;
CREATE TABLE productos(
	id INT PRIMARY KEY AUTO_INCREMENT,
	sector INT,
	descripcion VARCHAR(250),
	precio FLOAT,
	CONSTRAINT `sector_check` CHECK (sector BETWEEN 1 AND 4),
	CONSTRAINT `precio_check` CHECK (precio > 0)
	) AUTO_INCREMENT = 101;
	
INSERT INTO productos(sector, descripcion, precio) VALUES 
	(2, 'Amstel Lager 750ml', 1449.99),
	(3, 'Pastel de papa', 1699.5),
	(3, 'Pollo y papas con crema al verdeo', 2050),
	(4, 'Tiramisu de dulce de leche', 1250);
/*-----------------------------------------------*/
DROP TABLE IF EXISTS pedidos;
CREATE TABLE pedidos(
	id CHAR(5) PRIMARY KEY,
	idMesa INT,
	estado INT,
	precio FLOAT,
	minutos INT,
	foto VARCHAR(250),
	CONSTRAINT `estadoPed_check` CHECK (estado IN (0, 1)),
	CONSTRAINT `precioPed_check` CHECK (precio > 0),
	CONSTRAINT `idMesaPedFK` FOREIGN KEY (idMesa) REFERENCES mesas (id)
	);
/*-----------------------------------------------*/
DROP TABLE IF EXISTS productos_pedidos;
CREATE TABLE productos_pedidos(
	idProducto INT,
	idMesa INT,
	idPedido CHAR(5),
	estado INT,
	minutos INT,
	cliente VARCHAR(250),
	CONSTRAINT `estadoProdPed_check` CHECK (estado IN (0, 1)),
	CONSTRAINT `idProductoProdPedFK` FOREIGN KEY (idProducto) REFERENCES productos (id),
	CONSTRAINT `idMesaProdPedFK` FOREIGN KEY (idMesa) REFERENCES mesas (id),
	CONSTRAINT `idPedidoProdPedFK` FOREIGN KEY (idPedido) REFERENCES pedidos (id)
	);
/*-----------------------------------------------*/
DROP TABLE IF EXISTS encuestas;
CREATE TABLE encuestas(
	id INT PRIMARY KEY AUTO_INCREMENT,
	idMesa INT,
	idPedido CHAR(5),
	puntMesa INT,
	puntRestaurante INT,
	puntMozo INT,
	puntCocina INT,
	comentarios VARCHAR(66),
	CONSTRAINT `puntMesa_check` CHECK (puntMesa BETWEEN 1 AND 10),
	CONSTRAINT `puntRestaurante_check` CHECK (puntRestaurante BETWEEN 1 AND 10),
	CONSTRAINT `puntMozo_check` CHECK (puntMozo BETWEEN 1 AND 10),
	CONSTRAINT `puntCocina_check` CHECK (puntCocina BETWEEN 1 AND 10),
	CONSTRAINT `idMesaEncFK` FOREIGN KEY (idMesa) REFERENCES mesas (id),
	CONSTRAINT `idPedidoEncFK` FOREIGN KEY (idPedido) REFERENCES pedidos (id)
	) AUTO_INCREMENT = 1001;
/*-----------------------------------------------*/
DROP TABLE IF EXISTS logs;
CREATE TABLE logs(
	idUser INT,
	fecha VARCHAR(11),
	hora VARCHAR(11),
	CONSTRAINT `idUserFK` FOREIGN KEY (idUser) REFERENCES usuarios (id)
);