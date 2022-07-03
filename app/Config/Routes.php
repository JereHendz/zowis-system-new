<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes->group('api', function($routes){
    $routes->resource('roles'); //api/roles
    $routes->resource('users'); //api/Users
    $routes->resource('employees'); //api/Employees
    $routes->resource('country'); //api/Country
    $routes->resource('departments'); //api/Departments
    $routes->resource('municipios'); //api/Municipios
    $routes->resource('processstate'); //api/ProcessState
    $routes->add('loadEmp', 'Employees::loadEmployees');
    $routes->resource('categories'); //api/Categories
    $routes->resource('subcategories'); //api/Subcategories
    $routes->resource('providers'); //api/Providers
    $routes->add('providersComplete', 'Providers::providersComplete');
    $routes->resource('brands'); //api/Brands
    $routes->resource('products'); //api/Products
    $routes->add('getProductImages', 'Products::getAllProductsWithImages');
    $routes->add('infoFormProduct', 'Products::getInformationToCreateProduct');
    $routes->add('subCateByIdCate/(:any)', 'SubCategories::getSubCategoriesByIdCategory/$1');  
    $routes->add('imagesById/(:any)', 'Products::getImagesByProduct/$1');  
    $routes->add('uptImage', 'Products::updateImage');    
    $routes->add('createImages', 'Products::createImagesByProduct');    


    // $routes->post('statusR', 'ProcessState::show/$1');



    // $routes->get('login/(:any)', 'Users::login/$1'); //api/Users/login
    $routes->post('login', 'Users::login');
    // $routes->post('create', 'Users::create');
});

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
