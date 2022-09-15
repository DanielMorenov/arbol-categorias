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

// Obtenemos Arbol con SQL
$inicio = microtime();
$salida = Categories::cargarArbol(0);
$finSQL = microtime()-$inicio;


echo "Estructura de Categorias:<br>";

$categorias = new Categories();

echo "<br>";
$inicio = microtime();
echo Tree::mostrarArbol($categorias,"ul","li",false,0);
$finARRAY = microtime()-$inicio;

echo "Duración construcción con SQL (en ms)   : ".round($finSQL,10);
echo "<hr>";
echo "Duración construcción con arrays (en ms): ".round($finARRAY,10)." (un ".round($finARRAY/$finSQL*100,2)."% del tiempo empleado por SQL)";

