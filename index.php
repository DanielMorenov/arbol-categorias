<?php

// Display errors
// !ERROR: esto no funciona
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


// Inicializamos Prestashop

if(!defined('_PS_VERSION_')) include(dirname(__FILE__).'/../config/config.inc.php');

require 'vendor/autoload.php';

use Pro\Import\Tree;
use Pro\Import\Categories;

new Exception("Error Processing Request", 500);


$cats = new Categories();

echo $cats;


echo "<hr><strong>Autogeneración de 10 breadcrumbs aleatorios</strong>:<br>";
$media = 0.0;
for($i=0;$i<10;$i++) 
{
    $j = rand(0,16);
    $in = microtime(true);
    echo "<br>getBreadcrumbs($j): ";
    echo $cats->getBreadcrumbs($j);
    $out = microtime(true);
    $out -=$in;
    //echo "(".$out.")";
    $media += $out;
}

echo "<hr><br>Media de ejecución: ".($media/10)."ms<br><br>";

echo "<a href='views\mi-arbol.php'>Acceder a la vista de arbol</a>";

