CREATE DATABASE IF NOT EXISTS comanda_laporte;
USE comanda_laporte;

/*-----------------------------------------------*/
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios(
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario varchar(50) NOT NULL,
    clave varchar(250) NOT NULL,
    rol varchar(13) NOT NULL,
    CONSTRAINT `rol_check` CHECK (
		rol LIKE 'bartender' OR
		rol LIKE 'cervecero' OR
		rol LIKE 'cocinero' OR
		rol LIKE 'mozo' OR
		rol LIKE 'socio'
	)
) AUTO_INCREMENT = 101;

INSERT INTO usuarios(usuario, clave, rol) VALUES
    ('Max', '$2y$10$.RLc7btiUb4zFHku4QV8ZuM0AQK9oMI7EdRqcQDzAFF56t0W7yNY2', 'mozo'),
    ('John', '$2y$10$.RLc7btiUb4zFHku4QV8ZuM0AQK9oMI7EdRqcQDzAFF56t0W7yNY2', 'cocinero'),
    ('Tom', '$2y$10$.RLc7btiUb4zFHku4QV8ZuM0AQK9oMI7EdRqcQDzAFF56t0W7yNY2', 'socio');
/*-----------------------------------------------*/
DROP TABLE IF EXISTS mesas;
CREATE TABLE mesas(
    id INT(5) PRIMARY KEY AUTO_INCREMENT,
    estado INT NOT NULL,
    CONSTRAINT `estadoMesa_check` CHECK (estado >= 1 AND estado <= 4)
    ) AUTO_INCREMENT = 10001;
    
INSERT INTO mesas(estado) VALUES (1),(1),(3),(4);
/*-----------------------------------------------*/
DROP TABLE IF EXISTS productos;
CREATE TABLE productos(
    id INT PRIMARY KEY AUTO_INCREMENT,
    sector INT NOT NULL,
	descripcion varchar(250) NOT NULL,
	precio FLOAT NOT NULL,
    CONSTRAINT `sector_check` CHECK (sector >= 1 AND sector <= 4)
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
    idMesa INT NOT NULL,
    idProducto INT NOT NULL,
    estado INT NOT NULL,
	cliente varchar(250) NOT NULL,
	minutos INT NOT NULL,
	foto varchar(250) NOT NULL,
    CONSTRAINT `estadoPedido_check` CHECK (estado = 0 OR estado = 1),
	CONSTRAINT `idMesaFK` FOREIGN KEY (idMesa) REFERENCES mesas (id),
	CONSTRAINT `idProductoFK` FOREIGN KEY (idProducto) REFERENCES productos (id)
    );
/*-----------------------------------------------*/