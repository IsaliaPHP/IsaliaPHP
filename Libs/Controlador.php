<?php

class Controlador 
{
	protected $_variablesInternas = [];

	public function __set($variable, $valor)
	{
		$this->_variablesInternas[$variable] = $valor;
	}

	public function __get($variable)
	{
		return $this->_variablesInternas[$variable] ?? null;
	}

	public function misVariables()
	{
		return $this->_variablesInternas;	
	}

}