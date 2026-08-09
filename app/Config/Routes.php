<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/usuarios', 'Usuarios::index');
$routes->get('/usuarios/borrar/(:num)','Usuarios::borrar/$1');
$routes->get('/usuarios/nuevo','Usuarios::nuevo');
$routes->post('/usuarios/insertar','Usuarios::insertar');
$routes->post('/usuarios/actualizar/(:num)', 'Usuarios::actualizar/$1');
$routes->get('/usuarios/editar/(:num)', 'Usuarios::editar/$1');
$routes->get('/usuarios/papelera/', 'Usuarios::papelera/');
$routes->get('/usuarios/recuperacion/(:num)','Usuarios::recuperacion/$1');

$routes->get('/registro', 'Login::index');
$routes->post('/login/validacion', 'Login::validacion');