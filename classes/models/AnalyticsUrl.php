<?php

namespace modules\analytics\classes\models;

use core\classes\URL;
use core\classes\Model;
use core\classes\Request;

class AnalyticsUrl extends Model {

	protected $table       = 'analytics_url';
	protected $primary_key = 'analytics_url_id';
	protected $columns     = [
		'analytics_url_id' => [
			'data_type'      => 'bigint',
			'auto_increment' => TRUE,
			'null_allowed'   => FALSE,
		],
		'site_id' => [
			'data_type'      => 'int',
			'null_allowed'   => FALSE,
		],
		'analytics_url_created' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_url_last_hit' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_url_controller' => [
			'data_type'      => 'text',
			'data_length'    => 128,
			'null_allowed'   => FALSE,
		],
		'analytics_url_method' => [
			'data_type'      => 'text',
			'data_length'    => 128,
			'null_allowed'   => FALSE,
		],
		'analytics_url_params' => [
			'data_type'      => 'text',
			'data_length'    => 1024,
			'null_allowed'   => TRUE,
		],
	];

	protected $indexes = [
		'site_id',
		'analytics_url_created',
		'analytics_url_last_hit',
		'analytics_url_controller',
		'analytics_url_method',
		'analytics_url_params',
	];

	protected $foreign_keys = [];

	public function getHitCount() {
		return $this->getModel('\modules\analytics\classes\models\AnalyticsRequest')->getCount(
			['analytics_url_id' => $this->id ]
		);
	}

	public function updateLastHit() {
		$sql = "
			UPDATE analytics_url
			SET analytics_url_last_hit = CURRENT_TIMESTAMP
			WHERE analytics_url_id = ".(int)$this->id;

		return $this->database->executeQuery($sql);
	}
}
