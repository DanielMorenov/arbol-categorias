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
     * Muestra un arbol de categorias jerarquizado y en HTML, con parámetros
     * de jerarquia y visualización opcionales
     * 
     * @param Categories $categorias Objeto que contiene array a jerarquizar
     * @param string $nivel Nivel de jerarquia superior
     * @param string $nivel Nivel de jerarquia inferior
     * @param bool $mostrarRaiz Si se quiere visualizar el elemento raiz
     * @param int $id_raiz El id_category del nodo raiz
     * @return string HTML con el arbol jerarquizado
     */
    public static function mostrarArbol(Categories $categorias, $nivel = "ul", $subnivel = "li", $mostrarRaiz = true, $id_raiz = 1)
    {
        $result = "";
        $cat_name = SELF::nombreCategoria($categorias, $id_raiz);
        $tieneHijos = SELF::buscarHijos($categorias, $id_raiz);

        if($mostrarRaiz && $tieneHijos) $result .= "<".$subnivel.">".$cat_name;
        if($tieneHijos)  $result .= "<".$nivel.">";   
        else $result .= "<".$subnivel.">".$cat_name."</".$subnivel.">";   
        if($tieneHijos) foreach ($tieneHijos as $item) $result .= SELF::mostrarArbol($categorias, $nivel, $subnivel, true, (int)$item);
        if($tieneHijos) $result .= "</".$nivel.">";   
        if($mostrarRaiz && $tieneHijos) $result .= "</".$subnivel.">";
        
        return $result;
    }

    /**
     * Dada un arbol de Categories, y in id_category de un nodo, busca su padre
     * @param Categories $categorias 
     * @param int $indice id_category
     * @return int|bool el indice o false si no tiene padre
     */
    public static function buscarPadre(Categories $categorias, int $indice)
    {
        foreach($categorias->resultado as $categoria)  if((int)$categoria['id_category']===$indice) return $categoria['id_parent'];
        return false;
    }

    /**
     * Dada un arbol de Categories, y in id_category de un nodo, busca sus hijos
     * @param Categories $categorias 
     * @param int $indice id_category
     * @return array|bool el array de hijos o false si no tiene
     */
    public static function buscarHijos(Categories $categorias, int $indice)
    {
        $resultado = [];
        foreach($categorias->resultado as $categoria) if((int)$categoria['id_parent']===$indice) array_push($resultado,$categoria['id_category']);
                
        if (count($resultado)) return $resultado;
        else return false;
    }

    /**
     * Dada un arbol de Categories, y in id_category de un nodo, busca su nombre
     * @param Categories $categorias 
     * @param int $indice id_category
     * @return int|bool el nombre o false si no tiene padre
     */
    public static function nombreCategoria(Categories $categorias, $id_category)
    {
        foreach($categorias->resultado as $categoria) if((int)$categoria['id_category']===$id_category) return $categoria['name'];
            
        return false;
    }

    /**
     * Dada un arbol de Categories, y in id_category de un nodo, busca su padre
     * @param Categories $categorias 
     * @param int $indice id_category
     * @return string un string con las migas de pan de la categoria
     */
    public static function getBreadcrumbs(Categories $categorias, int $id_category): string
    {
        $breadcrumbs = "";

        $nombre = SELF::nombreCategoria($categorias, $id_category);
        $breadcrumbs = $nombre;
        
        $padre = SELF::buscarPadre($categorias, $id_category);      

        while($padre)
        {
            $nombre = (string) SELF::nombreCategoria($categorias, (int)$padre);
            $breadcrumbs = $nombre." -> ".$breadcrumbs;
            $padre = (int) SELF::buscarPadre($categorias,(int)$padre);
        }
        
        return $breadcrumbs;
    }

    /**
     * 
     */
    public function __toString()
    {
        return $this->salida;
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