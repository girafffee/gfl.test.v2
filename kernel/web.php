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

addGroup('/book/{action}/{id}', 'CatalogController', [
    'setNameRoute' => 'single_book'
]);

addGroup('/ajax/{action}', 'AjaxCatalogController', [
    'setNameRoute' => 'ajax_book'
]);

addGroup('/admin-g/{object}/{action}', 'AdminController', [
    'setNameRoute' => 'admin_group'
]);


