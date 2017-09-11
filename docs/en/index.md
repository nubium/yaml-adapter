Quickstart
==========

Integration of [Yaml](https://github.com/Symfony/Yaml) into Nette Framework DI Container.


Installation
------------

The best way to install Nubium/Yaml-Adapter is using  [Composer](http://getcomposer.org/):

```sh
$ composer require nubium/yaml-adapter
```


Minimal configuration
---------------------

You have to overide [nette/bootstrap](https://github.com/nette/boostrap/)'s Configurator::createLoader() method.
 

```php
<?php

namespace Nubium\Bootstrap;

use Nubium\DI\Config\Adapters\YamlAdapter;

class Configurator extends \Nette\Configurator
{
	protected function createLoader()
	{
		$loader = parent::createLoader();
		$loader->addAdapter('yml', YamlAdapter::class);
		$loader->addAdapter('yaml', YamlAdapter::class);

		return $loader;
	}
}
```

Now you can load yml and yaml configuration files! :)
