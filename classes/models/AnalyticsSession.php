<?php

namespace modules\analytics\classes\models;

use core\classes\URL;
use core\classes\Model;
use core\classes\Request;

class AnalyticsSession extends Model {

	protected $table       = 'analytics_session';
	protected $primary_key = 'analytics_session_id';
	protected $columns     = [
		'analytics_session_id' => [
			'data_type'      => 'bigint',
			'auto_increment' => TRUE,
			'null_allowed'   => FALSE,
		],
		'analytics_session_php_id' => [
			'data_type'      => 'text',
			'null_allowed'   => FALSE,
		],
		'site_id' => [
			'data_type'      => 'int',
			'null_allowed'   => FALSE,
		],
		'analytics_session_created' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_session_last_hit' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_session_ip' => [
			'data_type'      => 'inet',
			'null_allowed'   => FALSE,
		],
		'analytics_session_user_agent' => [
			'data_type'      => 'text',
			'null_allowed'   => FALSE,
		],
	];

	protected $indexes = [
		'site_id',
		'analytics_session_created',
		'analytics_session_ip',
		'analytics_session_php_id'
	];

	protected $uniques = [];

	protected $foreign_keys = [
	];

	public function getHitCount() {
		return $this->getModel('\modules\analytics\classes\models\AnalyticsRequest')->getCount(
			['analytics_session_id' => $this->id ]
		);
	}

	public function getRequests($ordering = NULL, $pagination = NULL, $grouping = NULL) {
		return $this->getModel('\modules\analytics\classes\models\AnalyticsRequest')->getMulti(
			['analytics_session_id' => $this->id ],
			$ordering, $pagination, $grouping
		);
	}

	public function updateLastHit() {
		$sql = "
			UPDATE analytics_session
			SET analytics_session_last_hit = CURRENT_TIMESTAMP
			WHERE analytics_session_id = ".(int)$this->id;

		return $this->database->executeQuery($sql);
	}
}
