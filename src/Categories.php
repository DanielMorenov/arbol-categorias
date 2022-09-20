<?php
declare(strict_types=1);

namespace Pro\Import;

/**
 * Categories obtiene todas las categorías de la BD de PS en una colección de arrays con la siguiente 
 * estructura:
 *  -   'id_category'       Id (indice) de la categoria
 *  -   'name'              Nombre de la categoría en Español
 *  -   'id_parent'         Id del padre de la categoría
 *  -   'is_root_category'  1 si es la raiz de todas las categorias, 0 si no. Solo hay 1 raíz
 *  -   'active'            Indica si la categoría está activa
 */
class Categories
{
    /**
     * Constantes utilizadas en las iteraciones del array de categorias
     */
    const ID_CATEGORY = 'id_category';
    const NAME = 'name';
    const PARENT = 'id_parent';
    const IS_ROOT = 'is_root_category';

    /**
     * @var array Almacena las categorias de los metodos
     */
    private $listado = [];

    private $arbol = [];

    
     /**
     * Carga todas las Categorías en la propiedad $listado y las
     * ordena en un array con indice ID_CATEGORY
     * 
     * @return void
     */
    public function __construct(){
        // Obtenemos categorias de la BD
        $this->listado = SELF::getCategories();
        $this->arbol = SELF::generaArbol();
    }

     /**
     * Genera arbol de elementos
     * @param array $elementos array de elementos
     * @param int $index El nodo desde el que crear el arbol
     * @return array Arbol resultante
     */
    public static function generaArbol(int $indice = 0) : array
    {
        $elementos = SELF::getCategories();
        
        // Si no hay indice definido en la llamada al método, buscamos el primer elemento
        if($indice===0){
            foreach ($elementos as $elemento)
            {
                if($elemento[SELF::IS_ROOT] !== '1') continue;
                // Construimos el Arbol en el array de la clase
                $indice = (int)$elemento[SELF::ID_CATEGORY];
                break;
            }
        }
        
        $arbol = SELF::getElement($elementos, $indice);
        
        // Le añadimos al array los hijos
        foreach ($elementos as $index => $elemento) 
        {
            if((int)$elemento[SELF::PARENT]!==$indice) continue;
            $arbol['children'][] = SELF::generaArbol($index);  
            
        }
    
        return $arbol;
    }

    /**
     * Dado un id, devuelve el elemento
     * 
     * @param array $elementos array de elementos
     * @param int $id_cat id de categoría
     * @return array|bool (int)'id-category', (string)'id_name', (int)'id_parent', (int)'is_root', 'active'
     */
    private static function getElement(array &$elementos, int $id_cat = 0) : array
    {
        echo "<br>getelement: ";
        var_dump($elementos[$id_cat]);
        if($id_cat) return $elementos[$id_cat];
        else return false;
    }

    /**
     * Método interno para obtener los dados de la BD y guardarlos en 
     * la propiedad $listado. Si se indica categoría, devuelve solo esa categoría
     * @param  int $categoria Id de categoria para filtrar resultados
     * @return array ['id_category', 'name', 'id_parent', 'is_root_category', 'active']
     */
    private static function getCategories(int $categoria = 0) : array
    {
        if($categoria) $filtro =  _DB_PREFIX_ . 'category.id_category = "'.$categoria.'"';
        else $filtro = '1';

        $request = '
            SELECT 
                ' . _DB_PREFIX_ . 'category.id_category, 
                ' . _DB_PREFIX_ . 'category_lang.name, 
                ' . _DB_PREFIX_ . 'category.id_parent, 
                ' . _DB_PREFIX_ . 'category.is_root_category
            FROM ' . _DB_PREFIX_ . 'category 
            INNER JOIN ' . _DB_PREFIX_ . 'category_lang
            ON ' . _DB_PREFIX_ . 'category.id_category = ' . _DB_PREFIX_ . 'category_lang.id_category
            WHERE ' . _DB_PREFIX_ . 'category_lang.id_lang = 1 
            AND '.$filtro.' 
            ORDER BY ' . _DB_PREFIX_ . 'category.id_parent ASC, ' . _DB_PREFIX_ . 'category.nleft ASC
        ';
    
        $elementos = \Db::getInstance()->executeS($request);
        $indices = array_column($elementos, 'id_category');
        $valores = array_values($elementos);

        return array_combine($indices, $valores);
    }


    /**
     * Getter del listado de categorías
     * 
     * @return array Listado de categorías
     */
    public function getListado() : array
    {
        return $this->listado;
    }


    /**
     * Getter del arbol de categorías
     * 
     * @return array Array de categorías
     */
    public function getArbol() : array
    {
        return $this->arbol;
    }


    /**
     * Retorna un string con los breadcrumbs de la categoría indicada como argumento
     * 
     * @param int $id_category El id de categoría del que queremos obtener las migas de pan
     * @param array $categorias El array de categorías sobre el que trabajar
     * @return string Breadcrumbs de la categoría
     */
    public function getBreadcrumbs(int $id_category): string
    {
        if(!isset($this->listado[$id_category]) || $this->listado[$id_category]['id_parent']==='0') return "No existe la categoría o es la raíz";
        
        if( $this->listado[$id_category]['is_root_category'] === '1') return $this->listado[$id_category]['name'];
        else return $this->getBreadcrumbs((int)$this->listado[$id_category]['id_parent'])." -> ".$this->listado[$id_category]['name'];

    }


    /**
     * Media: 0.001100871 ms
     * Media: 0.001062971 ms
     * Media: 0.000318582 ms
     * Media: 0.000027259 ms
     * Media: 0.000000699 ms
     */

    /**
     * Magic function para imprimir el array de categorias
     */
    public function __toString()
    {
        $salida = "";
        foreach($this->listado as $categoria)
        {
            $salida .= "<br>Categoria: ".$categoria[SELF::ID_CATEGORY].": ".$categoria[SELF::NAME];
        }
        return $salida;
    }
}
