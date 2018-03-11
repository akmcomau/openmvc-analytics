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
		'analytics_url_id' => [
			'data_type'      => 'bigint',
			'null_allowed'   => FALSE,
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
		'analytics_request_created',
		'analytics_url_id',
		'analytics_session_id',
		'analytics_referer_id',
		'analytics_campaign_id',
	];

	protected $foreign_keys = [
		'analytics_url_id'      => ['analytics_url', 'analytics_url_id'],
		'analytics_session_id'  => ['analytics_session', 'analytics_session_id'],
		'analytics_referer_id'  => ['analytics_referer', 'analytics_referer_id'],
		'analytics_campaign_id' => ['analytics_campaign', 'analytics_campaign_id'],
	];

	public function getUrl() {
		if (isset($this->objects['url'])) {
			return $this->objects['url'];
		}

		$this->objects['url'] = $this->getModel('\modules\analytics\classes\models\AnalyticsUrl')->get([
			'id' => $this->analytics_url_id,
		]);
		return $this->objects['url'];
	}

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

	public function getEvents() {
		if (isset($this->objects['events'])) {
			return $this->objects['events'];
		}

		$this->objects['events'] = $this->getModel('\modules\analytics\classes\models\AnalyticsEvent')->getMulti([
			'analytics_request_id' => $this->id,
		]);
		return $this->objects['events'];
	}
}
