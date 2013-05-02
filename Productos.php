<?php
class Producto {
	private $nombre;
	private $id;
	private $categoria_id;
	private $precio_venta;
	private $descripcion;
	private $fabricante_id;
	private $disponibilidad;
	private $existencias;
	private $fecha;
	private $activa;

	function __construct($nombre, $id = null, $categoria_id, $precio_venta, $descripcion = null, $disponibilidad = 'Agotado') {
		$this->nombre = $nombre;
		$this->id = $id;
		$this->categoria_id = $categoria_id;
		$this->precio_venta = $precio_venta;
		$this->descripcion = $descripcion;
		$this->disponibilidad = $disponibilidad;
	}

	function getId() {
		return $this->id;
	}
	function getCategoriaId() {
		return $this->categoria_id;
	}
	function getNombre() {
		return $this->nombre;
	}
	function getDescripcion() {
		return $this->descripcion;
	}

	function getPrecioVenta() {
		return $this->precio_venta;
	}

	function getDisponibilidad() {
		return $this->disponibilidad;
	}

	function getFabricanteId(){
		return $this->fabricante_id;
	}

	function getExistencias(){
		return $this->existencias;
	}

	function getActiva() {
		return $this->activa;
	}

	function getFecha() {
		return $this->fecha;
	}

	function setCategoriaId($categoria_id) {
		$this->categoria_id = $categoria_id;
	}
	function setNombre($nombre) {
		$this->nombre=$nombre;;
	}
	function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}

	function setPrecioVenta($precio) {
		$this->precio_venta = $precio;
	}

	function setDisponibilidad($disponibilidad) {
		$this->disponibilidad = $disponibilidad;
	}

	function setExistencias($existencias) {
		$this->existencias = $existencias;
	}

	function addExistencias($existencias) {
		$this->existencias += $existencias;
	}

	function setFabricanteId($fabricante_id) {
		$this->fabricante_id = $fabricante_id;
	}

	function setActiva($activa) {
		$this->activa = $activa;
	}

	function setFecha($fecha) {
		$this->fecha= $fecha;
	}
}

class ListasProductos {
	private $productos = array();
	private $PDO;

	function __construct (PDO $PDO){
		$this->PDO = $PDO;
	}

	function addProducto(Producto $producto){
		$this->productos[] = $producto;
	}

	function getProductoById($producto_id=null) {
		if (!$producto_id) {			
			return $this->productos;
		}

		foreach ($this->productos as $key=>$producto) {
			if ($producto->getId()==$producto_id) {
				return $this->productos[$key];
			}
		}
		return null;
	}

	function delProducto($producto_id){
		$producto = $this->getProductoById($producto_id);
		if (($key = array_search($producto, $this->productos, TRUE)) !== FALSE) {
			unset ($this->productos[$key]);
		}
	}


	function addProductoBD ( Producto $producto) {
		// Todos los productos estan activos al darlos de alta.


		// Recalculamos la disponibilidad
		 if ($producto->getExistencias()>0) {
				$producto->setDisponibilidad ('En stock');
		} else if ($producto->getDisponibilidad() == 'Próxima reposición') {
		// No hacemos nada, ya tenemos el valor adecuado
		} else {
			$producto->setDisponibilidad ('Agotado');
		}



		$stmt = $this->PDO->prepare ( 'INSERT INTO productos 
			(nombre, descripcion, categoria_id, fabricante_id, precio_venta, disponibilidad, existencias) 
			VALUES (:nombre, :descripcion, :categoria_id, :fabricante_id, :precio_venta, :disponibilidad, :existencias)' );
		$stmt->execute ( array (
				':nombre' => $producto->getNombre (),
				':descripcion' => $producto->getDescripcion(),
				':categoria_id' => $producto->getCategoriaId(),
				':fabricante_id' => $producto->getFabricanteId(),
				':precio_venta'  => $producto->getPrecioVenta(),
				':disponibilidad' => $producto->getDisponibilidad(),
				':existencias' => $producto->getExistencias(),
		) );
	}

	function getProductoBD( $producto_id=null, $novedades=0, $outlet=0) {
		// Si se envia el parametro novedades se devuelven los $novedades registros más recientes

			if ($producto_id) {
				$stmt = $this->PDO->prepare ( 'SELECT * FROM productos WHERE producto_id = :producto_id');
				$stmt->execute ( array (':producto_id'=>$producto_id));
			} else if($novedades){
				$prepare = "SELECT * FROM productos ORDER BY fecha DESC LIMIT $novedades";
				$stmt = $this->PDO->prepare ( $prepare );
				$stmt->execute ();
			} else if($outlet){
				$prepare = "SELECT * FROM productos WHERE disponibilidad = 'Outlet' LIMIT $outlet";
				$stmt = $this->PDO->prepare ( $prepare );
				$stmt->execute ();
			} else {
				// Ordenamos alfabeticamente por nombre de producto
				$stmt = $this->PDO->prepare ( 'SELECT * FROM productos ORDER BY nombre');
				$stmt->execute ();
			}

		while ( $row = $stmt->fetch () ) {
			$nombre = $this->sanitize($row ['nombre']);
			$producto_id = $this->sanitize($row ['producto_id']);
			$categoria_id = $this->sanitize($row ['categoria_id']);
			$precio_venta = $this->sanitize($row ['precio_venta']);
			$descripcion = $this->sanitize($row ['descripcion']);
			$existencias = $this->sanitize($row['existencias']);
			$disponibilidad = $this->sanitize($row['disponibilidad']);
			$fabricante_id = $this->sanitize($row['fabricante_id']);		
			$fecha = $this->sanitize($row['fecha']);

			$activa = $row['activa'] ? TRUE : FALSE;

			if (!$activa) {
				if ($existencias>0) {
					$disponibilidad = 'Outlet';
				} else {
					$disponibilidad = 'Descatalogado';
				}
			} else if ($existencias>0) {
				$disponibilidad = 'En stock';
			} else if ($disponibilidad=='Próxima reposición') {
				// No hacemos nada, ya tenemos el valor adecuado
			} else {
				$disponibilidad = 'Agotado';
			}

			$producto = new Producto ( $nombre, $producto_id, $categoria_id, $precio_venta, $descripcion, $disponibilidad ) ;
			$producto->setFabricanteId($fabricante_id);
			$producto->setExistencias ($existencias);
			$producto->setActiva($activa);
			$producto->setFecha($fecha);

			$this->addProducto ($producto);

		}

	}


	function getProductosByCategoriaId($categoria_id) {
		// Añadir: buscar todas las categorias child i añadirlas a la lista
		$productos = array ();
		foreach ( $this->productos as $producto ) {
			if ($producto->getCategoriaId () == $categoria_id) {
				$productos [] = $producto;
			}
		}
		return $productos;
	}


	function setProductoBD ( Producto $producto) {

		// Recalculamos la disponibilidad
			if (!$producto->getActiva()) {
				if ($producto->getExistencias()>0) {
					$producto->setDisponibilidad ('Outlet');
				} else {
					$producto->setDisponibilidad ('Descatalogado');
				}
			} else if ($producto->getExistencias()>0) {
				$producto->setDisponibilidad ('En stock');
			} else if ($producto->getDisponibilidad() == 'Próxima reposición') {
				// No hacemos nada, ya tenemos el valor adecuado
			} else {
				$producto->setDisponibilidad ('Agotado');
			}


		$stmt = $this->PDO->prepare('UPDATE productos SET nombre = :nombre, descripcion = :descripcion, categoria_id = :categoria_id, precio_venta = :precio_venta, fabricante_id = :fabricante_id, existencias = :existencias, activa = :activa, disponibilidad = :disponibilidad WHERE producto_id = :producto_id');
		$stmt->execute (array (
				':producto_id' => $producto->getId(),
				':nombre' => $producto->getNombre (),
				':descripcion' => $producto->getDescripcion(),
				':categoria_id' => $producto->getCategoriaId(),
				':precio_venta'  => $producto->getPrecioVenta(),
				':fabricante_id' => $producto->getFabricanteId(),
				':existencias' => $producto->getExistencias(),
				':activa' => $producto->getActiva(),
				':disponibilidad' => $producto->getDisponibilidad(),
		));
		$this->delProducto($producto->getId());
		$this->addProducto($producto);
	}

	function delProductoBD( $producto_id) {
		$stmt = $this->PDO->prepare ('DELETE FROM productos WHERE producto_id = :producto_id');
		$stmt->execute (array (
			':producto_id' => $producto_id
		));
		$this->delProducto($producto_id);
	}

	function getTotal(){
		return count($this->productos);
	}

	private function sanitize($item){
		return htmlentities(trim($item), ENT_QUOTES, 'ISO-8859-1');
	}

}

