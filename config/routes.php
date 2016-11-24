<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

/**
 * Define route for API
 * Added by phucnguyen
 */

Router::scope('/api-users', function ($routes) {
    $routes->connect('/', ['controller' => 'ApiUsers', 'action' => 'index']);
    $routes->connect('/add', ['controller' => 'ApiUsers', 'action' => 'add']);
    $routes->connect('/view/:id', ['controller' => 'ApiUsers', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/edit/:id', ['controller' => 'ApiUsers', 'action' => 'edit'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/delete/:id', ['controller' => 'ApiUsers', 'action' => 'delete'], ['id' => '\d+', 'pass' => ['id']]);
});
Router::scope('/users', function ($routes) {
    $routes->connect('/', ['controller' => 'Users', 'action' => 'index']);
    $routes->connect('/add', ['controller' => 'Users', 'action' => 'add']);
    $routes->connect('/view/:id', ['controller' => 'Users', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/edit/:id', ['controller' => 'Users', 'action' => 'edit'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/delete/:id', ['controller' => 'Users', 'action' => 'delete'], ['id' => '\d+', 'pass' => ['id']]);
});
Router::scope('/shops', function ($routes) {
    $routes->connect('/', ['controller' => 'Shops', 'action' => 'index']);
    $routes->connect('/add', ['controller' => 'Shops', 'action' => 'add']);
    $routes->connect('/view/:id', ['controller' => 'Shops', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/edit/:id', ['controller' => 'Shops', 'action' => 'edit'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/delete/:id', ['controller' => 'Shops', 'action' => 'delete'], ['id' => '\d+', 'pass' => ['id']]);
});
Router::scope('/employees', function ($routes) {
    $routes->connect('/', ['controller' => 'Employees', 'action' => 'index']);
    $routes->connect('/add', ['controller' => 'Employees', 'action' => 'add']);
    $routes->connect('/view/:id', ['controller' => 'Employees', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/edit/:id', ['controller' => 'Employees', 'action' => 'edit'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/delete/:id', ['controller' => 'Employees', 'action' => 'delete'], ['id' => '\d+', 'pass' => ['id']]);
});
Router::scope('/customers', function ($routes) {
    $routes->connect('/', ['controller' => 'Customers', 'action' => 'index']);
    $routes->connect('/add', ['controller' => 'Customers', 'action' => 'add']);
    $routes->connect('/view/:id', ['controller' => 'Customers', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/edit/:id', ['controller' => 'Customers', 'action' => 'edit'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/delete/:id', ['controller' => 'Customers', 'action' => 'delete'], ['id' => '\d+', 'pass' => ['id']]);
});
Router::scope('/categories', function ($routes) {
    $routes->connect('/', ['controller' => 'Categories', 'action' => 'index']);
    $routes->connect('/add', ['controller' => 'Categories', 'action' => 'add']);
    $routes->connect('/view/:id', ['controller' => 'Categories', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/edit/:id', ['controller' => 'Categories', 'action' => 'edit'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/delete/:id', ['controller' => 'Categories', 'action' => 'delete'], ['id' => '\d+', 'pass' => ['id']]);
});
Router::scope('/services', function ($routes) {
    $routes->connect('/', ['controller' => 'Services', 'action' => 'index']);
    $routes->connect('/add', ['controller' => 'Services', 'action' => 'add']);
    $routes->connect('/view/:id', ['controller' => 'Services', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/edit/:id', ['controller' => 'Services', 'action' => 'edit'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/delete/:id', ['controller' => 'Services', 'action' => 'delete'], ['id' => '\d+', 'pass' => ['id']]);
});


Router::prefix('api', function ($routes) {
    $routes->extensions(['json', 'xml']);

    $routes->connect('/shops/login', ['controller' => 'Shops', 'action' => 'login', '_method' => 'POST']);
    $routes->connect('/shops/logout', ['controller' => 'Shops', 'action' => 'logout', '_method' => 'POST']);

    $routes->resources('ApiUsers');
    Router::connect('/api/api-users/register', ['controller' => 'ApiUsers', 'action' => 'add', 'prefix' => 'api']);
    Router::connect('/api/api-users/token', ['controller' => 'ApiUsers', 'action' => 'token', 'prefix' => 'api']);

    $routes->resources('Employees');
    $routes->resources('Customers');
    $routes->resources('Categories');
    $routes->resources('Services');
    $routes->resources('Billings');

    Router::connect('/api/billings/add-services/:id', ['controller' => 'Billings', 'action' => 'addService', 'prefix' => 'api'], ['id' => '\d+', 'pass' => ['id']]);
    Router::connect('/api/billings/remove-services/:id', ['controller' => 'Billings', 'action' => 'removeService', 'prefix' => 'api'], ['id' => '\d+', 'pass' => ['id']]);
    Router::connect('/api/billings/discount/:id', ['controller' => 'Billings', 'action' => 'discount', 'prefix' => 'api'], ['id' => '\d+', 'pass' => ['id']]);
    Router::connect('/api/billings/tips/:id', ['controller' => 'Billings', 'action' => 'tips', 'prefix' => 'api'], ['id' => '\d+', 'pass' => ['id']]);
    Router::connect('/api/billings/done/:id', ['controller' => 'Billings', 'action' => 'done', 'prefix' => 'api'], ['id' => '\d+', 'pass' => ['id']]);

    $routes->resources('Salaries');

    $routes->fallbacks('InflectedRoute');
});



/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
