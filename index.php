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

// Pasamos el arbol (array) u los nodos HTML

echo "Estructura de arbol:<br>";
$init = new Tree(Category::getRootCategory()->recurseLiteCategTree(0),"ul","li");
echo $init;

echo "<br>Array deseado:<br>";
var_dump(Category::getRootCategory()->recurseLiteCategTree(0));

echo "<br><br>Array final: <br>";

$salida = Categories::cargarArbol(1);

var_dump($salida);

echo "<br><br>Salida final:<br>";
$init2 = new Tree(Categories::cargarArbol(1),"ul","li");
echo $init2;

var_dump (Categories::cargarArbol(1));