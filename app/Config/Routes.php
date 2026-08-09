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
$routes->get('/login/sesion', 'Login::salir');


$routes->get('/etiquetas', 'Etiquetas::index');
$routes->get('/etiquetas/borrar/(:num)','Etiquetas::borrar/$1');
$routes->get('/etiquetas/nuevo','Etiquetas::nuevo');
$routes->post('/etiquetas/insertar','Etiquetas::insertar');
$routes->post('/etiquetas/actualizar/(:num)', 'Etiquetas::actualizar/$1');
$routes->get('/etiquetas/editar/(:num)', 'Etiquetas::editar/$1');
$routes->get('/etiquetas/papelera/', 'Etiquetas::papelera/');
$routes->get('/etiquetas/recuperacion/(:num)','Etiquetas::recuperacion/$1');