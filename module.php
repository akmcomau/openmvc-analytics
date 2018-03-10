<?php
$_MODULE = [
	"name" => "Analytics",
	"description" => "Track page views and generate reports",
	"namespace" => "\\modules\\analytics",
	"config_controller" => "administrator\\Analytics",
	"hooks" => [
		"request" => [
			"after_request" => "classes\\Hooks",
		]
	],
	"controllers" => [
		"administrator\\Analytics",
	],
	"default_config" => [
	]
];
