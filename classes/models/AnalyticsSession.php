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
		'analytics_referer_id' => [
			'data_type'      => 'bigint',
			'null_allowed'   => TRUE,
		],
		'analytics_campaign_id' => [
			'data_type'      => 'bigint',
			'null_allowed'   => TRUE,
		],
	];

	protected $indexes = [
		'site_id',
		'analytics_session_created',
		'analytics_session_ip',
		'analytics_referer_id',
		'analytics_campaign_id',
		'analytics_session_php_id'
	];

	protected $uniques = [];

	protected $foreign_keys = [
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
}
