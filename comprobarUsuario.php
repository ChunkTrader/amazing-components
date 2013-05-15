<?php

if ($regSistema->getValor('autenticado'))
	// Estamos autenticados, 
	$usuario_conectado=$usuarios->getItemBD(array('id'=>$regSistema->getValor('id')))->getItemById($regSistema->getValor('id'));
	//echo "estas autenticado como {$regSistema->getValor('nombre')}";
	
	// 
	
?>