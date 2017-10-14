# PHP WordPress Register

[![Latest Stable Version](https://poser.pugx.org/josantonius/wp_register/v/stable)](https://packagist.org/packages/josantonius/wp_register) [![Total Downloads](https://poser.pugx.org/josantonius/wp_register/downloads)](https://packagist.org/packages/josantonius/wp_register) [![Latest Unstable Version](https://poser.pugx.org/josantonius/wp_register/v/unstable)](https://packagist.org/packages/josantonius/wp_register) [![License](https://poser.pugx.org/josantonius/wp_register/license)](https://packagist.org/packages/josantonius/wp_register) [![Travis](https://travis-ci.org/Josantonius/WP_Register.svg)](https://travis-ci.org/Josantonius/WP_Register)

[English version](README.md)

Registrar, minificar y unificar recursos CSS y JavaScript en WordPress.

---

- [Instalación](#instalación)
- [Requisitos](#requisitos)
- [Cómo empezar y ejemplos](#cómo-empezar-y-ejemplos)
- [Métodos disponibles](#métodos-disponibles)
- [Uso](#uso)
- [Tests](#tests)
- [Tareas pendientes](#-tareas-pendientes)
- [Contribuir](#contribuir)
- [Repositorio](#repositorio)
- [Licencia](#licencia)
- [Copyright](#copyright)

---

## Instalación 

La mejor forma de instalar esta extensión es a través de [composer](http://getcomposer.org/download/).

Para instalar PHP WordPress Register library, simplemente escribe:

    $ composer require Josantonius/WP_Register

El comando anterior sólo instalará los archivos necesarios, si prefieres descargar todo el código fuente (incluyendo tests, directorio vendor, excepciones no utilizadas, documentos...) puedes utilizar:

    $ composer require Josantonius/WP_Register --prefer-source

También puedes clonar el repositorio completo con Git:

    $ git clone https://github.com/Josantonius/WP_Register.git
    
## Requisitos

Esta biblioteca es soportada por versiones de PHP 5.6 o superiores y es compatible con versiones de HHVM 3.0 o superiores.

Para utilizar esta biblioteca en HHVM (HipHop Virtual Machine) tendrás que activar los tipos escalares. Añade la siguiente ĺínea "hhvm.php7.scalar_types = true" en tu "/etc/hhvm/php.ini".

## Cómo empezar y ejemplos

Para utilizar esta biblioteca, simplemente:

```php
require __DIR__ . '/vendor/autoload.php';

use Josantonius\WP_Register\WP_Register;
```

### Métodos disponibles

Métodos disponibles en esta biblioteca:

#### add()
Agregar scripts o estilos.
```php
WP_Register::add($type, $data);
```

| Atributo | Descripción | Tipo de dato | Requerido | Por defecto
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |

| Atributo | Clave | Descripción | Tipo de dato | Requerido | Por defecto
| --- | --- | --- | --- | --- | --- |
| $data | | Settings | array | Yes | |
|  | name | Unique ID | string | Yes | |
|  | url | Url to file | string | Yes | |
|  | place | 'admin' or 'front'  | string | No | 'front' |
|  | deps | Dependences | array | No | [] |
|  | version | Version | string | No | false |
|  | footer | **Only for scripts** - Attach in footer | boolean | No | true |
|  | params | **Only for scripts** - Params available in JS | array | Yes | [] |
|  | media | **Only for styles** - Media | string | No | '' |

**@return** → void  

#### unify()
Establecer si se fusiona el contenido de los archivos en un único archivo.
```php
WP_Register::unify($id, $params, $minify);
```

| Atributo | Descripción | Tipo de dato | Requerido | Por defecto
| --- | --- | --- | --- | --- |
| $id | Action hook name | string | Yes | |
| $params | Path urls | mixed | Yes | |
| $minify | Minimize file content | boolean | No | false |

**@return** → boolean true

#### isAdded()
Comprobar si se ha añadido un estilo o script concreto a la cola.
```php
WP_Register::isAdded($type, $name);
```

| Atributo | Descripción | Tipo de dato | Requerido | Por defecto
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |
| $name | Script or style ID | string | Yes | |

**@return** → boolean 

#### remove()
Eliminar antes de que se haya registrado el script o el estilo.
```php
WP_Register::isAdded($type, $name);
```

| Atributo | Descripción | Tipo de dato | Requerido | Por defecto
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |
| $name | Script or style ID | string | Yes | |

**@return** → boolean true

## Uso

Ejemplo de uso para esta biblioteca:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Josantonius\WP_Register\WP_Register;
```

#### Agregar script:

```php
WP_Register::add('script', [

    'name'  => 'HTML_script',
    'url'   => 'http://josantonius.com/js/html5.js'
]);
```

```php
WP_Register::add('script', [

    'name'    => 'NavigationScript',
    'url'     => 'http://josantonius.com/js/navigation.js',
    'place'   => 'admin',
    'deps'    => ['jquery'],
    'version' => '1.1.3',
    'footer'  => true,
    'params'  => ['date' => date('now')],
]);
```

Adicionalmente se crea un nonce para cada script utilizando su nombre. En este ejemplo, sería accesible desde JavaScript utilizando `NavigationScript.nonce`.

`wp_verify_nonce($nonce, 'NavigationScript');`

En el caso de scripts creados desde plugins se guarda como parámetro la ruta del directorio de plugins. En este ejemplo, seríá accesible desde JavaScript utilizando `NavigationScript.pluginUrl`.

#### Agregar estilo:

```php
WP_Register::add('style', [

    'name'  => 'EditorStyle',
    'url'   => 'http://josantonius.com/js/editor-style.css'
]);
```

```php
WP_Register::add('style', [

    'name'    => 'DefaultStyle',
    'url'     => 'http://josantonius.com/js/style.css',
    'place'   => 'admin',
    'deps'    => [],
    'version' => '1.1.3',
    'media'   => 'all'
])
```

#### Unificar:

```php
WP_Register::unify('UniqueID', 'http://josantonius.com/min/');
```

#### Unificar y minimizar:

```php
WP_Register::unify('UniqueID', 'http://josantonius.com/min/', true);
```

#### Unificar especificando diferentes urls para las rutas donde se guardarán los estilos y los scripts:

```php
WP_Register::unify('UniqueID', [

    'styles'  => 'http://josantonius.com/min/css/',
    'scripts' => 'http://josantonius.com/min/js/'
]);
```

#### Unificar y minimizar especificando diferentes urls para las rutas donde se guardarán los estilos y los scripts:

```php
WP_Register::unify('UniqueID', [

    'styles'  => 'http://josantonius.com/min/css/',
    'scripts' => 'http://josantonius.com/min/js/'
]);
```

#### Comprueba si un estilo o script ha sido añadido para ser registrado:

```php

WP_Register::isAdded('script', 'HTML_script');

WP_Register::isAdded('script', 'NavigationScript');

WP_Register::isAdded('style', 'EditorStyle');

WP_Register::isAdded('style', 'DefaultStyle');
```

#### Eliminar antes de que el script o el estilo hayan sido añadidos a la cola:

```php

WP_Register::remove('script', 'HTML_script');

WP_Register::remove('script', 'NavigationScript');

WP_Register::remove('style', 'EditorStyle');

WP_Register::remove('style', 'DefaultStyle');
```

### Tests 

Para ejecutar las [pruebas](tests/WP_Register/Test) simplemente:

    $ git clone https://github.com/Josantonius/WP_Register.git
    
    $ cd WP_Register

    $ bash bin/install-wp-tests.sh wordpress_test root '' localhost latest

    $ phpunit

### ☑ Tareas pendientes

- [ ] Implementar recolector de basura en archivos unificados
- [ ] Ordenar dependencias al unificar parámetros
- [x] Completar tests
- [ ] Mejorar la documentación

## Contribuir

1. Comprobar si hay incidencias abiertas o abrir una nueva para iniciar una discusión en torno a un fallo o función.
1. Bifurca la rama del repositorio en GitHub para iniciar la operación de ajuste.
1. Escribe una o más pruebas para la nueva característica o expón el error.
1. Haz cambios en el código para implementar la característica o reparar el fallo.
1. Envía pull request para fusionar los cambios y que sean publicados.

Esto está pensado para proyectos grandes y de larga duración.

## Repositorio

Los archivos de este repositorio se crearon y subieron automáticamente con [Reposgit Creator](https://github.com/Josantonius/BASH-Reposgit).

## Licencia

Este proyecto está licenciado bajo **licencia MIT**. Consulta el archivo [LICENSE](LICENSE) para más información.

## Copyright

2017 Josantonius, [josantonius.com](https://josantonius.com/)

Si te ha resultado útil, házmelo saber :wink:

Puedes contactarme en [Twitter](https://twitter.com/Josantonius) o a través de mi [correo electrónico](mailto:hello@josantonius.com).
