<?php

/** @var Router $router */

// Auth
$router->get('/login',  'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Home redirect
$router->get('/', 'DashboardController@index');

// Dashboard
$router->get('/dashboard', 'DashboardController@index');

// Users (super admin only)
$router->get('/users',              'UserController@index');
$router->get('/users/create',       'UserController@create');
$router->post('/users/store',       'UserController@store');
$router->get('/users/{id}/toggle',  'UserController@toggle');

// Products
$router->get('/products',               'ProductController@index');
$router->get('/products/create',        'ProductController@create');
$router->post('/products/store',        'ProductController@store');
$router->get('/products/{id}/edit',     'ProductController@edit');
$router->post('/products/{id}/update',  'ProductController@update');
$router->get('/products/{id}/toggle',   'ProductController@toggle');
$router->get('/products/{id}/delete',   'ProductController@delete');

// Categories
$router->get('/categories',                 'CategoryController@index');
$router->post('/categories/store',          'CategoryController@store');
$router->get('/categories/{id}/edit',       'CategoryController@edit');
$router->post('/categories/{id}/update',    'CategoryController@update');
$router->get('/categories/{id}/delete',     'CategoryController@delete');

// Stock / Inventory
$router->get('/stock', 'StockController@index');

// Suppliers
$router->get('/suppliers',               'SupplierController@index');
$router->get('/suppliers/create',        'SupplierController@create');
$router->post('/suppliers/store',        'SupplierController@store');
$router->get('/suppliers/{id}/edit',     'SupplierController@edit');
$router->post('/suppliers/{id}/update',  'SupplierController@update');
$router->get('/suppliers/{id}/toggle',   'SupplierController@toggle');

// Sales
$router->get('/sales',          'SaleController@index');
$router->get('/sales/{id}',     'SaleController@show');
$router->get('/sales/{id}/print','SaleController@print');

// Purchases
$router->get('/purchases',          'PurchaseController@index');
$router->get('/purchases/create',   'PurchaseController@create');
$router->post('/purchases/store',   'PurchaseController@store');
$router->get('/purchases/{id}',     'PurchaseController@show');
$router->get('/purchases/{id}/receive', 'PurchaseController@receive');
$router->get('/purchases/{id}/cancel',  'PurchaseController@cancel');
$router->get('/purchases/{id}/print','PurchaseController@print');

// Customers
$router->get('/customers',               'CustomerController@index');
$router->get('/customers/create',        'CustomerController@create');
$router->post('/customers/store',        'CustomerController@store');
$router->get('/customers/{id}/edit',     'CustomerController@edit');
$router->post('/customers/{id}/update',  'CustomerController@update');
$router->get('/customers/{id}/toggle',   'CustomerController@toggle');

// POS
$router->get('/pos',            'SaleController@cashier');
$router->post('/pos/checkout',  'SaleController@checkout');

// Reports
$router->get('/reports/daily', 'ReportController@daily');

// Credits
$router->get('/credits/suppliers',              'CreditController@suppliers');
$router->post('/credits/suppliers/{id}/settle', 'CreditController@settleSupplier');
$router->get('/credits/customers',              'CreditController@customers');
$router->post('/credits/customers/{id}/settle', 'CreditController@settleCustomer');

// Accounts
$router->post('/accounts/update', 'AccountController@update');
$router->get('/accounts', 'AccountController@index');

// Settings
$router->get('/settings',           'SettingsController@index');
$router->post('/settings/profile',  'SettingsController@updateProfile');
$router->post('/settings/password', 'SettingsController@updatePassword');

// Transactions
$router->get('/transactions', 'TransactionController@index');

// Notifications
$router->get('/notifications', 'NotificationController@index');