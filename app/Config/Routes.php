<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/panel', 'Panel::index');
$routes->get('/clientes', 'Clientes::index');
$routes->get('/clientes/borrar/(:num)','Clientes::borrar/$1');
$routes->get('/clientes/nuevo','Clientes::nuevo');