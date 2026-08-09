<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Auth::index'); 
$routes->get('login', 'Auth::index');
$routes->post('auth/procesarLogin', 'Auth::procesarLogin');
$routes->get('logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // -- Inventario Principal --
    $routes->get('inventario', 'Inventario::lista');
    $routes->get('inventario/entrada', 'Inventario::entrada');
    $routes->post('inventario/guardar', 'Inventario::guardar');
    
    // -- Gestión de Archivos y Firmas --
    $routes->get('inventario/subir/(:num)', 'Inventario::subirArchivo/$1');
    $routes->post('inventario/procesarArchivo', 'Inventario::procesarArchivo');
    $routes->get('inventario/documento/(:num)', 'Inventario::panelDocumento/$1');
    $routes->post('inventario/subirFirmado', 'Inventario::subirFirmado');

    // -- Arrendamientos y Salidas --
    $routes->get('arrendamientos', 'Inventario::arrendamientoGeneral');
    $routes->post('arrendamientos/procesarGeneral', 'Inventario::procesarArrendamientoGeneral');
    $routes->get('arrendamientos/previsualizarSalida', 'Inventario::previsualizarSalida');
    $routes->post('arrendamientos/enviarCorreoSalida', 'Inventario::enviarCorreoSalida');

    // -- Cotizaciones e Historial --
    $routes->get('cotizaciones', 'Cotizaciones::index');
    $routes->get('historial', 'Historial::index');
});