<?php

namespace modules\analytics\classes\models;

use core\classes\URL;
use core\classes\Model;
use core\classes\Request;

class AnalyticsEvent extends Model {

	protected $table       = 'analytics_event';
	protected $primary_key = 'analytics_event_id';
	protected $columns     = [
		'analytics_event_id' => [
			'data_type'      => 'bigint',
			'auto_increment' => TRUE,
			'null_allowed'   => FALSE,
		],
		'analytics_request_id' => [
			'data_type'      => 'bigint',
			'null_allowed'   => FALSE,
		],
		'analytics_event_type_id' => [
			'data_type'      => 'int',
			'auto_increment' => TRUE,
		],
		'analytics_event_created' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_event_int' => [
			'data_type'      => 'bigint',
			'null_allowed'   => TRUE,
		],
		'analytics_event_double' => [
			'data_type'      => 'double',
			'null_allowed'   => TRUE,
		],
		'analytics_event_text' => [
			'data_type'      => 'text',
			'data_length'    => 1024,
			'null_allowed'   => TRUE,
		],
	];

	protected $indexes = [
		'analytics_event_type_id',
		'analytics_event_created',
	];

	protected $foreign_keys = [
		'analytics_request_id'  => ['analytics_request', 'analytics_request_id'],
	];

	public function getRequest() {
		if (isset($this->objects['request'])) {
			return $this->objects['request'];
		}

		$this->objects['request'] = $this->getModel('\modules\analytics\classes\models\AnalyticsRequest')->get([
			'id' => $this->analytics_request_id,
		]);
		return $this->objects['request'];
	}

	public function getEventType() {
		if (isset($this->objects['event_type'])) {
			return $this->objects['event_type'];
		}

		$this->objects['event_type'] = $this->getModel('\modules\analytics\classes\models\AnalyticsEventType')->get([
			'id' => $this->analytics_event_type_id,
		]);
		return $this->objects['event_type'];
	}
}
