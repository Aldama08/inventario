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

$routes->get('inventario/previsualizar/(:num)', 'Inventario::previsualizar/$1');
$routes->post('inventario/enviarCorreo', 'inventario::enviarCorreo');

$routes->get('inventario/subir/(:num)', 'Inventario::subirArchivo/$1');
$routes->post('inventario/procesarArchivo', 'Inventario::procesarArchivo');

$routes->get('inventario/documento/(:num)', 'Inventario::panelDocumento/$1');
$routes->get('inventario/descargarOriginal/(:num)', 'Inventario::descargarOriginal/$1');
$routes->post('inventario/subirFirmado', 'Inventario::subirFirmado'); 

$routes->get('arrendamientos', 'Inventario::arrendamientoGeneral');
$routes->post('arrendamientos/procesarGeneral', 'Inventario::procesarArrendamientoGeneral');