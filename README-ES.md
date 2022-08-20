# PHP WordPress Register

[![Latest Stable Version](https://poser.pugx.org/josantonius/wp-register/v/stable)](https://packagist.org/packages/josantonius/wp-register)
[![License](https://poser.pugx.org/josantonius/wp-register/license)](LICENSE)

[English version](README.md)

Registrar, minificar y unificar recursos CSS y JavaScript en WordPress.

---

- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Métodos disponibles](#métodos-disponibles)
- [Cómo empezar](#cómo-empezar)
- [Uso](#uso)
- [Tests](#tests)
- [Patrocinar](#patrocinar)
- [Licencia](#licencia)

---

## Requisitos

Esta biblioteca es soportada por versiones de **PHP 5.6** o superiores y es compatible con versiones de **HHVM 3.0** o superiores.

## Instalación

La mejor forma de instalar esta extensión es a través de [Composer](http://getcomposer.org/download/).

Para instalar **PHP WP_Register library**, simplemente escribe:

    composer require josantonius/wp-register

El comando anterior sólo instalará los archivos necesarios, si prefieres **descargar todo el código fuente** puedes utilizar:

    composer require josantonius/wp-register --prefer-source

También puedes **clonar el repositorio** completo con Git:

    git clone https://github.com/josantonius/wp-register.git

O **instalarlo manualmente**:

[Download WP_Register.php](https://raw.githubusercontent.com/josantonius/wp-register/master/src/class-wp-register.php):

    wget https://raw.githubusercontent.com/josantonius/wp-register/master/src/class-wp-register.php

## Métodos disponibles

Métodos disponibles en esta biblioteca:

### - Agregar scripts o estilos

```php
WP_Register::add($type, $data);
```

| Atributo | Descripción | Tipo | Requerido | Predeterminado
| --- | --- | --- | --- | --- |
| $type | 'script' o 'style' | string | Sí | |

| Atributo | clave | Descripción | Tipo | Requerido | Predeterminado
| --- | --- | --- | --- | --- | --- |
| $data | | Settings | array | Sí | |
|  | name | ID único | string | Sí | |
|  | url | URL del archivo | string | Sí | |
|  | version | Versión | string | No | false |
|  | footer | **Solo para scripts** - Fijar en footer | boolean | No | true |
|  | attr | **Solo para scripts** - Atributo (defer/sync) | string | No | |

**@return** → boolean

### - Establecer si se fusiona el contenido de los archivos en un único archivo

```php
WP_Register::unify($id, $params, $minify);
```

| Atributo | Descripción | Tipo de dato | Requerido | Por defecto
| --- | --- | --- | --- | --- |
| $id | Action hook name | string | Yes | |
| $params | Path urls | mixed | Yes | |
| $minify | Minimize file content | boolean | No | false |

**@return** → boolean true

### - Comprobar si se ha añadido un estilo o script concreto a la cola

```php
WP_Register::is_added($type, $name);
```

| Atributo | Descripción | Tipo de dato | Requerido | Por defecto
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |
| $name | Script or style ID | string | Yes | |

**@return** → boolean

### - Eliminar antes de que se haya registrado el script o el estilo

```php
WP_Register::is_added($type, $name);
```

| Atributo | Descripción | Tipo de dato | Requerido | Por defecto
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |
| $name | Script or style ID | string | Yes | |

**@return** → boolean true

## Cómo empezar

Para utilizar esta clase con **Composer**:

```php
require __DIR__ . '/vendor/autoload.php';

use Josantonius\WP_Register;
```

Si la instalaste **manualmente**, utiliza:

```php
require_once __DIR__ . '/class-wp-register.php';

use Josantonius\WP_Register\WP_Register;
```

## Uso

Ejemplo de uso para esta biblioteca:

### - Agregar script

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

### - Agregar estilo

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

### - Unificar

```php
WP_Register::unify('UniqueID', 'http://josantonius.com/min/');
```

### - Unificar y minimizar

```php
WP_Register::unify('UniqueID', 'http://josantonius.com/min/', true);
```

### - Unificar especificando diferentes urls para las rutas donde se guardarán los estilos y los scripts

```php
WP_Register::unify('UniqueID', [

    'styles'  => 'http://josantonius.com/min/css/',
    'scripts' => 'http://josantonius.com/min/js/'
]);
```

### - Unificar y minimizar especificando diferentes urls para las rutas donde se guardarán los estilos y los scripts

```php
WP_Register::unify('UniqueID', [

    'styles'  => 'http://josantonius.com/min/css/',
    'scripts' => 'http://josantonius.com/min/js/'
    
], true);
```

### - Comprueba si un estilo o script ha sido añadido para ser registrado

```php

WP_Register::is_added('script', 'HTML_script');

WP_Register::is_added('script', 'NavigationScript');

WP_Register::is_added('style', 'EditorStyle');

WP_Register::is_added('style', 'DefaultStyle');
```

### - Eliminar antes de que el script o el estilo hayan sido añadidos a la cola

```php

WP_Register::remove('script', 'HTML_script');

WP_Register::remove('script', 'NavigationScript');

WP_Register::remove('style', 'EditorStyle');

WP_Register::remove('style', 'DefaultStyle');
```

## Tests

Para ejecutar las [pruebas](tests) necesitarás [Composer](http://getcomposer.org/download/) y seguir los siguientes pasos:

    git clone https://github.com/josantonius/wp-register.git
    
    cd WP_Register

    composer install

Ejecutar pruebas unitarias con [PHPUnit](https://phpunit.de/):

    composer phpunit

Ejecutar pruebas de estándares de código para [WordPress](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/) con [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer):

    composer phpcs

Ejecutar pruebas con [PHP Mess Detector](https://phpmd.org/) para detectar inconsistencias en el estilo de codificación:

    composer phpmd

Ejecutar todas las pruebas anteriores:

    composer tests

## Patrocinar

Si este proyecto te ayuda a reducir el tiempo de desarrollo,
[puedes patrocinarme](https://github.com/josantonius/lang/es-ES/README.md#patrocinar)
para apoyar mi trabajo :blush:

## Licencia

Este repositorio tiene una licencia [MIT License](LICENSE).

Copyright © 2017-2022, [Josantonius](https://github.com/josantonius/lang/es-ES/README.md#contacto)
