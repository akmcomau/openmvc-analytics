<?php

namespace modules\analytics\classes\models;

use core\classes\URL;
use core\classes\Model;
use core\classes\Request;

class AnalyticsReferer extends Model {

	protected $table       = 'analytics_referer';
	protected $primary_key = 'analytics_referer_id';
	protected $columns     = [
		'analytics_referer_id' => [
			'data_type'      => 'bigint',
			'auto_increment' => TRUE,
			'null_allowed'   => FALSE,
		],
		'analytics_referer_url' => [
			'data_type'      => 'text',
			'data_length'    => 1024,
			'null_allowed'   => FALSE,
		],
		'analytics_referer_domain' => [
			'data_type'      => 'text',
			'data_length'    => 256,
			'null_allowed'   => FALSE,
		],
	];

	protected $indexes = [
		'lower(analytics_referer_domain)',
	];

	protected $foreign_keys = [];
}
