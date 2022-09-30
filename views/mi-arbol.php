<?php

// Display errors pero definimos antes modo debug en PS, antes que lo haga PS con el valor que tenga configurada la tienda
//define('_PS_MODE_DEV_', true);

if (!defined('_PS_VERSION_')) {
    include(__DIR__ . '/../../../config/config.inc.php');
}

// A pesar que PS haya podido modificar estos valores en php.ini, los volvemos a configurar para que muestren los errores
ini_set('display_errors', 'on');
error_reporting(E_ALL);

// Cargamos composer
require '../vendor/autoload.php';

use Pro\Import\Categories;
use Pro\Import\Tree;

$cats = new Categories();

$tree = new Tree($cats->arbol);

$html = $tree->get();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Arbol de Categorías</title>
    <link rel="stylesheet" href="css/arbol.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/arbol.js"></script>
</head>

<body>
    <div class='category-frame'>
        <?php echo $html; ?>
    </div>
</body>

</html>