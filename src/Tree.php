<?php
declare(strict_types=1);

namespace Pro\Import;

/**
 * Clase para imprimir en pantalla un arbol en HTML 
 * con los tags indicados (por defecto ul/li) 
 * @param array Category::getRootCategory()->recurseLiteCategTree(0)
 * @param string Nivel superior
 * @param string Nivel Inferior
 * @return string HTML formateado
 */
class Tree 
{
    /**
     * @var string Salida en formato HTML del arbol resultante
     */
    public $salida = "";
    public $categorias = [];
    
    /**
     * @param array
     */
    public function __construct($arbol,$nivel = "ul", $subnivel = "li", $mostrarRaiz = true)
    {
        
        // $tienehijos = count($arbol['children']);
        
        // if($mostrarRaiz && $tienehijos) $this->salida .= "<".$subnivel.">".$arbol['name'];

        // if($tienehijos)  $this->salida .= "<".$nivel.">";   
        // else $this->salida .= "<".$subnivel.">".$arbol['name']."</".$subnivel.">";   

        // if($tienehijos) foreach ($arbol['children'] as $item) $this->salida .= SELF::__construct($item, $nivel, $subnivel, true);
        
        // if($tienehijos) $this->salida .= "</".$nivel.">";   

        // if($mostrarRaiz && $tienehijos) $this->salida .= "</".$subnivel.">";
            
    }

    public static function mostrarArbol(Categories $categorias, $nivel = "ul", $subnivel = "li", $mostrarRaiz = true, $id_raiz = 1)
    {
        $result = "";
        $cat_name = SELF::nombreCategoria($categorias, $id_raiz);
        $tieneHijos = SELF::buscarHijos($categorias,$id_raiz);


        if($mostrarRaiz && $tieneHijos) $result .= "<".$subnivel.">".$cat_name;

        if($tieneHijos)  $result .= "<".$nivel.">";   
        else $result .= "<".$subnivel.">".$cat_name."</".$subnivel.">";   

        if($tieneHijos) foreach ($tieneHijos as $item) $result .= SELF::mostrarArbol($categorias, $nivel, $subnivel, true, (int)$item);
        
        if($tieneHijos) $result .= "</".$nivel.">";   

        if($mostrarRaiz && $tieneHijos) $result .= "</".$subnivel.">";
        
        return $result;
    }

    public static function buscarPadre(Categories $categorias, int $indice)
    {
        foreach($categorias->resultado as $categoria)  if((int)$categoria['id_category']===$indice) return $categoria['id_parent'];
        return false;
    }

    public static function buscarHijos(Categories $categorias, $indice)
    {
        $resultado = [];
        foreach($categorias->resultado as $categoria)  if($categoria['id_parent']==$indice) array_push($resultado,$categoria['id_category']);
        if (count($resultado)) return $resultado;
        return false;
    }

    public static function nombreCategoria(Categories $categorias, $id_category)
    {
        foreach($categorias->resultado as $categoria)  if((int)$categoria['id_category']===$id_category) return $categoria['name'];
        return false;
    }

    public function getBreadcrumbs(int $id_category): string
    {
        $breadcrumbs = "";

        
    
        $nombre = Categories::getNombreCategoria($id_category);
        $breadcrumbs = $nombre;
        
        $padre = Categories::getPadre($id_category);
        // array $padre['id_parent'] , $padre['name']
        

        while($padre['id_parent']!=1)
        {
            $breadcrumbs = $padre['name']." -> ".$breadcrumbs;
            $padre = Categories::getPadre((int)$padre['id_parent']);
            
        }
        
        return $breadcrumbs;
    
    }

    
    public function mostrarCategoria(int $id_category):string
    {

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