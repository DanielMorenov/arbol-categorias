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
    public static function mostrarArbol(array $elementos, string $nivel = "ul", string $subnivel = "li") : string
    {
        $result = "";

        
        $cat_name = $elementos['name'];
        $tieneHijos = count($elementos['children']);

        if($tieneHijos) $result .= "<".$subnivel.">".$cat_name;
        if($tieneHijos)  $result .= "<".$nivel.">";   
        else $result .= "<".$subnivel.">".$cat_name."</".$subnivel.">";   
        if($tieneHijos) foreach ($elementos['children'] as $item) $result .= SELF::mostrarArbol($item, $nivel, $subnivel);
        if($tieneHijos) $result .= "</".$nivel.">";   
        if($tieneHijos) $result .= "</".$subnivel.">";
        
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