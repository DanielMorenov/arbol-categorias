<?php

declare(strict_types=1);

namespace Pro\Import;

/**
 * Clase para imprimir en pantalla un arbol en HTML 
 * 
 */
class Tree
{
    /**
     * @var string HTML del arbol
     */
    public $html = "";


    /**
     * Muestra un arbol de categorias jerarquizado en HTML, como listas desordenadas
     * 
     * @param array $arbol Array a jerarquizar.
     * 
     * @return string HTML con el arbol jerarquizado //! ERROR ¿Retorna algo?
     */
    public function __construct(array $arbol)
    {
        $this->html = SELF::mostrarArbol($arbol);
    }

    /**
     * Recibe arbol en forma de array y lo muestra en HTML con la estructura <ul> - <li>
     * 
     * @param array $nodo Arbol en formato array
     * @param int $step Representa el numero de llamadas a la función hechas
     * 
     * @return string HTML del arbol jerarquizado
     */
    public static function mostrarArbol(array $nodo, int $step = 0): string
    {
        $result = "";

        if ($step === 0) $result .= "<ul class='category-tree'><li class='main-category'>Categoría principal</li>";

        $result .= "<li><i id='label-" . ($step + 1) . "' class='material-icons'>arrow_drop_down</i><div class='contenedor'>";
        $result .= "<input type='checkbox' name = 'categoriasel[]' value = '" . $nodo['id_category'] . "' class='main-category'>" . $nodo['name'];
        $result .= "<input type='radio' value='" . $nodo['id_category'] . "' name='ignore' class='default-category'>";
        $result .= SELF::mostrarHijos($nodo, ++$step);
        $result .= "</div></li>";

        if ($step === 0) $result .= "</ul>";

        return $result;
    }

    /**
     * Recibe un nodo y devuelve una lista desordenada <ul>...</ul> con los hijos (si los tiene)
     * 
     * @param array $nodo El nodo del que se quieren mostrar los hijos
     * @param int $step el numero de llamadas a la función hechas
     * 
     * @return string HTML lista desordenada de hijos del nodo
     */
    public static function mostrarHijos(array $nodo, $step): string
    {
        if (!count($nodo['children']))
        {
            return '';
        }

        $result = "<div id='" . $step . "'><ul>";

        foreach ($nodo['children'] as $item) {
            $result .= SELF::mostrarArbol($item, ++$step);
        }

        return $result . "</ul></div>";
    }

    /**
     * Magic Function representar objeto en HTML
     */
    public function __toString()
    {
        return $this->html;
    }
}
