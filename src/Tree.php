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
     * @return string HTML con el arbol jerarquizado //! ¿El constructor retorna algo?
     */
    public function __construct(array $arbol)
    {
        //!ERROR seguir la misma metodologia que en Categories.php
        //!ERROR no utilizar if corto cuando hay else
        if(!SELF::esArbol($arbol)) 
        {
            $this->html = "<p>El arbol pasado no tiene el formato correcto</p>";
        }
        else
        {
            $this->html = "<form action=''><ul class='category-tree'><div id='nube_cat'><span id='sincat'>Sin categorías</span></div><li class='main-category'>Categoría principal</li>";
            $this->html .= SELF::mostrarNodo($arbol);
            $this->html .= "</ul></form>";
        }
    }

    /**
     * Recibe arbol en forma de array y lo muestra en HTML con la estructura <ul> - <li>
     * 
     * @param array $nodo Arbol en formato array
     * @param array $id identificador de nodos
     * @return string HTML del arbol jerarquizado
     */
    public static function mostrarNodo(array $nodo, array $id = [0]) : string
    {
        $result = "<li>";

        if(count($nodo['children'])) $result .= "<i id='label-".SELF::mostrarId($id)."' class='material-icons childrens'>arrow_drop_down</i>";
        else $result .= "<i id='label-".$nodo['id_category']."' class='material-icons'>arrow_right_alt</span></i>";
        
        $result .= "<div class='contenedor'>";
        //! error ¿name = 'categoriasel[]'?  
        //! error ¿id_category? Esto es la clase arbol. Es una clase externa que no depende de categorias depende de un tipo de estructura de array
        $result .= "<input type='checkbox' name = 'categoriasel[]' value = '".$nodo['name']."' class='main-category'>" . $nodo['name'];
        $result .= "<input type='radio' value='".$nodo['id_category']."' name='ignore' class='default-category'>";
        $result .= SELF::mostrarHijos($nodo, $id);
        $result .= "</div></li>";  
        
        return $result;
    }

    /**
     * Recibe un nodo y devuelve una lista desordenada <ul>...</ul> con los hijos 
     * (si los tiene)
     * 
     * @param array $nodo El nodo del que se quieren mostrar los hijos
     * @param array $id Identificación única de nodo
     * @return string HTML lista desordenada de hijos del nodo
     */
    public static function mostrarHijos(array $nodo, array $id = [0]) : string
    {
        $result = "";
        // TODO return early
        if(count($nodo['children']) ) // Tiene hijos que mostrar
        {
            $result .= "<div id='".SELF::mostrarId($id)."'><ul>"; 
            $id[]=0;
            $last = count($id)-1;

            foreach ($nodo['children'] as $item) 
            {
                $result .= SELF::mostrarNodo($item,$id);
                $id[$last]++;
            }
            $result .= "</ul></div>";   
        }
        return $result;
    }

    /**
     * Función empleada para generar un string con un id único dentro del arbol
     * 
     * @param array $id El id del elemento
     * @return string Código de digitos y guiones que identifican el nodo dentro del árbol
     */
    private static function mostrarId(array $id) : string
    {
        //! ERROR es mucho mas sencillo que esto
        $out=(string)$id[0];
        for($i = 1; $i<count($id);$i++)
        {
            $out .= "-".$id[$i];
        }
        return $out;
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
     * Evalua el arbol pasado para ver si es válido. Un arbol válido es un array con un elemento 'children' que a su vez es un array de árboles o un array vacío
     * 
     * @param array $arbol El array a evaluar si es un arbol
     * @return bool True si es un árbol válido, false sino.
     */
    public static function esArbol(array $arbol) : bool
    {
        if(!is_array($arbol) || !isset($arbol['children']) || !is_array($arbol['children'])) {
            return false;
        }
        
        // if(!count($arbol['children'])) return true; // No hace falta esta comprobacion

        foreach($arbol['children'] as $subarbol)
        {
            if(!SELF::esArbol($subarbol)) return false;
        }
        return true;
       
    }


    /**
     * Magic Function representar objeto en HTML
     */
    public function __toString()
    {
        return $this->html;
    }

}