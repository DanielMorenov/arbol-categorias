<?php
    if(!defined('_PS_VERSION_')) include(dirname(__FILE__).'/../../config/config.inc.php');

    require '../vendor/autoload.php';

    use Pro\Import\Tree;
    use Pro\Import\Categories;

    $cats = new Categories();

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
<?php

    echo new Tree($cats->arbol);

?>
    </div>
</body>
</html>