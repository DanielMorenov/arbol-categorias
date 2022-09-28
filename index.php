<?php

// Display errors
// !ERROR: esto no funciona, Das la solución por correo pero no lo resuelves en el codigo
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Pro\Import\Tree; // !ERROR necesitas esta clase?
use Pro\Import\Categories;

// Inicializamos Prestashop
if (!defined('_PS_VERSION_')) {
    include(dirname(__FILE__) . '/../../../config/config.inc.php'); // El proyecto tiene que estar almacenado en la carpeta import como se dijo en las especificaciones del proyecto
}

require 'vendor/autoload.php';


new Exception("Error Processing Request", 500); //! No se suben datos de prueba a git

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
