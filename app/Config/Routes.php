<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('inventario', 'Inventario::lista');
$routes->get('inventario/entrada', 'Inventario::entrada');
$routes->post('inventario/guardar', 'Inventario::guardar');

$routes->get('arrendamientos', 'Inventario::arrendamientoGeneral');
$routes->post('arrendamientos/procesarGeneral', 'Inventario::procesarArrendamientoGeneral');
$routes->get('arrendamientos/previsualizarSalida', 'Inventario::previsualizarSalida');
$routes->post('arrendamientos/enviarCorreoSalida', 'Inventario::enviarCorreoSalida');

$routes->get('inventario/subir/(:num)', 'Inventario::subirArchivo/$1');
$routes->post('inventario/procesarArchivo', 'Inventario::procesarArchivo');
$routes->get('inventario/documento/(:num)', 'Inventario::panelDocumento/$1');
$routes->post('inventario/subirFirmado', 'Inventario::subirFirmado');

$routes->get('cotizaciones', 'Cotizaciones::index');
$routes->get('historial', 'Historial::index');