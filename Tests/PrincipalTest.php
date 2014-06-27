<?php
require_once('mySegurata.php');

class LocaleTest extends PHPUnit_Framework_TestCase
{
	public function testExisteArchivo()
	{
		$this->assertFileExists('mySegurata.php');
	}
	
	public function testInstanciarObjeto()
	{
		$this->assertInstanceOf('mySegurata', new mySegurata);
	}
	
	public function testAccesoSetyGet()
	{
		$acceso = new mySegurata();
		$acceso->setAcceso('1');
		
		$this->assertEquals('1', $acceso->getAcceso());
	}

	public function testCrearLista()
	{
		$acceso = new mySegurata();
		
		//comprobamos que devuelva un array
		$lista = $acceso->CrearLista('Juan:0,Pepito:1,Antonio:2');
		$this->assertInternalType('array', $lista);
		
		// En el caso de que no escriban bien la lista
		$lista = $acceso->CrearLista(',Juan:0,Pepito:1,Antonio:2,');
		$this->assertEquals('2', $lista['Antonio']);
		
		// En el caso de que no escriban bien la lista, que registre los valores validos.
		$lista = $acceso->CrearLista(',Juan:0,Pepito:1,Antonio:2,');
		$this->assertCount(3, $lista);
		
		// En el caso de que no escriban bien la lista, que registre los valores validos.
		$lista = $acceso->CrearLista('Juan:,Pepito:1,Antonio:2,');
		$this->assertCount(2, $lista);
		
		// En el caso de que no escriban bien la lista, que registre los valores validos.
		$lista = $acceso->CrearLista(':,Pepito:1,Antonio:2,');
		$this->assertCount(2, $lista);
	}



	public function testListaSetyGet()
	{
		$acceso = new mySegurata();
		
		// Comprobar Set y Get de ListaPSW
		$acceso->setListaPSW('hola');
		$lista = $acceso->getListaPSW();
		$this->assertEquals('hola',$lista);
		
		// Comprobar Set y Get de Lista
		$acceso->setLista('hola');
		$lista = $acceso->getLista();
		$this->assertEquals('hola',$lista);
	}

	
	public function testCheckeo()
	{
		$acceso = new mySegurata();
		
		// entrada null
		$check = $acceso->checkeo();
		$this->assertNull($check);
		
		// Comprobamos que devuelve el pase correcto
		$lista 		= $acceso->CrearLista('Juan:0,Pepito:1,Antonio:2');
		$listaPSW 	= $acceso->CrearLista('Juan:pass1,Pepito:pass2,Antonio:pass3');
		
		$acceso->setLista($lista);
		$acceso->setListaPSW($listaPSW);
		
		$check = $acceso->checkeo('Juan','pass1');
		$this->assertEquals('0',$check);


		// Probamos con valores nulos.
		$check = $acceso->checkeo('Juan');
		$this->assertEquals(null,$check);
		
		// Valores incorrectos en usuario y password.
		$check = $acceso->checkeo('xxxx','yyyy');
		$this->assertEquals(null,$check);

	}	
	
	public function testVisita()
	{
		$acceso = new mySegurata();

		// entrada null
		$visita = $acceso->visita();
		$this->assertNull($visita);
	}		
	
}
?>	
