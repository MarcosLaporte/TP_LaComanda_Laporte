CREATE DATABASE IF NOT EXISTS comanda_laporte;
USE comanda_laporte;

/*-----------------------------------------------*/
DROP TABLE IF EXISTS estados_user;
CREATE TABLE estados_user(
	id INT PRIMARY KEY,
	descripcion VARCHAR(50) NOT NULL
);

INSERT INTO estados_user(id, descripcion) VALUES
	(-1, "SUSPENDIDO"),
	(0, "INACTIVO"),
	(1, "ACTIVO");
/*-----------------------------------------------*/
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios(
	id INT PRIMARY KEY AUTO_INCREMENT,
	usuario VARCHAR(50),
	clave VARCHAR(250),
	rol VARCHAR(13),
	estado INT,
	CONSTRAINT `rol_check` CHECK (
		rol IN ('bartender', 'cervecero', 'cocinero', 'mozo', 'socio')
	),
	CONSTRAINT `estado_check` CHECK (estado BETWEEN -1 AND 1),
	CONSTRAINT `estadoUserFK` FOREIGN KEY (estado) REFERENCES estados_user (id)
) AUTO_INCREMENT = 101;

INSERT INTO usuarios(usuario, clave, rol, estado) VALUES
	('Frank', '$2y$10$.RLc7btiUb4zFHku4QV8ZuM0AQK9oMI7EdRqcQDzAFF56t0W7yNY2', 'socio', 1),
	('Max', '$2y$10$.RLc7btiUb4zFHku4QV8ZuM0AQK9oMI7EdRqcQDzAFF56t0W7yNY2', 'mozo', 1),
	('Tom', '$2y$10$.RLc7btiUb4zFHku4QV8ZuM0AQK9oMI7EdRqcQDzAFF56t0W7yNY2', 'cocinero', 1),
	('John', '$2y$10$.RLc7btiUb4zFHku4QV8ZuM0AQK9oMI7EdRqcQDzAFF56t0W7yNY2', 'bartender', 1);
/*-----------------------------------------------*/
DROP TABLE IF EXISTS estados_mesas;
CREATE TABLE estados_mesas(
	id INT PRIMARY KEY,
	descripcion VARCHAR(50) NOT NULL
);

INSERT INTO estados_mesas(id, descripcion) VALUES
	(0, "VACIA"),
	(1, "ESPERANDO"),
	(2, "COMIENDO"),
	(3, "PAGANDO"),
	(4, "CERRADA");
/*-----------------------------------------------*/
DROP TABLE IF EXISTS mesas;
CREATE TABLE mesas(
	id INT(5) PRIMARY KEY AUTO_INCREMENT,
	estado INT,
	CONSTRAINT `estadoMesa_check` CHECK (estado BETWEEN 0 AND 4),
	CONSTRAINT `estadoMesaFK` FOREIGN KEY (estado) REFERENCES estados_mesas (id)
) AUTO_INCREMENT = 1;

INSERT INTO mesas(estado) VALUES (0),(0),(0),(0),(0);
/*-----------------------------------------------*/
DROP TABLE IF EXISTS sectores_productos;
CREATE TABLE sectores_productos(
	id INT PRIMARY KEY,
	descripcion VARCHAR(50) NOT NULL
);

INSERT INTO sectores_productos(id, descripcion) VALUES
	(1, "TRAGOS"),
	(2, "CERVEZAS"),
	(3, "COCINA"),
	(4, "CANDY");

/*-----------------------------------------------*/
DROP TABLE IF EXISTS productos;
CREATE TABLE productos(
	id INT PRIMARY KEY AUTO_INCREMENT,
	sector INT,
	descripcion VARCHAR(250),
	precio FLOAT,
	CONSTRAINT `sector_check` CHECK (sector BETWEEN 1 AND 4),
	CONSTRAINT `precio_check` CHECK (precio > 0),
	CONSTRAINT `sectorProdFK` FOREIGN KEY (sector) REFERENCES sectores_productos (id)
) AUTO_INCREMENT = 101;

INSERT INTO productos(sector, descripcion, precio) VALUES 
	(2, 'Amstel Lager 750ml', 1449.99),
	(3, 'Pastel de papa', 1699.5),
	(3, 'Pollo y papas con crema al verdeo', 2050),
	(4, 'Tiramisu de dulce de leche', 1250);
/*-----------------------------------------------*/
DROP TABLE IF EXISTS estados_pedidos;
CREATE TABLE estados_pedidos(
	id INT PRIMARY KEY,
	descripcion VARCHAR(50) NOT NULL
);

INSERT INTO estados_pedidos(id, descripcion) VALUES
	(0, "PREPARACION"),
	(1, "LISTO");
/*-----------------------------------------------*/
DROP TABLE IF EXISTS pedidos;
CREATE TABLE pedidos(
	id CHAR(5) PRIMARY KEY,
	idMesa INT,
	estado INT,
	precio FLOAT,
	fecha VARCHAR(11),
	minutos INT,
	foto VARCHAR(250),
	activo BOOLEAN,
	CONSTRAINT `estadoPed_check` CHECK (estado IN (0, 1)),
	CONSTRAINT `precioPed_check` CHECK (precio > 0),
	CONSTRAINT `idMesaPedFK` FOREIGN KEY (idMesa) REFERENCES mesas (id),
	CONSTRAINT `estadoPedFK` FOREIGN KEY (estado) REFERENCES estados_pedidos (id)
	);
/*-----------------------------------------------*/
DROP TABLE IF EXISTS productos_pedidos;
CREATE TABLE productos_pedidos(
	idProducto INT,
	idMesa INT,
	idPedido CHAR(5),
	estado INT,
	minutos INT,
	cliente VARCHAR(50),
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
/*-----------------------------------------------*/
DROP TABLE IF EXISTS recibos;
CREATE TABLE recibos(
	numero INT PRIMARY KEY AUTO_INCREMENT,
	fecha VARCHAR(11),
	idPedido CHAR(5),
	cliente VARCHAR(50),
	formaDePago VARCHAR(25),
	importe FLOAT,
	CONSTRAINT `formaDePago_check` CHECK (
		formaDePago IN ("efectivo", "transferencia", "debito", "credito")
	),
	CONSTRAINT `idPedidoRecFK` FOREIGN KEY (idPedido) REFERENCES pedidos (id)
) AUTO_INCREMENT = 1000001;