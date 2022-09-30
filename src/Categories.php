<?php

declare(strict_types=1);

namespace Pro\Import;

/**
 * Categories obtiene en $categorias todas las categorías de la BD de PS en una colección de arrays con la siguiente 
 * estructura:
 *  -   'id_category'       Id (indice) de la categoria
 *  -   'name'              Nombre de la categoría en Español
 *  -   'id_parent'         Id del padre de la categoría
 *  -   'is_root_category'  1 si es la raiz de todas las categorias, 0 si no. Solo hay 1 raíz
 * 
 * Posteriormente construye el arbol de categorias en la propiedad $arbol y una matriz padre-hijo en
 * la propiedad $mapa, para recorrer facilmente los hijos de cada nodo del arbol
 * 
 */
class Categories
{
    /**
     * @var array Almacena el arbol de categorías, en un array con la estructura: ["id_category"] ["name"] ["id_parent"] ["is_root_category"] ["children"]
     */
    public $arbol = [];

    /**
     * @var array Almacena el array de categorias sin jerarquizar, indexadas por id_category: array["id_category"] = ["id_category"] ["name"] ["id_parent"] ["is_root_category"] 
     */
    public $categorias = [];

    /**
     * @var array Almacena las categorias con estructura ['id_parent']['id_category'] => $categoria
     */
    public $mapa = [];

    /**
     * @var int id_category de la categoría raiz del arbol de categorias ('is_root_category' == '1')
     */
    public $raiz = 0;

    /**
     * Carga todas las categorías recogidas de la BD en el $arbol y guarda la $raiz del arbol, el array de $categorias y el $mapa de [padres][categorias]
     * 
     * @return void 
     */
    public function __construct()
    {
        if (!$this->getCategories()) {
            throw new \Exception("No se pueden cargar las categorías", 500);
        }

        // Construimos el árbol de categorias
        $this->arbol['children'] = $this->generaArbol($this->raiz);
    }

    /**
     * Genera arbol de categorias partiendo de la categoria raiz y almacena el arbol en la propiedad $this->arbol.
     *
     * @param int $indice El nodo desde el que crear el arbol. 0 para la raiz (por defecto)
     * 
     * @return array Arbol con raiz el nodo cuya id_category se pasa como argumento $indice, o un array vacío si no obtiene las categorias.
     */
    private function generaArbol(int $indice = 0): array
    {
        $arbol = [];
        if (!isset($this->mapa[$indice])) {
            return $arbol;
        }


        foreach ($this->mapa[$indice] as $index => $categoria) {
            $categoria['children'] = $this->generaArbol($index);
            $arbol[] = $categoria;
        }

        return $arbol;
    }

    /**
     * Método interno para obtener las categorías de la BD y guardarlas en la propiedad $categorias, $mapa y $raiz. Se guardan indexando id_category para agilizar las búsquedas.
     * 
     * @return bool Si no encuentra categorías o la raiz, retorna falso, si obtenemos un array no vacío, devuelve true
     */
    private function getCategories(): bool
    {
        //! id lang puede cambiar.
        $request = '
            SELECT 
                c.id_category, 
                cl.name, 
                c.id_parent, 
                c.is_root_category
            FROM ' . _DB_PREFIX_ . 'category as c
            INNER JOIN ' . _DB_PREFIX_ . 'category_lang as cl
            ON c.id_category = cl.id_category
            WHERE cl.id_lang = 1
            ORDER BY c.id_parent ASC, c.nleft ASC
        ';

        $elementos = \Db::getInstance()->executeS($request);

        if (!is_array($elementos)) {
            return false;
        }

        foreach ($elementos as $categoria) {
            $this->categorias[$categoria['id_category']] = $categoria;
            $this->mapa[$categoria['id_parent']][$categoria['id_category']] = $categoria;
            if ((bool)$categoria['is_root_category']) {
                $this->raiz = (int)$categoria['id_category'];
                $this->arbol = $categoria;
            }
        }

        if ($this->raiz === 0) {
            return false;
        }

        return true;
    }

    /**
     * Retorna un string con los breadcrumbs de la categoría indicada como argumento
     * 
     * @param int $id_category El id de categoría del que queremos obtener las migas de pan
     * 
     * @return string Breadcrumbs de la categoría si existe y no es la raiz, un string vacío en caso contrario.
     */
    public function getBreadcrumbs(int $id_category): string
    {
        if (!isset($this->categorias[$id_category]) || $this->categorias[$id_category]['id_parent'] === '0') {
            return "No existe la categoría o es la Raíz";
        }

        if ($id_category === $this->raiz) {
            return $this->categorias[$id_category]['name'];
        }

        return $this->getBreadcrumbs((int)$this->categorias[$id_category]['id_parent']) . " -> " . $this->categorias[$id_category]['name'];
    }

    /**
     * Imprime las Categorias y el árbol de categorías en pantalla con formato
     * 
     * @return string HTML básico con tabulaciones para preformatear las categorias y su arbol en pantalla
     */
    public function __toString(): string
    {
        $salida = "<br>Categorias obtenidas:<br>";
        foreach ($this->categorias as $index => $categoria) {
            $salida .= "<br>Categoria[" . $index . "]: " . $categoria['name'];
        }

        return $salida . "<br><br>Arbol de Categorias preformateado:<br><br>" . $this->arbol['name'] . "<br>" . $this->mostrarHijos((int)$this->arbol['id_category']);
    }

    /**
     * Función recursiva usada para formatear el arbol de categorías en pantalla, mostrando una pequeña tabulación para los hijos
     * 
     * @param int $id el id_category de la categoría a mostrar sus hijos
     * @param int $deep nivel de profundidad del arbol para calcular las tabulaciones
     * 
     * @return string $salida HTML formateado para mostrar en pantalla
     */
    private function mostrarHijos(int $id, int $deep = 0): string
    {
        $deep += 3;
        $salida = "";
        if (!isset($this->mapa[$id])) return $salida;
        foreach ($this->mapa[$id] as $hijo) {
            for ($i = 0; $i < $deep; $i++) $salida .= "&nbsp;";
            $salida .= $hijo['name'] . "<br>" . $this->mostrarHijos((int)$hijo['id_category'], $deep);
        }
        return $salida;
    }
}
