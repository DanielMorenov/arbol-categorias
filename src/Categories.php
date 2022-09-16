<?php
declare(strict_types=1);

namespace Pro\Import;

class Categories
{

    /**
     * @var array Almacena las categorias de los metodos
     */
    public $resultado = [];

    /**
     * @var array Almacena el arbol
     */
    public $arbol = [];

    
     /**
     * Carga todas las Categorías en la propiedad $resultado
     * 
     * @return array resultado de la consulta SQL
     */
    public function __construct(){

        SELF::getCategories();

    }

    private function getCategories()
    {
        $request = '
            SELECT 
                ' . _DB_PREFIX_ . 'category.id_category, 
                ' . _DB_PREFIX_ . 'category_lang.name, 
                ' . _DB_PREFIX_ . 'category.id_parent, 
                ' . _DB_PREFIX_ . 'category.is_root_category, 
                ' . _DB_PREFIX_ . 'category.active
            FROM ' . _DB_PREFIX_ . 'category 
            INNER JOIN ' . _DB_PREFIX_ . 'category_lang
            ON ' . _DB_PREFIX_ . 'category.id_category = ' . _DB_PREFIX_ . 'category_lang.id_category
            WHERE ' . _DB_PREFIX_ . 'category_lang.id_lang = 1 
            ORDER BY ' . _DB_PREFIX_ . 'category.id_parent ASC
        ';
    
        $this->resultado = \Db::getInstance()->executeS($request);
        $this->arbol = SELF::generaArbol($this);
    }


    /**
     * @param $coleccion Coleccion de elementos a indexar
     * @param $indice Indice dentro del array por los que se quiere indexar
     * 
     * @return array Array indexado
     */
    private function indexaArray(array $coleccion, string $indice)
{
    $indices = [];
    foreach($coleccion as $item)
    {
        $indices[]=(int)$item[$indice];
    }
    $valores = array_values($coleccion);
    
    $final = array_combine($indices, $valores);
    
    return $final;
    
}


    /**
     * Genera arbol de Categories
     * 
     * @param int $nodo El nodo desde el que crear el arbol
     */
    public static function generaArbol(Categories $categorias, int $nodo = 1)
    {
        // Construimos el Arbol en el array de la clase
        $arbol = $categorias->getCategory($nodo);

        // Le añadimos al array los hijos
        $arbol['children']=[]; 
        foreach ($categorias->resultado as $categoria) 
        {
            if((int)$categoria['id_parent']===$nodo) 
            {
                $arbol['children'][] = SELF::generaArbol($categorias, (int)$categoria['id_category']);
            }  
        }
        return $arbol;
    }


    /**
     * Dado un id_category, devuelve el array de la categoría
     * 
     * @param int $id_category id de categoría
     * @return array (int)'id-category', (string)'id-name', (int)'id-parent', (int)'is_root', 'active'
     */
    public function getCategory(int $id_cat = 1)
    {
        foreach ($this->resultado as $categoria) if((int)$categoria['id_category']===$id_cat) return $categoria;
        return false;
    }


    public function __toString()
    {
        return $this->arbol;
    }
}
