<?php
declare(strict_types=1);

namespace Pro\Import;

/**
 * Clase para imprimir en pantalla un arbol en HTML 
 * con los tags indicados (por defecto ul/li) 
 * @param Categories $arbol El arbol generado por 
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
     * @param array $categorias Array a jerarquizar
     * @param string $nivel Nivel de jerarquia superior
     * @param string $nivel Nivel de jerarquia inferior
     * @param bool $mostrarRaiz Si se quiere visualizar el elemento raiz
     * 
     * @return string HTML con el arbol jerarquizado
     */

    public function __construct(array $categorias, string $nivel = "ul", string $subnivel = "li", bool $mostrarRaiz = false)
    {
        $this->html = "";

        $this->html = SELF::mostrarArbol($categorias, $nivel, $subnivel, $mostrarRaiz);
        
    }

    /**
     * Recibe arbol en forma de array y lo muestra en HTML con la estructura
     * deseada, por defecto <ul><li></li></ul>
     * 
     * @param array $categorias Arbol en formato array
     * @param string $nivel estructura jerarquica superior
     * @param string $subnivel estructura jerarquica inferior
     * @param bool $mostrarRaiz Si se quiere mostrar la raiz, por defecto TRUE
     * 
     * @return string HTML del arbol jerarquizado
     */
    public static function mostrarArbol(array $categorias, string $nivel = "ul", string $subnivel = "li", bool $mostrarRaiz = true)
    {
        $result = "";
        
        $cat_name = $categorias['name'];
        $tieneHijos = count($categorias['children']);

        if($mostrarRaiz && $tieneHijos) $result .= "<".$subnivel.">".$cat_name;
        if($tieneHijos)  $result .= "<".$nivel.">";   
        else $result .= "<".$subnivel.">".$cat_name."</".$subnivel.">";   
        if($tieneHijos) foreach ($categorias['children'] as $item) $result .= SELF::mostrarArbol($item, $nivel, $subnivel, true, (int)$item['id_category']);
        if($tieneHijos) $result .= "</".$nivel.">";   
        if($mostrarRaiz && $tieneHijos) $result .= "</".$subnivel.">";
        
        return $result;
    }




    















//     public static function mostrarArbolCategories(array $categorias, $nivel = "ul", $subnivel = "li", $mostrarRaiz = true, $id_raiz = 1)
//     {
//         $result = "";
//         $cat_name = SELF::nombreCategoria($categorias, $id_raiz);
//         $tieneHijos = SELF::buscarHijos($categorias, $id_raiz);

//         if($mostrarRaiz && $tieneHijos) $result .= "<".$subnivel.">".$cat_name;
//         if($tieneHijos)  $result .= "<".$nivel.">";   
//         else $result .= "<".$subnivel.">".$cat_name."</".$subnivel.">";   
//         if($tieneHijos) foreach ($tieneHijos as $item) $result .= SELF::mostrarArbol($categorias, $nivel, $subnivel, true, (int)$item);
//         if($tieneHijos) $result .= "</".$nivel.">";   
//         if($mostrarRaiz && $tieneHijos) $result .= "</".$subnivel.">";
        
//         return $result;
//     }

//     /**
//      * Dada un arbol de Categories, y in id_category de un nodo, busca su padre
//      * @param Categories $categorias 
//      * @param int $indice id_category
//      * @return int|bool el indice o false si no tiene padre
//      */
//     public static function buscarPadre(Categories $categorias, int $indice)
//     {
//         foreach($categorias->resultado as $categoria)  if((int)$categoria['id_category']===$indice) return $categoria['id_parent'];
//         return false;
//     }

//     /**
//      * Dada un arbol de Categories, y in id_category de un nodo, busca sus hijos
//      * @param Categories $categorias 
//      * @param int $indice id_category
//      * @return array|bool el array de hijos o false si no tiene
//      */
//     public static function buscarHijos(Categories $categorias, int $indice)
//     {
//         $resultado = [];
//         foreach($categorias->resultado as $categoria) if((int)$categoria['id_parent']===$indice) array_push($resultado,$categoria['id_category']);
                
//         if (count($resultado)) return $resultado;
//         else return false;
//     }

//     /**
//      * Dada un arbol de Categories, y in id_category de un nodo, busca su nombre
//      * @param Categories $categorias 
//      * @param int $indice id_category
//      * @return int|bool el nombre o false si no tiene padre
//      */
//     public static function nombreCategoria(Categories $categorias, $id_category)
//     {
//         foreach($categorias->resultado as $categoria) if((int)$categoria['id_category']===$id_category) return $categoria['name'];
            
//         return false;
//     }

//     /**
//      * Dada un arbol de Categories, y in id_category de un nodo, busca su nombre
//      * @param Categories $categorias 
//      * @param int $indice id_category
//      * @return int|bool el nombre o false si no tiene padre
//      */
//     public static function nombreCategoriaArbol(array $categorias, int $id_category)
//     {
//         if($categorias['id_category']===$id_category) return $categorias['name'];
//         else
//         {
//             $hijos = $categorias['children'];
//             var_dump($categorias['children']);
//             foreach ($hijos as $hijo) SELF::nombreCategoriaArbol($hijo, $id_category);
//         }
//         return false;
//     }

//     /**
//      * Dada un arbol de Categories, y in id_category de un nodo, busca su padre
//      * @param Categories $categorias 
//      * @param int $indice id_category
//      * @return string un string con las migas de pan de la categoria
//      */
//     public static function getBreadcrumbs(Categories $categorias, int $id_category): string
//     {
//         $breadcrumbs = "";

//         $nombre = SELF::nombreCategoria($categorias, $id_category);
//         $breadcrumbs = $nombre;
        
//         $padre = SELF::buscarPadre($categorias, $id_category);      

//         while($padre)
//         {
//             $nombre = (string) SELF::nombreCategoria($categorias, (int)$padre);
//             $breadcrumbs = $nombre." -> ".$breadcrumbs;
//             $padre = (int) SELF::buscarPadre($categorias,(int)$padre);
//         }
        
//         return $breadcrumbs;
//     }

//     /**
//      * 
//      */
    public function __toString()
    {
        return $this->html;
    }


    
}

// <li>Raíz
//     <ul>
//         <li>Inicio
//             <ul>
//                 <li>Clothes
//                     <ul>
//                         <li>Men</li>
//                         <li>Women</li>
//                     </ul>
//                 </li>
//                 <li>Accesorios
//                     <ul>
//                         <li>Stationery</li>
//                         <li>Home Accessories</li>
//                     </ul>
//                 </li>
//                 <li>Art</li>
//             </ul>
//         </li>
//     </ul>
// </li>

// array(1) { 
//     [0]=> array(4) { 
//         ["id_category"]=> string(1) "1" 
//         ["name"]=> string(5) "Raíz" 
//         ["id_parent"]=> string(1) "0" 
//         ["children"]=> array(1) { 
//             [0]=> array(4) { 
//                 ["id_category"]=> string(1) "2" 
//                 ["name"]=> string(6) "Inicio" 
//                 ["id_parent"]=> string(1) "1" 
//                 ["children"]=> array(5) { 
//                     [0]=> array(4) { 
//                         ["id_category"]=> string(1) "3" 
//                         ["name"]=> string(7) "Clothes" 
//                         ["id_parent"]=> string(1) "2" 
//                         ["children"]=> array(2) { 
//                             [0]=> array(4) { 
//                                 ["id_category"]=> string(1) "4" 
//                                 ["name"]=> string(3) "Men" 
//                                 ["id_parent"]=> string(1) "3" 
//                                 ["children"]=> array(0) { } } 
//                             [1]=> array(4) { 
//                                 ["id_category"]=> string(1) "5" 
//                                 ["name"]=> string(5) "Women" 
//                                 ["id_parent"]=> string(1) "3" 
//                                 ["children"]=> array(0) { 

//                                 } 
//                             } 
//                         } 
//                     } 
//                     [1]=> array(4) { 
//                         ["id_category"]=> string(1) "6" 
//                         ["name"]=> string(10) "Accesorios" 
//                         ["id_parent"]=> string(1) "2" 
//                         ["children"]=> array(2) { 
//                             [0]=> array(4) { 
//                                 ["id_category"]=> string(1) "7" 
//                                 ["name"]=> string(10) "Stationery" 
//                                 ["id_parent"]=> string(1) "6" 
//                                 ["children"]=> array(0) { } 
//                             } 
//                             [1]=> array(4) { 
//                                 ["id_category"]=> string(1) "8" 
//                                 ["name"]=> string(16) "Home Accessories" 
//                                 ["id_parent"]=> string(1) "6" 
//                                 ["children"]=> array(0) { } 
//                             } 
//                         } 
//                     } 
//                     [2]=> array(4) { 
//                         ["id_category"]=> string(1) "9" 
//                         ["name"]=> string(3) "Art" 
//                         ["id_parent"]=> string(1) "2" 
//                         ["children"]=> array(0) { } 
//                     } 
//                     [3]=> array(4) { 
//                         ["id_category"]=> string(2) "10" 
//                         ["name"]=> string(6) "Prueba" 
//                         ["id_parent"]=> string(1) "2" 
//                         ["children"]=> array(1) { 
//                             [0]=> array(4) { 
//                                 ["id_category"]=> string(2) "11" 
//                                 ["name"]=> string(14) "dentrodeprueba" 
//                                 ["id_parent"]=> string(2) "10" 
//                                 ["children"]=> array(1) { [0]=> array(4) { ["id_category"]=> string(2) "12" ["name"]=> string(22) "dentrodedentrodeprueba" ["id_parent"]=> string(2) "11" ["children"]=> array(1) { [0]=> array(4) { ["id_category"]=> string(2) "13" ["name"]=> string(10) "masadentro" ["id_parent"]=> string(2) "12" ["children"]=> array(0) { } } } } }