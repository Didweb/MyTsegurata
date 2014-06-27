<?php

class mySegurata
{
	public $acceso;
	private $lista;
	private $listaPSW;
	
	
	public function setAcceso($valor)
	{
		$this->acceso = $valor;
		return $this;
	}
	
	
	public function getAcceso()
	{
		return $this->acceso;
	}
	
	public function CrearLista($valor)
	{
		$lista = array();
		$lista1 = explode(',',$valor);
		foreach ($lista1 as $nom=>$val){
			
			if(strpos($lista1[$nom], ':')){
				$lista2 = explode(':',$lista1[$nom]);
				
				foreach ($lista2 as $nom2=>$val2){
					
					if($lista2[0]=='' || $lista2[1]==''){
						
						} else {
							
						$lista[$lista2[0]] = $lista2[1];
						}
					}
				}
			}
		
		return $lista;
	}
	
	public function setLista($lista)
	{
		$this->lista = $lista;
		return $this->lista;
	}
	
	
	public function getLista()
	{
		return $this->lista;
	}

	public function setListaPSW($listaPSW)
	{
		$this->listaPSW = $listaPSW;
		return $this->listaPSW;
	}
	
	
	public function getListaPSW()
	{
		return $this->listaPSW;
	}

	public function visita()
	{
		if(isset($_SESSION['pass'])){
			$this->setAcceso($_SESSION['pass']);
			} elseif (!isset($_SESSION['pass'])) {
				return null; }
		
	}

	
	public function checkeo($usuario = null,$password = null)
	{
		if($usuario != null && $password != null){
				
			$lista_psw = $this->getListaPSW();	
			
			foreach ($lista_psw as $nom=>$val){
				
				$key = $nom;
				
				if($usuario == $key ){
					if ($password == $lista_psw[$key]) {
						return 	$this->lista[$usuario]; } }
				
				}
			
			} else {
			return null; }
	}
	
}
?>
