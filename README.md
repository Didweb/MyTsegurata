MyTSeguridad
=================

[Inicio Documentación][2] 


Sistema para el control de accesos a zonas restringidas.

Este sistema esta ideado para mi Framework MyT [MyT][1]


### Criterios

Las páginas de acceso libre tiene un permiso de acceso `0` y las que tiene distintos permisos se asignan `1` o podrá asignarse otros superiores a `0`.

La idea es que los usuarios se definan con una categoría de permisos de forma jerárquica, por ejemplo un usuario con acceso 2 podrá acceder a todas las páginas definidas con un acceso de 2 o inferior.

De esta manera los usuarios definidos con acceso 1 no podrán acceder a páginas o contenidos definidos con un número superior.

Si un usuario accede a una página definida con un permiso de acceso superior a `0` se le redirige a la página de acceso para que se identifique. Debe pasar el proceso de identificación para poder ver la página.


# Funcionamiento y configuración en Framework MyT

A continuación se detalla como configurarlo en **Framework MyT**, más adelante detallare como configurarlo sin utilizar MyT.


### Claves

Las claves se almacenan codificadas sh1.

El password se forman por la palabra que se quiera poner más una cadena que se puede definir en `config/seguridad.yml` en el parámetro `comodin`. Se ha de tener en cuenta que cuando creemos de forma manual el sh1 se ha de poner el password+el comodín. 

Para acceder al sistema el usuario solo deberá poner el password ya que el comodín se monta de forma interna para hacer la comprobación.



### Definiendo usuarios


Se pueden definir usuarios tanto dentro de un archivo de configuración llamado `config/seguridad.yml` como especificarlos en una Base de Datos y que el sistema se nutra de estos usuarios para hacer el checkeo de entrada.


### Definir usuarios en archivo de configuración


Se definen en el archivo `config/seguridad.yml`, este archivo se transforma en una clase para poder acceder a estos datos de forma más rápida, esta clase se almacena en `tmp/seguridad.php`.

El proceso de transformación se realiza de forma automática cada vez que se percibe un cambio en el archivo de configuración `config/seguridad.yml`.

El aspecto es el siguiente:

```

Seguridad:
    
    usuarios:
        edu: e3f96800b051602e7ac5542e01747eb09147a54b:1
        pepito: 5d212bd2fed57636c27d15965598817b1e45d3ca:1

```

Donde en este caso `edu` y `pepito` son los usuarios. la cadena larga hasta el símbolo `:` es la codificación de la clave. Después del símbolo `:` se puede ver el tipo de acceso asignado para cada usuario.


### Definir usuarios en Base de Datos

Si quieres especificar una fuente de usuarios en una base de datos, dentro de `config/seguridad.yml` se ha de especificar el parámetro `datosfuente` donde...

**datosfuente** : Se utiliza para dar los parámetros como la tabla de la fuente el campo de usuario y el de password así como el nombre del campo de tipo de acceso.

El formato para este parámetro es el siguiente `NOMBRE_TABLA:NOMBRE_CAMPO_USUARIO:NOMBRE_CAMPO_PASSWORD:NOMBRE_CAMPO_TIPO_ACCESO` separado por `:`.



Y dentro de `config/rutas.yml` debemos especificar que fuente de acceso precisa esta url con el parámetro `fuenteacceso` ...

**fuenteacceso** : Aquí pondremos `bbdd` si se quiere suministrar los datos por Base de Datos.


### Especificando acceso a páginas restringidas

Dentro del archivo de configuración de URLs `config/rutas.yml` podemos definir el tipo de acceso que va a tener esta URL, un ejemplo:

```

IndexIdioma:
    url: home/{lang:locale}
    controller: Index::index
    permiso: 0
    fuenteacceso: sin
      
ruta_uno:
    url: getsor/{lang:locale}/{pagina:int}
    controller: Index::index2
    permiso: 1
    fuenteacceso: bbdd


```

Es en el parámetro `permiso` donde se especifica el número de acceso. En este ejemplo las URLs `home/es`, `home/en`, etc. Pueden acceder todos los usuarios, no es necesario que estén identificados.
Las URLs  del tipo: `getsor/es/1`, `getsor/en/1`, `getsor/es/3`, etc. solo pueden ser vistas por usuarios identificados con el acceso `1` o superior

Con el parámetro `fuenteacceso` se determina la fuente de usuarios si se pone `bbdd` se accederá al base de datos detallada en `config/seguridad.yml` en el parámetro `datosfuente`



### ¿Dónde se produce el control?

El control de acceso se realiza dentro del archivo `app/Bootstrap.php` donde se comprueba el tipo de acceso que tiene la url y si el usuario esta logeado o no.



# Configuración sin utilizar MyT


Para utilizarlo este servicio se realiza de la siguiente forma:

```
		$parametros = array('lista'=>'Juan:15,Pepito:1,Antonio:2',
							'listaPSW'=>'Juan:fbaf40c551682f72e2261c95566dbfdcb7e00951,Pepito:pass2,Antonio:pass3',
							'comodin'=>'a',
							'acceso'=>0,
							'session'=>'',
							'cookie'=>'',
							'fuenteacceso'=>'bbdd',
							'datosfuente'=> 'usuarios:usuario:password:acceso');
		$acceso = new mySegurata($parametros);
		$acceso->visita($_POST['usuario'],$_POST['password']);

```

En este caso se crean los parámetros como creas conveniente, pero deben ser estos, aquí en el parámetro `fuenteacceso` se da el valor `bbdd` esto ara que busque en la Base de datos detallada en `datosfuente` si se quiere que recogerlos datos desde el archivo de configuración se ha de dar como valor cualquier otro texto.

Esto devuelve 1 para acceso permitido y 0 acceso no permitido, luego en tu sistema se ha de crear lo necesario para gestionar estas respuestas.


[1]: https://github.com/Didweb/MyT
