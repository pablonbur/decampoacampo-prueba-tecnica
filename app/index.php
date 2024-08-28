<?php
// Pequeña ayuda en el php.ini para ver errores.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

require_once __DIR__ . '/services/Database.php';
require_once __DIR__ . '/models/BaseModel.php';
require_once __DIR__ . '/models/Producto.php';
require_once __DIR__ . '/controllers/ProductoController.php';
require_once __DIR__ . '/controllers/DolarController.php';

$productoController = new ProductoController();
$dolarController = new DolarController();
$router = new AltoRouter();

// Views

try {
    $router->map('GET', '/', function () {
        require __DIR__.'/views/home.php';
    });
    } catch (Exception $e) {
}

// API

try {
    $router->map('GET', '/api/productos', function () use ($productoController) {
        $productoController->productos();
    });
    } catch (Exception $e) {
}

try {
    $router->map('GET', '/api/productos/[i:id]', function ( $id ) use ($productoController) {
        $productoController->producto($id);
    });
    } catch (Exception $e) {
}

try {
    $router->map('POST', '/api/productos', function () use ($productoController) {
        $productoController->crearProducto();
    });
    } catch (Exception $e) {
}

try {
    $router->map('PUT', '/api/productos/[i:id]', function ($id) use ($productoController) {
        $productoController->editarProducto($id);
    });
    } catch (Exception $e) {
}

try {
    $router->map('DELETE', '/api/productos/[i:id]', function ( $id ) use ($productoController) {
        $productoController->eliminarProducto($id);
    });
    } catch (Exception $e) {
}

// Dolar - Exchange Rate API

try {
    $router->map('GET', '/dolar', function () use ($dolarController) {
        $dolarController->getRate();
    });
    } catch (Exception $e) {
}


// Funcionamiento de AltoRouter.

$match = $router->match();

if( is_array($match) && is_callable( $match['target'] ) ) {
    call_user_func_array( $match['target'], $match['params'] );
} else {
    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
