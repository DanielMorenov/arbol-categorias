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

echo $arbol = new Tree($categorias->getArbol());



echo "<hr>";

for($i=0;$i<30;$i++) 
{
    $j = rand(0,16);
    $in = microtime(true);
    echo "<br>getBreadcrumbs($j): ";
    echo $categorias->getBreadcrumbs($j);
    $out = microtime(true);
    $out -=$in;
    //echo "(".$out.")";
    $media += $out;
}

echo "<hr><br>Media: ".($media/30)."ms";