# PHP WordPress Register

[![Latest Stable Version](https://poser.pugx.org/josantonius/WP_Register/v/stable)](https://packagist.org/packages/josantonius/WP_Register) [![Latest Unstable Version](https://poser.pugx.org/josantonius/WP_Register/v/unstable)](https://packagist.org/packages/josantonius/WP_Register) [![License](https://poser.pugx.org/josantonius/WP_Register/license)](LICENSE) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/ab4c47e60ada48b39c712c85a1a59322)](https://www.codacy.com/app/Josantonius/WP_Register?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Josantonius/WP_Register&amp;utm_campaign=Badge_Grade) [![Total Downloads](https://poser.pugx.org/josantonius/WP_Register/downloads)](https://packagist.org/packages/josantonius/WP_Register) [![Travis](https://travis-ci.org/Josantonius/WP_Register.svg)](https://travis-ci.org/Josantonius/WP_Register) [![WP](https://img.shields.io/badge/WordPress-Standar-1abc9c.svg)](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/) [![CodeCov](https://codecov.io/gh/Josantonius/WP_Register/branch/master/graph/badge.svg)](https://codecov.io/gh/Josantonius/WP_Register)

[English version](README.md)

Registrar, minificar y unificar recursos CSS y JavaScript en WordPress.

---

- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Métodos disponibles](#métodos-disponibles)
- [Cómo empezar](#cómo-empezar)
- [Uso](#uso)
- [Tests](#tests)
- [Tareas pendientes](#-tareas-pendientes)
- [Contribuir](#contribuir)
- [Repositorio](#repositorio)
- [Licencia](#licencia)
- [Copyright](#copyright)

---

## Requisitos

Esta biblioteca es soportada por versiones de **PHP 5.6** o superiores y es compatible con versiones de **HHVM 3.0** o superiores.

## Instalación 

La mejor forma de instalar esta extensión es a través de [Composer](http://getcomposer.org/download/).

Para instalar **PHP WP_Register library**, simplemente escribe:

    $ composer require Josantonius/WP_Register

El comando anterior sólo instalará los archivos necesarios, si prefieres **descargar todo el código fuente** puedes utilizar:

    $ composer require Josantonius/WP_Register --prefer-source

También puedes **clonar el repositorio** completo con Git:

    $ git clone https://github.com/Josantonius/WP_Register.git

O **instalarlo manualmente**:

[Download WP_Register.php](https://raw.githubusercontent.com/Josantonius/WP_Register/master/src/class-wp-register.php):

    $ wget https://raw.githubusercontent.com/Josantonius/WP_Register/master/src/class-wp-register.php

[Descargar Json.php](https://raw.githubusercontent.com/Josantonius/Json/master/src/Json.php):

    $ wget https://raw.githubusercontent.com/Josantonius/Json/master/src/Json.php

## Métodos disponibles

Métodos disponibles en esta biblioteca:

### - Agregar scripts o estilos:

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

### - Establecer si se fusiona el contenido de los archivos en un único archivo:

```php
WP_Register::unify($id, $params, $minify);
```

| Atributo | Descripción | Tipo de dato | Requerido | Por defecto
| --- | --- | --- | --- | --- |
| $id | Action hook name | string | Yes | |
| $params | Path urls | mixed | Yes | |
| $minify | Minimize file content | boolean | No | false |

**@return** → boolean true

### - Comprobar si se ha añadido un estilo o script concreto a la cola:

```php
WP_Register::is_added($type, $name);
```

| Atributo | Descripción | Tipo de dato | Requerido | Por defecto
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |
| $name | Script or style ID | string | Yes | |

**@return** → boolean 

### - Eliminar antes de que se haya registrado el script o el estilo:

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
require_once __DIR__ . '/Json.php';

use Josantonius\WP_Register\WP_Register;
```

## Uso

Ejemplo de uso para esta biblioteca:

### - Agregar script:

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

### - Agregar estilo:

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

### - Unificar:

```php
WP_Register::unify('UniqueID', 'http://josantonius.com/min/');
```

### - Unificar y minimizar:

```php
WP_Register::unify('UniqueID', 'http://josantonius.com/min/', true);
```

### - Unificar especificando diferentes urls para las rutas donde se guardarán los estilos y los scripts:

```php
WP_Register::unify('UniqueID', [

    'styles'  => 'http://josantonius.com/min/css/',
    'scripts' => 'http://josantonius.com/min/js/'
]);
```

### - Unificar y minimizar especificando diferentes urls para las rutas donde se guardarán los estilos y los scripts:

```php
WP_Register::unify('UniqueID', [

    'styles'  => 'http://josantonius.com/min/css/',
    'scripts' => 'http://josantonius.com/min/js/'
    
], true);
```

### - Comprueba si un estilo o script ha sido añadido para ser registrado:

```php

WP_Register::is_added('script', 'HTML_script');

WP_Register::is_added('script', 'NavigationScript');

WP_Register::is_added('style', 'EditorStyle');

WP_Register::is_added('style', 'DefaultStyle');
```

### - Eliminar antes de que el script o el estilo hayan sido añadidos a la cola:

```php

WP_Register::remove('script', 'HTML_script');

WP_Register::remove('script', 'NavigationScript');

WP_Register::remove('style', 'EditorStyle');

WP_Register::remove('style', 'DefaultStyle');
```

## Tests 

Para ejecutar las [pruebas](tests) necesitarás [Composer](http://getcomposer.org/download/) y seguir los siguientes pasos:

    $ git clone https://github.com/Josantonius/WP_Register.git
    
    $ cd WP_Register

    $ composer install

Ejecutar pruebas unitarias con [PHPUnit](https://phpunit.de/):

    $ composer phpunit

Ejecutar pruebas de estándares de código para [WordPress](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/) con [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer):

    $ composer phpcs

Ejecutar pruebas con [PHP Mess Detector](https://phpmd.org/) para detectar inconsistencias en el estilo de codificación:

    $ composer phpmd

Ejecutar todas las pruebas anteriores:

    $ composer tests

## ☑ Tareas pendientes

- [ ] Implementar recolector de basura en archivos unificados
- [ ] Ordenar dependencias al unificar parámetros
- [ ] Añadir nueva funcionalidad
- [ ] Mejorar pruebas
- [ ] Mejorar documentación
- [ ] Refactorizar código

## Contribuir

Si deseas colaborar, puedes echar un vistazo a la lista de
[issues](https://github.com/Josantonius/WP_Register/issues) o [tareas pendientes](#-tareas-pendientes).

**Pull requests**

* [Fork and clone](https://help.github.com/articles/fork-a-repo).
* Ejecuta el comando `composer install` para instalar dependencias.
  Esto también instalará las [dependencias de desarrollo](https://getcomposer.org/doc/03-cli.md#install).
* Ejecuta el comando `composer fix` para estandarizar el código.
* Ejecuta las [pruebas](#tests).
* Crea una nueva rama (**branch**), **commit**, **push** y envíame un
  [pull request](https://help.github.com/articles/using-pull-requests).

## Repositorio

La estructura de archivos de este repositorio se creó con [PHP-Skeleton](https://github.com/Josantonius/PHP-Skeleton).

## Licencia

Este proyecto está licenciado bajo **licencia MIT**. Consulta el archivo [LICENSE](LICENSE) para más información.

## Copyright

2017 Josantonius, [josantonius.com](https://josantonius.com/)

Si te ha resultado útil, házmelo saber :wink:

Puedes contactarme en [Twitter](https://twitter.com/Josantonius) o a través de mi [correo electrónico](mailto:hello@josantonius.com).
