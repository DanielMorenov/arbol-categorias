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

    
     /**
     * Carga todas las Categorías en la propiedad $listado y las
     * ordena en un array con indice ID_CATEGORY
     * 
     * @return void
     */
    public function __construct(){
        // Obtenemos categorias de la BD
        $this->listado = SELF::getCategories();

        // Las indexamos por el ID_CATEGORY
        $this->listado = SELF::indexaArray( $this->listado, SELF::ID_CATEGORY);  

    }

    /**
     * Método interno para obtener los dados de la BD y guardarlos en 
     * la propiedad $listado. Si se indica categoría, devuelve solo esa categoría
     * @return array ['id_category', 'name', 'id_parent', 'is_root_category', 'active']
     */
    private function getCategories($categoria = false) : array
    {
        if($categoria) $filtro =  _DB_PREFIX_ . 'category.id_category = "'.$categoria.'"';
        else $filtro = '1';

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
            AND '.$filtro.' 
            ORDER BY ' . _DB_PREFIX_ . 'category.id_parent ASC, ' . _DB_PREFIX_ . 'category.nleft ASC
        ';
    
        return \Db::getInstance()->executeS($request);
    }


    /**
     * Función interna que indexa los elementos por su indice ID_CATEGORY
     * @param $indice Indice dentro del array por los que se quiere indexar
     * 
     * @return array Array indexado
     */
    private static function indexaArray(array $elementos, string $indice) : array
    {
        $indices = array_column($elementos,  $indice);
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
     * Retorna un string con los breadcrumbs de la categoría indicada como argumento
     * 
     * @param int $id_category El id de categoría del que queremos obtener las migas de pan
     * @return string Breadcrumbs de la categoría
     */
    public static function getBreadcrumbs(int $id_category): string
    {
        $categorias = SELF::getCategories();
        $categorias = SELF::indexaArray($categorias, SELF::ID_CATEGORY);  

        $salida = $categorias[$id_category][SELF::NAME];

        foreach ($categorias as $categoria)
        {
            if( ( (int)$categoria[SELF::ID_CATEGORY] !== (int)$categorias[$id_category][SELF::PARENT] ) || ( (int)$categoria[SELF::IS_ROOT]!== 0)  ) continue;
            $salida = SELF::getBreadcrumbs((int)$categoria[SELF::ID_CATEGORY])." -> ".$salida;
         
            break;
        }
        return $salida; 
    }

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
