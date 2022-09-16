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
            ORDER BY ' . _DB_PREFIX_ . 'category.id_parent ASC,  ' . _DB_PREFIX_ . 'category.nleft ASC
        ';
        
        $this->resultado = \Db::getInstance()->executeS($request);
        $this->arbol = SELF::generaArbol($this);

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
        foreach ($categorias->resultado as $categoria) if((int)$categoria['id_parent']===$nodo) array_push($arbol['children'],SELF::generaArbol($categorias, (int)$categoria['id_category']));
            
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

    

    /**
     * Dato un id_category, delvuelve un array de id_category de sus hijos,
     * o false si no tiene
     */

    

    // /**
    //  * Devuelve un arbol de categorias:
    //  * Array -> (int)'id-category', (string)'id-name', (int)'id-parent', (int)'is_root', (array)'children'
    //  * @param int nodo
    //  * @return array
    //  */
    // public function generarArbol(int $nodo)
    // {
    //     $arbol = SELF::cargarCategoria($nodo);
    //     var_dump($arbol);
    //     $subcategorias = SELF::cargarCategoriasPorPadre($nodo);
    //     //var_dump($subcategorias);
    //     foreach ($subcategorias[0] as $subcategoria) $arbol['children'] = SELF::generarArbol((int)$subcategoria['id_category']);
       
    //     return $arbol;

        
    // }
   
    // /**
    //  * Dado un indice, devuelve el id_category de su padre o false si es huérfano
    //  * 
    //  * @param int $indice 
    //  */
    // public function buscarPadre(int $indice) : mixed
    // {
    //     foreach($this->resultado as $index => $categoria)  if($categoria['id_category']==$indice) return $index;
    //     return false;
    // }
    // /**
    //  * Dado un indice, devuelve un array con los id_category de sus hijos
    //  * 
    //  * @param int $indice id_category del padre
    //  * @return array $result con el id_category de los hijos del padre
    //  */
    // public function buscarHijos(int $indice) : array
    // {
    //     $result = [];
    //     foreach($this->resultado as $categoria)  if($categoria['id_parent']==$indice)  array_push($result,(int)$categoria['id_category']);
    
    //     return $result;
    // }
    // /**
    //  * Dado un indice, devuelve el nombre de la categoria
    //  * 
    //  * @param int $indice con el id_category de la categoría
    //  * @return string|bool muestra el nombre de la categoria con id_category $indice
    //  */
    // public function mostrarCategoria($indice) : mixed
    // {
    //     foreach($this->resultado as $categoria)  if($categoria['id_category']==$indice)  return $categoria['name'];
    
    //     return false;
    // }
   
    // /**
    //  * Carga las Categorías que pertenecen a un $nivel
    //  * @param int $padre
    //  * @return array resultado de la consulta SQL
    //  */
    // public static function cargarCategoriasPorPadre(int $padre): array
    // {
    //     $db = \Db::getInstance();

    //     $request = '
    //         SELECT ' . _DB_PREFIX_ . 'category.id_category, ' . _DB_PREFIX_ . 'category_lang.name, ' . _DB_PREFIX_ . 'category.id_parent 
    //         FROM ' . _DB_PREFIX_ . 'category 
    //         INNER JOIN ' . _DB_PREFIX_ . 'category_lang
    //         ON ' . _DB_PREFIX_ . 'category.id_category = ' . _DB_PREFIX_ . 'category_lang.id_category
    //         WHERE ' . _DB_PREFIX_ . 'category_lang.id_lang = 1 AND ' . _DB_PREFIX_ . 'category.id_parent = '.$padre.'
    //         ORDER BY ' . _DB_PREFIX_ . 'category.id_parent ASC,  ' . _DB_PREFIX_ . 'category.nleft ASC';
    //     $result = $db->executeS($request);

    //     return $result;
    // }

    // /**
    //  * Carga la categoría pedida id_category - name - id_parent
    //  * 
    //  * @param int $id_category ID de la categoría
    //  * @return array Resultado de la consulta SQL
    //  */
    // public static function cargarCategoria(int $id_category): array
    // {
    //     $db = \Db::getInstance();

    //     $request = '
    //         SELECT ' . _DB_PREFIX_ . 'category.id_category, ' . _DB_PREFIX_ . 'category_lang.name, ' . _DB_PREFIX_ . 'category.id_parent 
    //         FROM ' . _DB_PREFIX_ . 'category 
    //         INNER JOIN ' . _DB_PREFIX_ . 'category_lang
    //         ON ' . _DB_PREFIX_ . 'category.id_category = ' . _DB_PREFIX_ . 'category_lang.id_category
    //         WHERE ' . _DB_PREFIX_ . 'category_lang.id_lang = 1 AND ' . _DB_PREFIX_ . 'category.id_category = '.$id_category.'
    //         ORDER BY ' . _DB_PREFIX_ . 'category.id_parent ASC,  ' . _DB_PREFIX_ . 'category.nleft ASC';
    //     $result = $db->executeS($request);

    //     return $result;
    // }

    // /**
    //  * Devuelve un arbol (array) a partir del id del nodo indicado
    //  * 
    //  * @param int $padre El ID del nodo a partir del cual contruir el arbol
    //  * @return array Devuelve un arbol en array
    //  */
    // public static function cargarArbol(int $padre = 0): array
    // {
    //     $subcategorias = SELF::cargarCategoriasPorPadre($padre);
       
    //     foreach ($subcategorias as &$subcategoria) $subcategoria['children'] = SELF::cargarArbol((int)$subcategoria['id_category']);
       
    //     return $subcategorias;
    // }

    // /**
    //  * Retorna las migas de pan de la categoría
    //  * 
    //  * @param int $id_category ID de la categoria eb BD
    //  * @return string Ej: Inicio -> ropa -> hombre
    //  */
    // public static function getBreadcrumbs(int $id_category): string
    // {
    //     $breadcrumbs = "";
    
    //     $nombre = Categories::getNombreCategoria($id_category);
    //     $breadcrumbs = $nombre;
       
    //     $padre = Categories::getPadre($id_category);
    //     // array $padre['id_parent'] , $padre['name']
        

    //     while($padre['id_parent']!=1)
    //     {
    //         $breadcrumbs = $padre['name']." -> ".$breadcrumbs;
    //         $padre = Categories::getPadre((int)$padre['id_parent']);
            
    //     }
        
    //     return $breadcrumbs;
    // }

    // /**
    //  * Obtener el id del Padre de la categoría
    //  * 
    //  * @param int $id_category id de la categoría a obtener padre
    //  * @return array Obtenemos tupla de (id_category, name) del padre
    //  */
    // public static function getPadre(int $id_category): array
    // {
    //     $db = \Db::getInstance();

    //     $request = '
    //         SELECT ' . _DB_PREFIX_ . 'category.id_parent, ' . _DB_PREFIX_ . 'category_lang.name
    //         FROM ' . _DB_PREFIX_ . 'category 
    //         INNER JOIN ' . _DB_PREFIX_ . 'category_lang
    //         ON ' . _DB_PREFIX_ . 'category.id_parent = ' . _DB_PREFIX_ . 'category_lang.id_category
    //         WHERE ' . _DB_PREFIX_ . 'category_lang.id_lang = 1 AND ' . _DB_PREFIX_ . 'category.id_category = '.$id_category.'
    //         LIMIT 1';
    //     $result = $db->executeS($request);

    //     return $result[0];
    // }

    // /**
    //  * Obtener el nombre de una categoría
    //  * 
    //  * @param int $id_category id de la categoría
    //  * @return string $nombre El nombre de la categoría
    //  */
    // public static function getNombreCategoria($id_category): string
    // {
    //     $db = \Db::getInstance();

    //     $request = '
    //         SELECT ' . _DB_PREFIX_ . 'category_lang.name
    //         FROM ' . _DB_PREFIX_ . 'category 
    //         INNER JOIN ' . _DB_PREFIX_ . 'category_lang
    //         ON ' . _DB_PREFIX_ . 'category.id_category = ' . _DB_PREFIX_ . 'category_lang.id_category
    //         WHERE ' . _DB_PREFIX_ . 'category_lang.id_lang = 1 AND ' . _DB_PREFIX_ . 'category.id_category = '.$id_category.'
    //         LIMIT 1';
    //     $result = $db->executeS($request);

    //     return $result[0]['name'];
    // }

    public function __toString()
    {
        return $this->arbol;
    }
}
