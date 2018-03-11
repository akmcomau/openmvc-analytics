<?php

namespace modules\analytics\classes\models;

use core\classes\URL;
use core\classes\Model;
use core\classes\Request;

class AnalyticsCampaign extends Model {

	protected $table       = 'analytics_campaign';
	protected $primary_key = 'analytics_campaign_id';
	protected $columns     = [
		'analytics_campaign_id' => [
			'data_type'      => 'bigint',
			'auto_increment' => TRUE,
			'null_allowed'   => FALSE,
		],
		'analytics_campaign_name' => [
			'data_type'      => 'text',
			'data_length'    => 256,
			'null_allowed'   => TRUE,
		],
		'analytics_campaign_created' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_campaign_last_hit' => [
			'data_type'      => 'datetime',
			'null_allowed'   => FALSE,
			'default_value'  => 'CURRENT_TIMESTAMP',
		],
		'analytics_campaign_source' => [
			'data_type'      => 'text',
			'data_length'    => 64,
			'null_allowed'   => FALSE,
		],
		'analytics_campaign_medium' => [
			'data_type'      => 'text',
			'data_length'    => 64,
			'null_allowed'   => FALSE,
		],
		'analytics_campaign_campaign' => [
			'data_type'      => 'text',
			'data_length'    => 256,
			'null_allowed'   => FALSE,
		],
		'analytics_campaign_term' => [
			'data_type'      => 'text',
			'data_length'    => 256,
			'null_allowed'   => FALSE,
		],
	];

	protected $indexes = [
		'analytics_campaign_created',
		'analytics_campaign_last_hit',
		'analytics_campaign_source',
		'analytics_campaign_medium',
		'analytics_campaign_campaign',
		'analytics_campaign_term',
	];

	protected $uniques = [
		['analytics_campaign_source', 'analytics_campaign_medium', 'analytics_campaign_campaign', 'analytics_campaign_term'],
	];

	protected $foreign_keys = [
	];

	public function getHitCount() {
		return $this->getModel('\modules\analytics\classes\models\AnalyticsRequest')->getCount(
			['analytics_campaign_id' => $this->id ]
		);
	}
}
