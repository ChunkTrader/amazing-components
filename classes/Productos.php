<?php
require_once 'classes/Producto.php';
require_once 'classes/Coleccion.php';

class Productos extends Coleccion{
	protected $tabla;

	public function __construct(Controlador $controlador) {
		$this->controlador = $controlador;
		$this->tabla = 'Productos';
		$this->orden = 'nombre';
		$this->miembro = 'Producto';
	}

	function setItemBD(Item $item) {
		$item->calcDisponibilidad();
		return parent::setItemBD($item);
	}

	function addItemBD(Item $item) {
		$item->calcDisponibilidad();

		//Los productos siempre estan activos al crearlos
		$item->setPropiedad('activo', 1);
		
		return parent::addItemBD($item);
	}


	function getItemByCategoria($id){
		$items = array();
		foreach ($this->coleccion as $item) {
			if ($item->getPropiedad('categoria_id') == $id) {
				$items[] = $item;
			}
		}
		return $items;
	}


	/* Todos los metodos getItemBD deberian ser refactorizados, recibir como parametro un objeto
		con las opciones, el statement y el array del execute ya preparado, dejar getItemBD en la
		superclase y en las clases que heredan definir metodos especificos para enviar los parametros
		así eliminariamos todo el jaleo de if/else. Por ejemplo getOfertas(), getNovedades(), etc.
	*/
	function getItemBD(array $opciones=null) {
		// Si tenemos una $id cargamos solo un elemento

		if (isset($opciones['id'])) {
			$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE id = :id';
			$stmt = $this->controlador->getPDO()->prepare($prepare);
			$stmt->execute(array(':id'=>$opciones['id']));
		} else {
			// Vaciamos la colección antes de cargar la tabla de la base de datos
			$this->coleccion = array();
			
			if (isset($opciones['ofertas'])) {
				// OJO, en esta consulta no estamos usando la variable para la tabla porque no tenia pensado
				// necesitar ningún JOIN.

				$prepare = "SELECT pr.* FROM productos pr JOIN ofertas o on pr.id = o.producto_id WHERE o.activa = 1 AND pr.disponibilidad!='Descatalogado' ORDER BY fecha ASC";
				$countPrepare = str_replace('SELECT pr.* FROM', 'SELECT COUNT(*) FROM', $prepare);
				if ($opciones['ofertas']!=-1) {
					$prepare .= ' LIMIT ' . $opciones['ofertas'];
				}

			} else if (isset($opciones['novedades'])) {
				// Novedades, mostramos los últimos productos añadidos primero. Si  'novedades'=-1
				// las mostramos por páginas, si hay una cantidad es que estamos mostrando las 
				// del index.
				if ($opciones['novedades']!=-1) {
					$prepare = 'SELECT * FROM ' . $this->tabla . ' ORDER BY fecha DESC LIMIT ' . $opciones['novedades'];
				} else {
					$prepare = 'SELECT * FROM ' . $this->tabla . ' ORDER BY fecha DESC';
				}

			} else if (isset($opciones['outlet'])) {
				// Outlet, mostramos los productos más antiguos primero.
				if ($opciones['outlet']!=-1) {
					$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE disponibilidad = \'Outlet\' ORDER BY fecha ASC LIMIT ' . $opciones['outlet'];
				} else {
					$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE disponibilidad = \'Outlet\' ORDER BY fecha ASC';
				}

			} else if (isset($opciones['parent_id'])) {
				// OJO, en esta consulta no estamos usando la variable para la tabla porque no tenia pensado
				// necesitar ningún JOIN.
				$prepare = 'SELECT pr.* FROM productos pr INNER JOIN categorias cat ON pr.categoria_id=cat.id WHERE cat.parent_id = '.$opciones['parent_id'];
				$countPrepare = str_replace('SELECT pr.* FROM', 'SELECT COUNT(*) FROM', $prepare);

			} else if (isset($opciones['cat_id'])) {
				// Mostramos todos los productos de la categoría
				$prepare = 'SELECT * FROM ' . $this->tabla . ' WHERE categoria_id = ' . $opciones['cat_id'];

			} else if (isset($opciones['buscar'])) {
				// Llamamos a otro mètodo que nos construya la consulta
				$prepare = $this->getSearch($opciones['buscar']);
				$countPrepare = str_replace('SELECT pr.* FROM', 'SELECT COUNT(*) FROM', $prepare);


			} else {
				// Default, los mostramos según lo establecido en el constructor.
				$prepare = 'SELECT * FROM ' . $this->tabla .' ORDER BY ' . $this->orden;
			}

			if (isset($opciones['recordoffset'])){
				$prepare .=  ' LIMIT ' . $opciones['recordoffset'] . ', ' . PRODUCTOS_PAGINA;
			}

			$stmt = $this->controlador->getPDO()->prepare($prepare);
			$stmt->execute();

			
			// Si no existe ya, preparamos el contador normal
			if (!isset($countPrepare)) {
				$countPrepare = str_replace('SELECT * FROM', 'SELECT COUNT(*) FROM', $prepare);	
			}
			
			//Modificamos la consulta para obtener el total sin limit
			if (strripos($countPrepare, 'LIMIT')){
				$countPrepare = substr($countPrepare, 0, strripos($countPrepare, 'LIMIT'));
			}
			
			$countStmt = $this->controlador->getPDO()->prepare($countPrepare);
			$countStmt->execute();
			$this->totalBD=$countStmt->fetch(PDO::FETCH_COLUMN);
			
			//echo '<br>Elementos totales en la consulta sin LIMIT: '. $this->totalBD. '<br>';
		}
			
		$rows = $stmt->fetchAll();
		// Obtenemos el diccionario de $propiedades 'activo' => boolean, 'nombre' => string, etc.
		$clase = $this->miembro;
		$propiedades = call_user_func($clase.'::getListaPropiedades');

		foreach ($rows as $row){
			$campos = array();
			foreach ($propiedades as $propiedad=>$tipo) {
				$campos[$propiedad] = $this->sanitize($row[$propiedad], $tipo);
			}
			$aux = new $clase ($campos);
			$aux->calcDisponibilidad();
			$this->addItem($aux);
			//echo "Añadido el objeto: ".$campos['nombre'] ."<br>";
		}
		return $this;
	}

	protected function getSearch($buscar){

		//Eliminamos los separadores y los caracteres sobrantes
		$buscar  = trim(preg_replace("/[,.\s+]/",' ',$buscar));   
		
		$palabras = explode (' ', $buscar);
		// Cuando añadimos una palabra a la busqueda la guardamos en $memoria
		// Antes de añadir otra comprobamos que no este ya en $memoria.
		// Por si acaso comprobamos si la cadena está vacia y si es así la ignoramos.

		$memoria = array();
		foreach ($palabras as $palabra) {
			if (!in_array($palabra, $memoria) AND !empty($palabra)) {
				$memoria[]=$palabra;
				//echo "Palabra encontrada: $palabra<br>";
				$aux[] = "pr.nombre LIKE '%$palabra%' OR pr.descripcion LIKE '%$palabra%' OR fab.nombre like '%$palabra%' ";
			} 
		}

		$where = implode (' OR ', $aux);
		$prepare = 'SELECT pr.* FROM productos pr LEFT JOIN fabricantes fab ON pr.fabricante_id=fab.id WHERE ' . $where;
		
		//echo  '<br><br>'.$prepare.'<br><br>';
		return $prepare;
	}

}
?>