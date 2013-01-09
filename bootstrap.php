<?php

$module_paths = \Config::get('module_paths');
array_push($module_paths,  __DIR__ . '/modules/' );
\Config::set('module_paths', $module_paths);

Autoloader::add_classes([
	'Datagenerator\DataGenerator' => __DIR__.'/classes/datagenerator.php',
	'Datagenerator\FieldTemplate' => __DIR__.'/classes/fieldtemplate.php',
]);
