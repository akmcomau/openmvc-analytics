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
		'analytics_referer_id' => [
			'data_type'      => 'bigint',
			'null_allowed'   => TRUE,
		],
		'analytics_campaign_id' => [
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
		'analytics_referer_id',
		'analytics_campaign_id',
	];

	protected $foreign_keys = [
		'analytics_session_id'  => ['analytics_session', 'analytics_session_id'],
		'customer_id'  => ['customer', 'customer_id'],
		'administrator_id'  => ['administrator', 'administrator_id'],
		'analytics_referer_id'    => ['analytics_referer', 'analytics_referer_id'],
		'analytics_campaign_id'    => ['analytics_campaign', 'analytics_campaign_id'],
	];

	public function getReferer() {
		if (isset($this->objects['referer'])) {
			return $this->objects['referer'];
		}

		$this->objects['referer'] = $this->getModel('\modules\analytics\classes\models\AnalyticsReferer')->get([
			'id' => $this->analytics_referer_id,
		]);
		return $this->objects['referer'];
	}

	public function getRefererHost() {
		$referer = $this->getReferer();
		if ($referer) {
			$url = parse_url($referer->url);
			if ($url) {
				return $url['host'];
			}
		}
		return NULL;
	}

	public function getCampaign() {
		if (isset($this->objects['campaign'])) {
			return $this->objects['campaign'];
		}

		$this->objects['campaign'] = $this->getModel('\modules\analytics\classes\models\AnalyticsCampaign')->get([
			'id' => $this->analytics_campaign_id,
		]);
		return $this->objects['campaign'];
	}

	public function getCampaignName() {
		$campaign = $this->getCampaign();
		if ($campaign) {
			return $campaign->name;
		}
		return FALSE;
	}
}
