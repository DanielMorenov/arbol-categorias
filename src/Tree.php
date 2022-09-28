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
     * @return string HTML con el arbol jerarquizado
     */
    public function __construct(array $arbol)
    {
        $this->html = "<form action=''><ul class='category-tree'><div id='nube_cat'><span id='sincat'>Sin categorías</span></div><li class='main-category'>Categoría principal</li>";
        $this->html .= SELF::mostrarNodo($arbol);
        $this->html .= "</ul></form>";
    }

    /**
     * Recibe arbol en forma de array y lo muestra en HTML con la estructura <ul> - <li>
     * 
     * @param array $nodo Arbol en formato array
     * 
     * @return string HTML del arbol jerarquizado
     */
    public static function mostrarNodo(array $nodo) : string
    {
        $result = "";
        
        $result .= "<li><i id='label-".$nodo['id_category']."' class='material-icons'>arrow_drop_down</i><div class='contenedor'>";
        $result .= "<input type='checkbox' name = 'categoriasel[]' value = '".$nodo['name']."' class='main-category'>" . $nodo['name'];
        $result .= "<input type='radio' value='".$nodo['id_category']."' name='ignore' class='default-category'>";
        $result .= SELF::mostrarHijos($nodo);
        $result .= "</div></li>";  
        
        return $result;
    }

    /**
     * Recibe un nodo y devuelve una lista desordenada <ul>...</ul> con los hijos 
     * (si los tiene)
     * 
     * @param array $nodo El nodo del que se quieren mostrar los hijos
     * 
     * @return string HTML lista desordenada de hijos del nodo
     */
    public static function mostrarHijos(array $nodo) : string
    {
        $result = "";
        if(count($nodo['children']) ) // Tiene hijos que mostrar
        {
            $result .= "<div id='".$nodo['id_category']."'><ul>"; 
            foreach ($nodo['children'] as $item) 
            {
                $result .= SELF::mostrarNodo($item);
            }
            $result .= "</ul></div>";   
        }
        return $result;
    }

    /**
     * Retorna el arbol en formato HTML
     * 
     * @return string 
     */
    public function get()
    {
        return $this->html;
    }



    /**
     * Magic Function representar objeto en HTML
     */
    public function __toString()
    {
        return $this->html;
    }

}