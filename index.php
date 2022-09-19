<?php

// Display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializamos Prestashop

if(!defined('_PS_VERSION_')) include(dirname(__FILE__).'/../config/config.inc.php');

require 'vendor/autoload.php';

use Pro\Import\Tree;
use Pro\Import\Categories;

$categorias = new Categories();

echo "<hr>";

echo $categorias;

echo "<hr>";

$arbol = new Tree($categorias->getListado(), "ol","li",true);

echo $arbol;

echo "<hr>";

echo Categories::getBreadcrumbs(13);