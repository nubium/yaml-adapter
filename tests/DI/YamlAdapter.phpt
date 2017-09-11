<?php

declare(strict_types=1);

use Nette\DI\Config;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

define('TEMP_FILE', TEMP_DIR . '/cfg.neon');


$configLoader = new Config\Loader;
$configLoader->addAdapter('yaml', new \Nubium\DI\Config\Adapters\YamlAdapter());
$config = $configLoader->load(__DIR__ . '/fixtures/generic.yaml');
Assert::same([
	'section' => [
		'webname' => 'the example',
		'database' => [
			'adapter' => 'pdo_mysql',
			'params' => [
				'host' => 'db.example.com',
				'username' => 'dbuser',
				'password' => 'secret',
				'dbname' => 'dbname',
			],
		],
		'timeout' => 10,
		'display_errors' => true,
		'html_errors' => 'no', // no, yes and so not is not casted to boolean in yaml
		'items' => [10, 20],
	],
	'override' => [],
	'nothing' => null,
], $config);


$config = $configLoader->load(__DIR__ . '/fixtures/section.inheritance.yaml', 'development');
Assert::same([
	'application' => ['applicationConfigOveriden' => true], // common section has errorPresenter config which is overridden in development
	'database' => ['user' => 'root'],
], $config);
