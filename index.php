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

var_dump($categorias->arbol);

echo "<hr>";

echo new Tree($categorias->arbol);

echo new Tree($categorias->arbol, "ol","li", false);

$a = $categorias->resultado;
echo "<hr>";


$indices = [];
foreach($a as $item)
{
    $indices[]=(int)$item['id_category'];
}
$valores = array_values($a);

$final = array_combine($indices, $valores);

foreach($final as $index => $item)
{
    echo "<br>Clave: ".$index." -- "." Valor: ";
    var_dump($item);
}



