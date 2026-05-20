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
    $routes->post('posts/(:num)/submit-review', 'Posts::submitReview/$1');
    $routes->get('posts/review', 'Posts::reviewQueue');
    $routes->get('posts/(:num)/review', 'Posts::reviewPost/$1');
    $routes->post('posts/(:num)/approve', 'Posts::approve/$1');
    $routes->post('posts/(:num)/reject', 'Posts::reject/$1');
    $routes->get('generate', 'Generate::index');
    $routes->post('generate/cluster', 'Generate::cluster');
    $routes->post('generate/(:num)', 'Generate::generateOne/$1');
    $routes->get('generate/batch', 'Generate::index');
    $routes->post('generate/batch', 'Generate::generateBatch');
    $routes->get('blogs', 'Blogs::index');
    $routes->get('blogs/create', 'Blogs::create');
    $routes->post('blogs/store', 'Blogs::store');
    $routes->get('blogs/(:num)/edit', 'Blogs::edit/$1');
    $routes->post('blogs/(:num)/update', 'Blogs::update/$1');
    $routes->post('blogs/(:num)/delete', 'Blogs::delete/$1');
    $routes->get('translations', 'Translations::index');
    $routes->post('translations', 'Translations::update');
    $routes->post('stock-images/suggest', 'StockImages::suggest');
});

// Pretty post URLs: /{lang}/{category_slug}/{post_slug}
$routes->get('(:segment)/(:segment)/(:segment)', 'Home::showBySlug/$1/$2/$3');

// Backwards-compatible: /{lang}/posts/{id}
$routes->get('(:segment)/posts/(:num)', 'Home::show/$2/$1');

$routes->post('wp-json/wp/v2/posts', 'Api\Posts::create');
$routes->get('(:segment)', 'Home::index/$1');
