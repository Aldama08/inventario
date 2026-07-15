<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


// GET para ver el formulario
$routes->get('inventario/entrada', 'Inventario::entrada');

// POST para procesar y guardar el formulario
$routes->post('inventario/guardar', 'Inventario::guardar');

// GET para ver la lista
$routes->get('inventario', 'Inventario::lista');

$routes->get('cotizaciones', 'Cotizaciones::index');
$routes->get('historial', 'Historial::index');