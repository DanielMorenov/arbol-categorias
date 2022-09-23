<?php
declare(strict_types=1);

namespace Pro\Import;

/**
 * Clase para imprimir en pantalla un arbol en HTML 
 * con los tags indicados (por defecto ul/li) 
 * @param array $arbol FORMATO de array MINIMO:
 *      ['id]: int|string id del elemento
 *      ['id_parent']: int|string id del padre del elemento
 *      ['name']: int|string Nombre del elemento
 *      ['is_root']: bool|int|string true si es el elemento raiz, false si no
 * @param string Nivel superior
 * @param string Nivel Inferior
 * @return string HTML formateado
 */
class Tree 
{
    /**
     * @var string HTML del arbol
     */
    public $html = "";


    /**
     * Muestra un arbol de categorias jerarquizado y en HTML, con parámetros
     * de jerarquia y visualización opcionales
     * 
     * @param array $elementos Array a jerarquizar.
     * @param string $nivel Nivel de jerarquia superior
     * @param string $nivel Nivel de jerarquia inferior
     * 
     * @return string HTML con el arbol jerarquizado
     */
    public function __construct(array $elementos, string $nivel = "ul", string $subnivel = "li")
    {
        
        // Generamos el HTML
        $this->html = SELF::mostrarArbol($elementos, $nivel, $subnivel);
        
    }

    /**
     * Recibe arbol en forma de array y lo muestra en HTML con la estructura
     * deseada, por defecto <ul><li></li></ul>
     * 
     * @param array $elementos Arbol en formato array
     * @param string $nivel estructura jerarquica superior
     * @param string $subnivel estructura jerarquica inferior
     * 
     * @return string HTML del arbol jerarquizado
     */
    public static function mostrarArbol(array $nodo) : string
    {
        $result = "";

        // ! ¿Para que se utilizan las funciones staticas y las funciones normales?
        // TODO esta funcion se ve demasiado mal, tiene que ser rapidamente legible
        // TODO esto genera el arbol pero no es como el de prestashop
       
        if((bool)$nodo['is_root_category']) $result .= "<ul>";
        
        $result .= "<li>" . $nodo['name'] . SELF::mostrarHijos($nodo) . "</li>";  

        if($nodo['is_root_category']) $result .= "</ul>";
        
        return $result;
    }

    public static function mostrarHijos(array $nodo) : string
    {
        $result = "";
        if(count($nodo['children']) ) // Tiene hijos que mostrar
        {
            $result .= "<ul>"; 
            foreach ($nodo['children'] as $item) $result .= SELF::mostrarArbol($item);
            $result .= "</ul>";   
        }
        return $result;
    }


    /**
     * Magic Function representar objeto en HTML
     */
    public function __toString()
    {
        return $this->html;
    }

}

// <ul class="category-tree">        
//      <li class="less">
//          Inicio                   <--------- is_root_category               
//          <ul>
//              <li class="less">
//                  Clothes           <--------- 2º   
//                  <ul>
//                      <li>
//                         Men        <--------- 3ª   
//                      </li>
//                      <li>
//                          Women     <--------- 4ª
//                      </li>
//                  </ul>
//              </li>
//              <li class="less">
//                 Accesorios
//                 <ul>
//                      <li>
//                          Stationery
//                      </li>
//                      <li>
//                          Home Accessories
//                      </li>
//                 </ul>
//              </li>
//              <li>
//                 Art
//              </li>
//          </ul>
//      </li>
// </ul>