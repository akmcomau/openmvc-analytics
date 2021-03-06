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
		'site_id' => [
			'data_type'      => 'int',
			'null_allowed'   => FALSE,
		],
		'analytics_referer_created' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_referer_last_hit' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_referer_domain' => [
			'data_type'      => 'text',
			'data_length'    => 256,
			'null_allowed'   => FALSE,
		],
		'analytics_referer_url' => [
			'data_type'      => 'text',
			'data_length'    => 1024,
			'null_allowed'   => FALSE,
		],
	];

	protected $indexes = [
		'site_id',
		'lower(analytics_referer_domain)',
		'analytics_referer_last_hit',
	];

	protected $foreign_keys = [];

	public function getHitCount() {
		return $this->getModel('\modules\analytics\classes\models\AnalyticsRequest')->getCount(
			['analytics_referer_id' => $this->id ]
		);
	}

	public function updateLastHit() {
		$sql = "
			UPDATE analytics_referer
			SET analytics_referer_last_hit = CURRENT_TIMESTAMP
			WHERE analytics_referer_id = ".(int)$this->id;

		return $this->database->executeQuery($sql);
	}
}
