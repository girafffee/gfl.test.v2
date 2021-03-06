<?php

addRoute('/', 'HomeController', [
    'setNameRoute' => 'home'
]);

addRoute('/catalog', 'CatalogController', [
    'setNameRoute' => 'catalog'
]);

addRoute('/admin', 'AdminController', [
    'setNameRoute' => 'admin'
]);

addRoute('/book/create', 'AdminController#createBook', [
    'setNameRoute' => 'create_book'
]);

addGroup('/book/{action}/{id}', 'CatalogController', [
    'setNameRoute' => 'single_book'
]);

addGroup('/ajax-c/catalog/{action}', 'AjaxCatalogController', [
    'setNameRoute' => 'ajax_book'
]);

addGroup('/ajax-a/admin/{action}', 'AjaxAdminController', [
    'setNameRoute' => 'ajax_admin'
]);

addGroup('/admin-g/{object}/{action}', 'AdminController', [
    'setNameRoute' => 'admin_group'
]);

addGroup('/admin-s/{object}/{action}/{id}', 'AdminController', [
    'setNameRoute' => 'admin_single'
]);


