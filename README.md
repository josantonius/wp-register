# PHP WordPress Register

[![Latest Stable Version](https://poser.pugx.org/josantonius/wp_register/v/stable)](https://packagist.org/packages/josantonius/wp_register) [![Total Downloads](https://poser.pugx.org/josantonius/wp_register/downloads)](https://packagist.org/packages/josantonius/wp_register) [![Latest Unstable Version](https://poser.pugx.org/josantonius/wp_register/v/unstable)](https://packagist.org/packages/josantonius/wp_register) [![License](https://poser.pugx.org/josantonius/wp_register/license)](https://packagist.org/packages/josantonius/wp_register) [![Travis](https://travis-ci.org/Josantonius/WP_Register.svg)](https://travis-ci.org/Josantonius/WP_Register)

[Versión en español](README-ES.md)

Register, minify and unify CSS and JavaScript resources in WordPress.

---

- [Installation](#installation)
- [Requirements](#requirements)
- [Quick Start and Examples](#quick-start-and-examples)
- [Available Methods](#available-methods)
- [Usage](#usage)
- [Tests](#tests)
- [TODO](#-todo)
- [Contribute](#contribute)
- [Repository](#repository)
- [License](#license)
- [Copyright](#copyright)

---

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

To install PHP Wordpress Register library, simply:

    $ composer require Josantonius/WP_Register

The previous command will only install the necessary files, if you prefer to download the entire source code (including tests, vendor folder, exceptions not used, docs...) you can use:

    $ composer require Josantonius/WP_Register --prefer-source

Or you can also clone the complete repository with Git:

    $ git clone https://github.com/Josantonius/WP_Register.git
    
## Requirements

This library is supported by PHP versions 5.6 or higher and is compatible with HHVM versions 3.0 or higher.

To use this library in HHVM (HipHop Virtual Machine) you will have to activate the scalar types. Add the following line "hhvm.php7.scalar_types = true" in your "/etc/hhvm/php.ini".

## Quick Start and Examples

To use this class, simply:

```php
require __DIR__ . '/vendor/autoload.php';

use Josantonius\WP_Register\WP_Register;
```

### Available Methods

Available methods in this library:

#### add()
Add scripts or styles.
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

**@return** → void  

#### unify()
Sets whether to merge the content of files into a single file.
```php
WP_Register::unify($id, $params, $minify);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $id | Action hook name | string | Yes | |
| $params | Path urls | mixed | Yes | |
| $minify | Minimize file content | boolean | No | false |

**@return** → boolean true

#### isAdded()
Check if a particular style or script has been added to be enqueued.
```php
WP_Register::isAdded($type, $name);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |
| $name | Script or style ID | string | Yes | |

**@return** → boolean 

#### remove()
Remove before script or style have been registered.
```php
WP_Register::isAdded($type, $name);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $type | 'script' or 'style' | string | Yes | |
| $name | Script or style ID | string | Yes | |

**@return** → boolean true

## Usage

Example of use for this library:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Josantonius\WP_Register\WP_Register;
```

#### Add script:

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

#### Add style:

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

#### Unify:

```php
WP_Register::unify('UniqueID', 'http://josantonius.com/min/');
```

#### Unify and minify:

```php
WP_Register::unify('UniqueID', 'http://josantonius.com/min/', true);
```

#### Unify specifying different url paths for styles and scripts:

```php
WP_Register::unify('UniqueID', [

    'styles'  => 'http://josantonius.com/min/css/',
    'scripts' => 'http://josantonius.com/min/js/'
]);
```

#### Unify and minify specifying different url paths for styles and scripts:

```php
WP_Register::unify('UniqueID', [

    'styles'  => 'http://josantonius.com/min/css/',
    'scripts' => 'http://josantonius.com/min/js/'
]);
```

#### Check if a particular style or script has been added to be registered:

```php

WP_Register::isAdded('script', 'HTML_script');

WP_Register::isAdded('script', 'NavigationScript');

WP_Register::isAdded('style', 'EditorStyle');

WP_Register::isAdded('style', 'DefaultStyle');
```

#### Remove before script or style have been enqueued:

```php

WP_Register::remove('script', 'HTML_script');

WP_Register::remove('script', 'NavigationScript');

WP_Register::remove('style', 'EditorStyle');

WP_Register::remove('style', 'DefaultStyle');
```

### Tests 

To run [tests](tests/WP_Register/Test) simply:

    $ git clone https://github.com/Josantonius/WP_Register.git
    
    $ cd WP_Register

    $ bash bin/install-wp-tests.sh wordpress_test root '' localhost latest

    $ phpunit

### ☑ TODO

- [ ] Implement garbage collector in unified files
- [ ] Sort dependencies when unifying parameters
- [x] Create tests
- [ ] Improve documentation

## Contribute

1. Check for open issues or open a new issue to start a discussion around a bug or feature.
1. Fork the repository on GitHub to start making your changes.
1. Write one or more tests for the new feature or that expose the bug.
1. Make code changes to implement the feature or fix the bug.
1. Send a pull request to get your changes merged and published.

This is intended for large and long-lived objects.

## Repository

All files in this repository were created and uploaded automatically with [Reposgit Creator](https://github.com/Josantonius/BASH-Reposgit).

## License

This project is licensed under **MIT license**. See the [LICENSE](LICENSE) file for more info.

## Copyright

2017 Josantonius, [josantonius.com](https://josantonius.com/)

If you find it useful, let me know :wink:

You can contact me on [Twitter](https://twitter.com/Josantonius) or through my [email](mailto:hello@josantonius.com).