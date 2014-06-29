<?php
require_once('src/mySegurata.php');

class LocaleTest extends PHPUnit_Framework_TestCase
{
	public $acceso;
	
	public function setup()
	{
		$parametros = array('lista'=>'Juan:0,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'','cookie'=>'pass1:15');
		$acceso = new mySegurata($parametros);	
		$this->acceso = $acceso;
		
	}
	
	
	public function testExisteArchivo()
	{
		$this->assertFileExists('src/mySegurata.php');
	}
	
	public function testInstanciarObjeto()
	{	$parametros = array('lista'=>'Juan:0,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'','cookie'=>'pass1:15');
		$this->assertInstanceOf('mySegurata', new mySegurata($parametros));
	}



	public function testParametrosConctructor()
	{
		
		$this->assertInternalType('string', $this->acceso->getIn_lista());
		$this->assertInternalType('string', $this->acceso->getIn_listaPSW());
		$this->assertInternalType('integer', $this->acceso->acceso);
	}

	public function testAccesoSetyGet()
	{
		
		$this->acceso->setAcceso('1');
		$this->assertEquals('1', $this->acceso->getAcceso());
	}


	public function testOpcionCookieGet()
	{
		// sin definir opcion de cookie
		$opcion = $this->acceso->getOpcionCookie('1');
		$this->assertEquals('0', $opcion);
		
		//definiendo opcion cookie en 1 = si
		$parametros = array('lista'=>'Juan:0,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'','cookie'=>'','opcionCookie'=>1);
		$acceso2 = new mySegurata($parametros);	
		
		
	}


	public function testCrearLista()
	{
		
		//comprobamos que devuelva un array
		$lista = $this->acceso->CrearLista('Juan:0,Pepito:1,Antonio:2');
		$this->assertInternalType('array', $lista);
		
		// En el caso de que no escriban bien la lista
		$lista = $this->acceso->CrearLista(',Juan:0,Pepito:1,Antonio:2,');
		$this->assertEquals('2', $lista['Antonio']);
		
		// En el caso de que no escriban bien la lista, que registre los valores validos.
		$lista = $this->acceso->CrearLista(',Juan:0,Pepito:1,Antonio:2,');
		$this->assertCount(3, $lista);
		
		// En el caso de que no escriban bien la lista, que registre los valores validos.
		$lista = $this->acceso->CrearLista('Juan:,Pepito:1,Antonio:2,');
		$this->assertCount(2, $lista);
		
		// En el caso de que no escriban bien la lista, que registre los valores validos.
		$lista = $this->acceso->CrearLista(':,Pepito:1,Antonio:2,');
		$this->assertCount(2, $lista);
		
		//En el caso de no tener ningún valor bien.
		$lista = $this->acceso->CrearLista('xxxx');
		$this->assertNull(null,$lista);
		
	}



	public function testListaSetyGet()
	{
		
		
		// Comprobar Set y Get de ListaPSW
		$this->acceso->setListaPSW('hola');
		$lista = $this->acceso->getListaPSW();
		$this->assertNull(null,$lista);
		
		// Comprobar Set y Get de Lista
		$this->acceso->setLista('hola');
		$lista = $this->acceso->getLista();
		$this->assertNull(null,$lista);
		
		
		// Comprobar Set y Get de ListaPSW
		$this->acceso->setListaPSW('Juan:pass1,Pepito:pass2,Antonio:pass3');
		$lista = $this->acceso->getListaPSW();
		$this->assertInternalType('array', $lista);
		
		// Comprobar Set y Get de Lista
		$this->acceso->setLista('Juan:0,Pepito:1,Antonio:2');
		$lista = $this->acceso->getLista();
		$this->assertInternalType('array', $lista);
		
	}

	public function testUserSetyGet()
	{
		$user = $this->acceso->setUser('Manolo');
		$user = $this->acceso->getUser();
		$this->assertEquals('Manolo',$user);
	}	

	
	public function testVisita()
	{
		// entrada con parametros password y usuario null
		$parametros = array('lista'=>'Juan:0,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'','cookie'=>'');
		$acceso2 = new mySegurata($parametros);	
		$visita = $acceso2->visita();
		$this->assertEquals(0,$visita);
		$this->assertInternalType('integer', $visita);
		
		// entrada por visita con acceso permitido
		$parametros = array('lista'=>'Juan:0,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'','cookie'=>'');
		$acceso2 = new mySegurata($parametros);
		$visita = $acceso2->visita('Pepito','pass2');
		$this->assertEquals(1, $visita);
		$this->assertInternalType('integer', $visita);
		
		// con uno d elos parametros (usuario o password) en null
		$parametros = array('lista'=>'Juan:0,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'','cookie'=>'');
		$acceso2 = new mySegurata($parametros);
		$visita = $acceso2->visita('Pepito');
		$this->assertEquals(0, $visita);
		$this->assertInternalType('integer', $visita);
		
		// con un valor de cookie incorrecto
		$parametros = array('lista'=>'Juan:0,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'','cookie'=>'xxxx');
		$acceso2 = new mySegurata($parametros);
		$visita = $acceso2->visita();
		$this->assertEquals(0, $visita);
		$this->assertInternalType('integer', $visita);
		
		// con un valor de session incorrecto
		$parametros = array('lista'=>'Juan:0,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'yyyyy','cookie'=>'');
		$acceso2 = new mySegurata($parametros);
		$visita = $acceso2->visita();
		$this->assertEquals(0, $visita);
		$this->assertInternalType('integer', $visita);
		
		
		// con valor cookie correcto y sin sesion
		$parametros = array('lista'=>'Juan:15,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'','cookie'=>'pass1:15');
		$acceso2 = new mySegurata($parametros);
		$acceso2->visita();
		$elacceso = $acceso2->getAcceso();
		$this->assertEquals(15, $elacceso);
		$this->assertInternalType('integer', $elacceso);
		
		
		// con un valor de session incorrecto
		$parametros = array('lista'=>'Juan:0,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'2','cookie'=>'');
		$acceso2 = new mySegurata($parametros);
		$acceso2->visita();
		$elacceso = $acceso2->getAcceso();
		$this->assertEquals(2, $elacceso);
		$this->assertInternalType('integer', $elacceso);
		
		
	}		
		
	public function testResultadEntrada()
	{
		// Comprobar Set y Get de ListaPSW
		$parametros = array('lista'=>'Juan:0,Pepito:1,Antonio:2','listaPSW'=>'Juan:pass1,Pepito:pass2,Antonio:pass3','acceso'=>0,'session'=>'','cookie'=>'');
		$acceso2 = new mySegurata($parametros);
		$entrada = $this->acceso->getRes_entrada();
		$this->assertEquals(0,$entrada);
	}		
	
		
	public function testComprobarPase()
	{
		// valores correctos en cookie
		$pase = $this->acceso->comprobarPase('pass2:1');
		$this->assertEquals(1, $pase);
		
		// valores incorrectos
		$pase = $this->acceso->comprobarPase('xxx');
		$this->assertEquals(0, $pase);
		
		// valores inexistentes
		$pase = $this->acceso->comprobarPase('xxx:1');
		$this->assertEquals(0, $pase);
		
	}	



	public function testAccesos()
	{
		// Comprobar Set y Get de ListaPSW
		$parametros = array('lista'=>'','listaPSW'=>'','acceso'=>0,'session'=>'','cookie'=>'');
		$acceso2 = new mySegurata($parametros);
		$visita = $acceso2->visita();
		$this->assertEquals(0,$visita);
	}	
	
}
?>	
