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


    public function __toString()
    {
        return $this->html;
    }

}