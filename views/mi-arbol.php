<?php

use Pro\Import\Categories;
use Pro\Import\Tree;

if (!defined('_PS_VERSION_')) {
    include(dirname(__FILE__) . '/../../../config/config.inc.php'); // El proyecto tiene que estar almacenado en la carpeta import como se dijo en las especificaciones del proyecto
}

require '../vendor/autoload.php';

$cats = new Categories();
$tree = new Tree($cats->arbol);
// $html = $tree->get(); // TODO hay que hacer la funcion get(), la clase sino no tiene sentido ni usabilidad

//!Error las categorias sin hijos se muestran como si los tuviese
//! ERROR el archivo views/arbol.tpl no se utiliza y no esta almacenado correctamente views/TEMPLATES/arbol.tpl
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
        <?php echo $tree; ?>
    </div>
</body>

</html>