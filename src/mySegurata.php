<?php

class mySegurata 
{
	public $acceso;
	private $lista;
	private $listaPSW;
	private $user;
	
	private $in_lista;
	private $in_listapsw;
	
	private $comodin;
	
	private $res_entrada;
	private $session;
	private $cookie;
	private $opcionCookie;
	private $cookieExpira = 3600;
	
	public function __construct($parametros)
	{
	  $this->in_lista 		= $parametros['lista'];
	  $this->in_listaPSW 	= $parametros['listaPSW']; 
	  $this->acceso 		= $parametros['acceso']; 
	  $this->session 		= $parametros['session']; 
	  $this->cookie 		= $parametros['cookie']; 
	  $this->comodin 		= $parametros['comodin']; 
	  
	  
	  if(isset($parametros['opcionCookie'])) {
				$this->opcionCookie	= $parametros['opcionCookie'];
				} else {
				$this->opcionCookie	= 0;  }
	   
	}
	
	
	public function setAcceso($valor)
	{
		$this->acceso = $valor;
		return $this;
	}
	
	
	public function getAcceso()
	{
		return (int)$this->acceso;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setUser($valor)
	{
		$this->user = $valor;
		return $this;
	}
	
	public function getIn_lista()
	{
		return $this->in_lista;
	}
	
	public function getIn_listaPSW()
	{
		return $this->in_listaPSW;
	}
	
	public function setRes_entrada($valor)
	{
		$this->res_entrada = $valor;
		return $this;
	}	
	
	public function getRes_entrada()
	{
		return $this->res_entrada;
	}
	
	public function getSession()
	{
		return $this->session;
	}
	
	
	public function getCookie()
	{
		return $this->cookie;
	}
	
	
	public function getOpcionCookie()
	{
		return $this->opcionCookie;
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
		$this->lista = $this->CrearLista($lista);
		return $this->lista;
	}
	
	
	public function getLista()
	{
		return $this->lista;
	}


	public function setListaPSW($listaPSW)
	{
		$this->listaPSW = $this->CrearLista($listaPSW);
		return $this->listaPSW;
	}
	
	
	public function getListaPSW()
	{
		return $this->listaPSW;
	}

	public function comprobarPase($valor)
	{
		if(strpos($valor, ':')){
			$des = explode(':',$valor); 
			
			$this->setListaPSW($this->getIn_listaPSW());
			$listadoPSW = $this->getListaPSW();
			
			$this->setLista($this->getIn_lista());
			$lista = $this->getLista();
			
			$usuario = array_search($des[0],$listadoPSW);
			
			if($usuario != FALSE && $des[1] == $lista[$usuario]) { 
				$this->setUser(array_search($des[0],$listadoPSW));
				$this->setAcceso($des[1]);
				return 1;	
				}
			$this->setAcceso(0);
			return 0;
			} else {
			$this->setAcceso(0);	
			return 0;	
			}
	}



	public function visita($usuario = null,$password = null)
	{
		$session = $this->getSession();
		$cookie  = $this->getCookie();
		
		if($session != '') {
			
			$this->setAcceso($session);
			$this->res_entrada = 0;
			return $this->getAcceso(); 
			} elseif ($session == '') {
				
				if($cookie != ''){
					$this->res_entrada = 0;
					
					$this->comprobarPase($cookie);
					return $this->getAcceso(); 
					
					} elseif ($cookie == '') {
					
						$this->setLista($this->getIn_lista());
						$this->setListaPSW($this->getIn_listaPSW());
					
						$lista 		= $this->getLista();
						$listaPSW 	= $this->getListaPSW();
						
							if (!empty($lista) && !empty($listaPSW)) {
								
								$this->setAcceso($this->checkeo($usuario,$password));
								
								if($this->getOpcionCookie()==1) {
									setcookie("pass", $password.':'.$this->getAcceso(), time() + $this->cookieExpira);  }
								
								return $this->getAcceso();	
								} else { 
									$this->setAcceso(0);
									return $this->getAcceso(); 
									}
						} 
						}
	}

	
	public function checkeo($usuario,$password)
	{
		$password = $password.$this->comodin;
		$password = sha1($password);
		if($usuario != null && $password != null){
				
				
			$lista_psw = $this->getListaPSW();	
			
			foreach ($lista_psw as $nom=>$val){
				
				$key = $nom;
				$us_encontrado = array_search($usuario,$lista_psw);
				
				if ($us_encontrado == FALSE){
					if($usuario == $key ){
							if ($password == $lista_psw[$key]) {
									$this->setRes_entrada(1);
									$this->setUser($usuario);
									return 	(int)$this->lista[$usuario]; 
							} 
						}
				
						} else {
						$this->setRes_entrada(0);
						return 0;
						}
			} 
			$this->setRes_entrada(0);
			return 0;	
				
				} else {
			$this->setRes_entrada(0);
			return 0; } }
	
}
?>
