<?php
declare(strict_types=1);

namespace Pro\Import;

/**
 * Clase para imprimir en pantalla un arbol en HTML 
 * con los tags indicados (por defecto ul/li) 
 * @param array $arbol El arbol generado por 
 * @param string Nivel superior
 * @param string Nivel Inferior
 * @return string HTML formateado
 */
class Tree 
{
     /**
     * Constantes utilizadas en las iteraciones del array de elementos
     */
    const ID_ELEMENT = 'id_category';
    const NAME = 'name';
    const PARENT = 'id_parent';
    const IS_ROOT = 'is_root_category';


    /**
     * @var string HTML del arbol
     */
    public $html = "";

    /**
     * @var array Almacena el arbol
     */
    public $arbol = [];


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
        // Generamos arbol
        $this->arbol = SELF::generaArbol($elementos);
        // Generamos el HTML
        $this->html = SELF::mostrarArbol($this->arbol, $nivel, $subnivel);
        
    }


     /**
     * Genera arbol de elementos
     * @param array $elementos array de elementos
     * @param int $index El nodo desde el que crear el arbol
     * @return array Arbol resultante
     */
    public static function generaArbol(array $elementos, int $index = 0) : array
    {
        // Si no hay indice definido en la llamada al método, buscamos el primer elemento
        if($index===0){
            foreach ($elementos as $index => $elemento)
            {
                if($elemento[SELF::IS_ROOT] !== '1') continue;
                // Construimos el Arbol en el array de la clase
                $index = (int)$elemento[SELF::ID_ELEMENT];
                break;
            }
        }
        
        $arbol = SELF::getElement($elementos, $index);
        
        // Le añadimos al array los hijos
        foreach ($elementos as $elemento) 
        {
            if($elemento[SELF::PARENT]!==$arbol[SELF::ID_ELEMENT]) continue;
            $arbol['children'][] = SELF::generaArbol($elementos,(int)$elemento[SELF::ID_ELEMENT]);  
        }
    
        return $arbol;
    }

    /**
     * Dado un id, devuelve el elemento
     * 
     * @param array $elementos array de elementos
     * @param int $id_cat id de categoría
     * @return array|bool (int)'id-category', (string)'id-name', (int)'id-parent', (int)'is_root', 'active'
     */
    public static function getElement(array $elementos, int $id_cat = 0) : array
    {
        // foreach ($this->listado as $elemento) if((int)$elemento[SELF::NAME]===$id_cat) return $elemento;
        // return false;
        if($id_cat) return $elementos[$id_cat];
        else return false;
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

        
        $cat_name = $elementos[SELF::NAME];
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