<?php

namespace modules\analytics\classes\models;

use core\classes\URL;
use core\classes\Model;
use core\classes\Request;

class AnalyticsRequest extends Model {

	protected $table       = 'analytics_request';
	protected $primary_key = 'analytics_request_id';
	protected $columns     = [
		'analytics_request_id' => [
			'data_type'      => 'bigint',
			'auto_increment' => TRUE,
			'null_allowed'   => FALSE,
		],
		'analytics_session_id' => [
			'data_type'      => 'bigint',
			'null_allowed'   => FALSE,
		],
		'analytics_request_created' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_request_controller' => [
			'data_type'      => 'text',
			'data_length'    => 128,
			'null_allowed'   => FALSE,
		],
		'analytics_request_method' => [
			'data_type'      => 'text',
			'data_length'    => 128,
			'null_allowed'   => FALSE,
		],
		'analytics_request_params' => [
			'data_type'      => 'text',
			'data_length'    => 1024,
			'null_allowed'   => TRUE,
		],
		'customer_id' => [
			'data_type'      => 'bigint',
			'null_allowed'   => TRUE,
		],
		'administrator_id' => [
			'data_type'      => 'bigint',
			'null_allowed'   => TRUE,
		],
		'analytics_request_response_code' => [
			'data_type'      => 'smallint',
			'null_allowed'   => FALSE,
		],
		'analytics_request_response_time' => [
			'data_type'      => 'float',
			'null_allowed'   => FALSE,
		],
	];

	protected $indexes = [
		'analytics_session_id',
		'analytics_request_created',
		'lower(analytics_request_controller)',
		'lower(analytics_request_method)',
		'lower(analytics_request_params)',
	];

	protected $foreign_keys = [
		'analytics_session_id'  => ['analytics_session', 'analytics_session_id'],
		'customer_id'  => ['customer', 'customer_id'],
		'administrator_id'  => ['administrator', 'administrator_id'],
	];
}
