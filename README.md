# decampoacampo-prueba-tecnica
Desarrollo de una API REST para gestión de productos simple.


## Guía de instalación

La aplicación utiliza PHP 8.3 y MySQL principalmente, a través de Docker.

### Requisitos

- Docker

### Instalación

- Clonar el repositorio.
- Ejecutar `docker-compose up --build` en la raíz del proyecto.

En tu navegador, deberías poder acceder a la aplicación en:

    http://localhost:9000

Y por ejemplo al phpMyAdmin en:

    http://localhost:8080

Podés encontrar las credenciales en el compose.yaml.

## Código

Dentro de la carpeta app, veremos todo el código que corre la aplicación.

Como vemos en el compose.yaml, se levantan 3 servicios: la base de datos en MySQL, el proyecto en si y el phpMyAdmin. Estos últimos 2 dependiendo del primero.
Al levantarse la base de datos, se corre el script que hay en `config/init_productos.sql`.

### services

Encontraremos la conexión a la base de datos utilizando Singleton y tomando las credenciales desde el compose.yaml (aunque quiero integrarlo a un .env).

### models

Tendremos BaseModel y Producto. Ambos tienen la lógica de los métodos pedidos, BaseModel siendo una demostración de que podían abstraerse para algún uso aparte de productos, y Producto la implementación tradicional.

### controllers

ProductoController es la continuación a los models que nombré, donde se encarga de gestionar con ayuda de `helpers`, para armar una respuesta estándar, y `transformers`, para agregar el PRECIO_USD a los datos.

### index.php

El gestor de rutas. Como utilicé [AltoRouter](https://github.com/dannyvankooten/AltoRouter) para simplificarlas, lo recomendado era utilizarlo en esta ruta. Pueden verse las rutas de las Views o de la API, y sus llamdos a los métodos acordes en `ProductoController`.

### views

Contiene la única vista del proyecto, donde integra el HTML necesario y su JS debajo. Utiliza `Bootstrap 5` para su apariencia, y `SweetAlert` para simplificar algunas alertas/carteles.

### tests

BaseTestCase tiene algunos métodos comunes, como get, post y otras utilidades. ProductoTest contiene solo 2 tests, de crear y obtener productos.
