<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './db/accesoDatos.php';
require_once './controllers/ProductoController.php';
require_once './controllers/VentaController.php';
require_once './controllers/UsuarioController.php';
require_once './middlewares/autentificadorJWT.php';
require_once './middlewares/logger.php';



$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/api');
/*
por bash:
composer update
php -S localhost:666 -t public
localhost:666/public/
*/
/* 

*/
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true); 
$app->addBodyParsingMiddleware();




$app->get('[/]', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Recu Segundo Parcial");
    return $response;
});

$app->group('/tienda', function (RouteCollectorProxy $group) {
    $group->post('/consultar', \ProductoController::class . ':TraerUno');
    $group->get('/todos', \ProductoController::class . ':TraerTodos')->add(new ConfirmarPerfil('administrador'));
    $group->delete('/borrar/{id}', \ProductoController::class . ':BorrarProducto');
    $group->put('/modificar/{id}', \ProductoController::class . ':ModificarProducto')->add(new ConfirmarPerfil('admin'));
    $group->post('/alta', ProductoController::class . ':CargarUno')->add(new ConfirmarPerfil('admin'));
});

/* ->add(new ConfirmarPerfil('admin', 'cliente')); */
$app->group('/ventas', function (RouteCollectorProxy $group) {
    $group->post('/alta', \VentaController::class . ':CargarUno')->add(new ConfirmarPerfil('admin', 'cliente'));
    $group->get('/consultar/productos/vendidos/{fecha}', \VentaController::class . ':VentasPorFecha')->add(new ConfirmarPerfil('admin', 'cliente'));
    $group->get('/consultar/ventas/porUsuario/{email}', \VentaController::class . ':VentasPorUsuario')->add(new ConfirmarPerfil('admin', 'cliente'));
    $group->get('/consultar/ventas/porProducto/{nombre}/{tipo}', \VentaController::class . ':VentasPorProducto')->add(new ConfirmarPerfil('admin', 'cliente'));
    $group->get('/ingresos[/{fecha}]', \VentaController::class . ':IngresosPorDia')->add(new ConfirmarPerfil('admin'));
    $group->get('/tienda/consultar/productos/masVendido', \VentaController::class . ':ProductoMasVendido')->add(new ConfirmarPerfil('admin', 'cliente'));
    $group->put('/modificar', \VentaController::class . ':ModificarVenta');
    $group->get('/tienda/consultar/productos/entreValores/{min}/{max}', \VentaController::class . ':ProductoEntreValores')->add(new ConfirmarPerfil('admin', 'cliente'));
})->add(new ConfirmarPerfil('admin', 'cliente'));

$app->group('/registro', function (RouteCollectorProxy $group) {
    $group->post('[/]', \UsuarioController::class . ':CargarUno');

});

$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('[/]', \UsuarioController::class . ':ValidarUsuario');
  });

  $app->get('/ventas/descargar', 'VentaController:DescargarCSV')
  ->add(new ConfirmarPerfil('admin'));


// Run app
$app->run();

