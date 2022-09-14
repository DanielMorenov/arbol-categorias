<?php
declare(strict_types=1);

namespace Pro\Import;

use TreeCore;

/**
 * Clase para imprimir en pantalla un arbol en HTML 
 * con los tags indicados (por defecto ul/li) 
 * @param array Category::getRootCategory()->recurseLiteCategTree(0)
 * @param string Nivel superior
 * @param string Nivel Inferior
 * @return string HTML formateado
 */
class Tree extends TreeCore
{
    public $salida = "";
    
    /**
     * @param array
     */
    public function __construct($arbol, $nivel = "ul", $subnivel = "li")
    {
        $tienehijos = count($arbol['children']);

        if($tienehijos) $this->salida .= "<".$subnivel.">".$arbol['name']."<".$nivel.">";   
        else $this->salida .= "<".$subnivel.">".$arbol['name']."</".$subnivel.">";   

        if($tienehijos) // Tiene Hijos
        {
            foreach ($arbol['children'] as $item) {
                
                $this->salida .= SELF::__construct($item, $nivel, $subnivel);
            }
        }
        
        if($tienehijos) $this->salida .= "</".$nivel."></".$subnivel.">";   
       
    }

    public function __toString()
    {
        return $this->salida;
    }

    
}