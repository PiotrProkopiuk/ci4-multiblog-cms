<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('lang/(:segment)', 'Home::switchLanguage/$1');
$routes->get('posts/(:num)', 'Home::show/$1');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

$routes->group('admin', static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('posts', 'Posts::index');
    $routes->get('posts/new', 'Posts::new');
    $routes->post('posts', 'Posts::create');
    $routes->get('posts/(:num)/edit', 'Posts::edit/$1');
    $routes->post('posts/(:num)', 'Posts::update/$1');
    $routes->post('posts/(:num)/delete', 'Posts::delete/$1');
    $routes->get('translations', 'Translations::index');
    $routes->post('translations', 'Translations::update');
    $routes->post('stock-images/suggest', 'StockImages::suggest');
});

$routes->post('wp-json/wp/v2/posts', 'Api\Posts::create');
$routes->get('(:segment)', 'Home::index/$1');
$routes->get('(:segment)/posts/(:num)', 'Home::show/$2/$1');
