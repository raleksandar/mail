<?php defined('SYSPATH') or die('No direct script access.');

return array(

	'from' => array('admin@localhost' => 'Administrator'),

	'transports' => array(

		'smtp' => array(
			'host' => 'localhost',
			'port' => 25,
			'security' => null,
			'username' => null,
			'password' => null,
		),
	),
);