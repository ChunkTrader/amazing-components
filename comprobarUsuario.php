<?php

if ($regSistema->getValor('autenticado')) {
	// Estamos autenticados, 
	$usuario=$usuarios->getItemBD(array('id'=>$regSistema->getValor('id')))->getItemById($regSistema->getValor('id'));
	// 
}
	
?>