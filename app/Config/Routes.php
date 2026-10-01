<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/usuarios', 'Usuarios::index');
$routes->post('/usuarios/borrar/(:num)','Usuarios::borrar/$1');
$routes->get('/usuarios/nuevo','Usuarios::nuevo');
$routes->post('/usuarios/insertar','Usuarios::insertar');
$routes->post('/usuarios/actualizar/(:num)', 'Usuarios::actualizar/$1');
$routes->get('/usuarios/editar/(:num)', 'Usuarios::editar/$1');
$routes->get('/usuarios/papelera/', 'Usuarios::papelera/');
$routes->post('/usuarios/recuperacion/(:num)','Usuarios::recuperacion/$1');
$routes->get('usuarios/api','Usuarios::api_usuarios');
$routes->get('usuarios/api2','Usuarios::api_usuarios_borrados');


$routes->get('/registro', 'Login::index');
$routes->post('/login/validacion', 'Login::validacion');
$routes->get('/login/sesion', 'Login::salir');


$routes->get('/etiquetas', 'Etiquetas::index');
$routes->post('/etiquetas/borrar/(:num)','Etiquetas::borrar/$1');
$routes->get('/etiquetas/nuevo','Etiquetas::nuevo');
$routes->post('/etiquetas/insertar','Etiquetas::insertar');
$routes->post('/etiquetas/actualizar/(:num)', 'Etiquetas::actualizar/$1');
$routes->get('/etiquetas/editar/(:num)', 'Etiquetas::editar/$1');
$routes->get('/etiquetas/papelera/', 'Etiquetas::papelera/');
$routes->post('/etiquetas/recuperacion/(:num)','Etiquetas::recuperacion/$1');
$routes->get('etiquetas/api','Etiquetas::api_etiquetas');
$routes->get('etiquetas/api2','Etiquetas::api_etiquetas_borrados');

$routes->get('/atributos', 'Atributos::index');
$routes->post('/atributos/borrar/(:num)','Atributos::borrar/$1');
$routes->get('/atributos/nuevo','Atributos::nuevo');
$routes->post('/atributos/insertar','Atributos::insertar');
$routes->post('/atributos/actualizar/(:num)', 'Atributos::actualizar/$1');
$routes->get('/atributos/editar/(:num)', 'Atributos::editar/$1');
$routes->get('/atributos/papelera/', 'Atributos::papelera/');
$routes->post('/atributos/recuperacion/(:num)','Atributos::recuperacion/$1');
$routes->get('atributos/api','Atributos::api_atributos');
$routes->get('atributos/api2','Atributos::api_atributos_borrados');

$routes->get('/objetos', 'Objetos::index');
$routes->post('/objetos/borrar/(:num)','Objetos::borrar/$1');
$routes->get('/objetos/nuevo','Objetos::nuevo');
$routes->post('/objetos/insertar','Objetos::insertar');
$routes->post('/objetos/actualizar/(:num)', 'Objetos::actualizar/$1');
$routes->get('/objetos/editar/(:num)', 'Objetos::editar/$1');
$routes->get('/objetos/papelera/', 'Objetos::papelera/');
$routes->post('/objetos/recuperacion/(:num)','Objetos::recuperacion/$1');
$routes->get('objetos/ver/(:num)', 'Objetos::ver/$1');

$routes->get('objetos/api','Objetos::api_objetos');
$routes->get('objetos/api2','Objetos::api_objetos_borrados');