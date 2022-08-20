# PHP WordPress Register

[![Latest Stable Version](https://poser.pugx.org/josantonius/wp-register/v/stable)](https://packagist.org/packages/josantonius/wp-register)
[![License](https://poser.pugx.org/josantonius/wp-register/license)](LICENSE)

[Versión en español](README-ES.md)

Register, minify and unify CSS and JavaScript resources in WordPress.

---

- [Requirements](#requirements)
- [Installation](#installation)
- [Available Methods](#available-methods)
- [Quick Start](#quick-start)
- [Usage](#usage)
- [Tests](#tests)
- [Sponsor](#Sponsor)
- [License](#license)

---

## Requirements

This library is supported by **PHP versions 5.6** or higher and is compatible with **HHVM versions 3.0** or higher.

## Installation

The preferred way to install this extension is through [Composer](http://getcomposer.org/download/).

To install **WP_Register library**, simply:

    composer require josantonius/wp-register

The previous command will only install the necessary files, if you prefer to **download the entire source code** you can use:

    composer require josantonius/wp-register --prefer-source

You can also **clone the complete repository** with Git:

    git clone https://github.com/josantonius/wp-register.git

Or **install it manually**:

[Download WP_Register.php](https://raw.githubusercontent.com/josantonius/wp-register/master/src/class-wp-register.php):

    wget https://raw.githubusercontent.com/josantonius/wp-register/master/src/class-wp-register.php

## Available Methods

Available methods in this library:

### - Add scripts or styles

```php
WP_Register::add($type, $data);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |

| Attribute | key | Description | Type | Required | Default
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

**@return** (boolean)

### - Sets whether to merge the content of files into a single file

```php
WP_Register::unify($id, $params, $minify);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $id | Action hook name | string | Yes | |
| $params | Path urls | mixed | Yes | |
| $minify | Minimize file content | boolean | No | false |

**@return** (boolean true)

### - Check if a particular style or script has been added to be enqueued

```php
WP_Register::is_added($type, $name);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |
| $name | Script or style ID | string | Yes | |

**@return** (boolean)

### - Remove before script or style have been registered

```php
WP_Register::remove($type, $name);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |
| $name | Script or style ID | string | Yes | |

**@return** (boolean true)

## Quick Start

To use this library with **Composer**:

```php
require __DIR__ . '/vendor/autoload.php';

use Josantonius\WP_Register;
```

Or If you installed it **manually**, use it:

```php
require_once __DIR__ . '/class-wp-register.php';

use Josantonius\WP_Register\WP_Register;
```

## Usage

Example of use for this library:

### - Add script

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

Additionally, a nonce is created for each script using its name. In this example, it will be accessible from JavaScript using `NavigationScript.nonce`.

`wp_verify_nonce($nonce, 'NavigationScript');`

In the case of scripts created from plugins, the path of the plugin directory is saved as a parameter. In this example, it will be accessible from JavaScript using `NavigationScript.pluginUrl`.

### - Add style

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

### - Unify

```php
WP_Register::unify('UniqueID', 'http://josantonius.com/min/');
```

### - Unify and minify

```php
WP_Register::unify('UniqueID', 'http://josantonius.com/min/', true);
```

### - Unify specifying different url paths for styles and scripts

```php
WP_Register::unify('UniqueID', [

    'styles'  => 'http://josantonius.com/min/css/',
    'scripts' => 'http://josantonius.com/min/js/'
]);
```

### - Unify and minify specifying different url paths for styles and scripts

```php
WP_Register::unify('UniqueID', [

    'styles'  => 'http://josantonius.com/min/css/',
    'scripts' => 'http://josantonius.com/min/js/'
    
], true);
```

### - Check if a particular style or script has been added to be registered

```php

WP_Register::is_added('script', 'HTML_script');

WP_Register::is_added('script', 'NavigationScript');

WP_Register::is_added('style', 'EditorStyle');

WP_Register::is_added('style', 'DefaultStyle');
```

### - Remove before script or style have been enqueued

```php

WP_Register::remove('script', 'HTML_script');

WP_Register::remove('script', 'NavigationScript');

WP_Register::remove('style', 'EditorStyle');

WP_Register::remove('style', 'DefaultStyle');
```

## Tests

To run [tests](tests) you just need [composer](http://getcomposer.org/download/) and to execute the following:

    git clone https://github.com/josantonius/wp-register.git
    
    cd WP_Register

    bash bin/install-wp-tests.sh wordpress_test root '' localhost latest

    composer install

Run unit tests with [PHPUnit](https://phpunit.de/):

    composer phpunit

Run [WordPress](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/) code standard tests with [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer):

    composer phpcs

Run [PHP Mess Detector](https://phpmd.org/) tests to detect inconsistencies in code style:

    composer phpmd

Run all previous tests:

    composer tests

## Sponsor

If this project helps you to reduce your development time,
[you can sponsor me](https://github.com/josantonius#sponsor) to support my open source work :blush:

## License

This repository is licensed under the [MIT License](LICENSE).

Copyright © 2017-2022, [Josantonius](https://github.com/josantonius#contact)
