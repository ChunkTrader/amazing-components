<?php
	class Imagen {
		private $imagen_id;
		private $producto_id;
		private $imagen; // URL
		private $principal;

		function __construct ($producto_id, $imagen_id = null, $imagen, $principal=false) {
			$this->imagen_id = $imagen_id;
			$this->producto_id = $producto_id;
			$this->imagen = $imagen;
			$this->principal = $principal;
		}

		function getId(){
			return $this->imagen_id;		
		}

		function getProductoId() {
			return $this->producto_id;
		}

		function getImagen() {
			return $this->imagen;
		}

		function getPrincipal() {
			return $this->principal;
		}

		function setProductoId($producto_id){
			$this->producto_id = $producto_id;
		}

		function setImagen($imagen) {
			$this->imagen = $imagen;
		}

		function setPrincipal($principal) {
			$this->principal = $principal;
		}
	}

	class Galeria {
		private $imagenes = array();
		private $PDO;

		function __construct(PDO $PDO) {
			$this->PDO = $PDO;
		}

		function addImagen(Imagen $imagen) {
			$this->imagenes[] = $imagen;
		}


		function delImagen($imagen_id){
			$imagen = $this->getImagenById($imagen_id);
			if (($key = array_search($imagen, $this->imagenes, TRUE)) !== FALSE) {
				unset ($this->imagenes[$key]);
			}
		}

		function getImagenById($imagen_id=null) {
			if (!$imagen_id) {			
				return $this->imagenes;
			}

			foreach ($this->imagenes as $key=>$imagen) {
				if ($imagen->getId()==$imagen_id) {
					return $this->imagenes[$key];
				}
			}
			return null;
		}


		function getImagenByProductoId($producto_id) {
			// Ojo, solo devuelve 1 imagen, hay que añadir un array y almacenar las coincidencias para
			// devolverlas al final.
			foreach ($this->imagenes as $key=>$imagen) {
				if ($imagen->getProductoId()==$producto_id) {
					return $this->imagenes[$key];
				}
			}

			return null;
		}

		function addImagenBD ( Imagen $imagen) {
			$stmt = $this->PDO->prepare ('INSERT INTO galeria_productos (producto_id, imagen, principal)
				VALUES (:producto_id, :imagen, :principal)');
			$stmt->execute (array (
				':producto_id' => $imagen->getProductoId(),
				':imagen' => $imagen->getImagen(),
				':principal' => $imagen->getPrincipal(),
				));
		}

		function getImagenBD( $producto_id=null, $principal=null){
			// Si se pasa el parametro $principal solo se obtiene la lista de los elementos marcados como
			// principal.

			if ($producto_id) {
				if ($principal) {
					$prepare = 'SELECT * FROM galeria_productos WHERE producto_id = :producto_id AND principal=TRUE';
					$stmt = $this->PDO->prepare ( $prepare );
					$stmt->execute ( array (':producto_id'=>$producto_id));
				} else {
					$stmt = $this->PDO->prepare ( 'SELECT * FROM galeria_productos WHERE producto_id = :producto_id');
					$stmt->execute ( array (':producto_id'=>$producto_id));
				}
			} else {
				if ($principal) {
					$prepare = 'SELECT * FROM galeria_productos WHERE principal=TRUE';
					$stmt = $this->PDO->prepare ( $prepare);
					$stmt->execute ();
				} else {
					$stmt = $this->PDO->prepare ( 'SELECT * FROM galeria_productos' );
					$stmt->execute ();	
				}
				
			}


			while ($row = $stmt->fetch()) {
				$imagen_id = $this->sanitize($row['imagen_id']);
				$producto_id = $this->sanitize($row['producto_id']);
				$imagen = $this->sanitize($row['imagen']);
				$principal = $row['principal'] ? TRUE : FALSE;
				
				$this->addImagen(new Imagen($producto_id, $imagen_id, $imagen, $principal));

			}
		}

		function setImagenBD( Imagen $imagen){
			$stmt = $this->PDO->prepare('UPDATE galeria_productos SET producto_id = :producto_id, imagen = :imagen, principal = :principal WHERE imagen_id = :imagen_id');
			$stmt->execute(array (
					':imagen_id' => $imagen->getId(),
					':producto_id' => $imagen->getProductoId(),
					':imagen' => $imagen->getImagen(),
					':principal' => $imagen->getPrincipal()
				));
		
		$this->delImagen($imagen->getId());
		$this->addImagen($imagen);
	}


	function delImagenBD( $imagen_id) {
		// Eliminamos tambien la imagen del disco
		$url = 'images/products/' . $this->getImagenById($imagen_id)->getImagen() . '.jpeg';
		unlink($url);
		echo $url;

		$stmt = $this->PDO->prepare ('DELETE FROM galeria_productos WHERE imagen_id = :imagen_id');
		$stmt->execute (array (
			':imagen_id' => $imagen_id
		));
		$this->delImagen($imagen_id);

		

	}

	private function sanitize($item){
		return htmlentities(trim($item), ENT_QUOTES, 'ISO-8859-1');
	}


	}

?>