<?php

// Display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializamos Prestashop

if(!defined('_PS_VERSION_')) include(dirname(__FILE__).'/../config/config.inc.php');

require 'vendor/autoload.php';

use Pro\Import\Tree;

// Pasamos el arbol (array) u los nodos HTML
$init = new Tree(Category::getRootCategory()->recurseLiteCategTree(0),"ul","li");

echo $init;
