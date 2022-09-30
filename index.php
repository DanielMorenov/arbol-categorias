<?php

// Display errors pero definimos antes modo debug en PS, antes que lo haga PS con el valor que tenga configurada la tienda
//define('_PS_MODE_DEV_', true); // No se suben los datos que no son necesarios


// Inicializamos Prestashop
if (!defined('_PS_VERSION_')) {
    include(dirname(__FILE__) . '/../../config/config.inc.php');
}

// A pesar que PS haya podido modificar estos valores en php.ini, los volvemos a configurar para que muestren los errores
ini_set('display_errors', 'on');
error_reporting(E_ALL);

// Cargamos composer
require 'vendor/autoload.php';


use Pro\Import\Categories;

$cats = new Categories();
echo $cats;

echo "<hr><strong>Autogeneración de 10 breadcrumbs aleatorios</strong>:<br>";
$media = 0.0;
for ($i = 0; $i < 10; $i++) {
    $j = rand(0, 16);
    $in = microtime(true);
    echo "<br>getBreadcrumbs($j): ";
    echo $cats->getBreadcrumbs($j);
    $out = microtime(true);
    $out -= $in;
    //echo "(".$out.")";
    $media += $out;
}

echo "<hr><br>Media de ejecución: " . ($media / 10) . "ms<br><br>";

echo "<a href='views\mi-arbol.php'>Acceder a la vista de arbol</a>";
