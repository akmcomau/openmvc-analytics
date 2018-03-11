<?php

namespace modules\analytics\classes\models;

use core\classes\URL;
use core\classes\Model;
use core\classes\Request;

class AnalyticsEventType extends Model {

	protected $table       = 'analytics_event_type';
	protected $primary_key = 'analytics_event_type_id';
	protected $columns     = [
		'analytics_event_type_id' => [
			'data_type'      => 'bigint',
			'auto_increment' => TRUE,
			'null_allowed'   => FALSE,
		],
		'site_id' => [
			'data_type'      => 'int',
			'null_allowed'   => FALSE,
		],
		'analytics_event_type_name' => [
			'data_type'      => 'text',
			'data_length'    => 256,
		],
		'analytics_event_type_created' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_event_type_last_hit' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
	];

	protected $indexes = [
		'site_id',
		'analytics_event_type_created',
		'analytics_event_type_last_hit',
	];

	protected $foreign_keys = [];

	public function updateLastHit() {
		$sql = "
			UPDATE analytics_event_type
			SET analytics_event_type_last_hit = CURRENT_TIMESTAMP
			WHERE analytics_event_type_id = ".(int)$this->id;

		return $this->database->executeQuery($sql);
	}
}
