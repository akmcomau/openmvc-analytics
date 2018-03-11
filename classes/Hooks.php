<?php

namespace modules\analytics\classes;

use core\classes\exceptions\RedirectException;
use core\classes\Hook;
use core\classes\Model;
use core\classes\Authentication;
use core\classes\models\Customer;
use core\classes\models\Administrator;

class Hooks extends Hook {
	public function after_request($response_time) {
		// ignore robots or 404 or Admins (for now) TODO Make these optional
		if ($this->config->is_robot) return;
		if (http_response_code() == 404) return;
		if ($this->request->getAuthentication()->administratorLoggedIn()) return;

		// create the model objects
		$model = new Model($this->config, $this->database);
		$analytics_session = $model->getModel('\modules\analytics\classes\models\AnalyticsSession');
		$analytics_request = $model->getModel('\modules\analytics\classes\models\AnalyticsRequest');
		$analytics_referer = $model->getModel('\modules\analytics\classes\models\AnalyticsReferer');
		$analytics_campaign = $model->getModel('\modules\analytics\classes\models\AnalyticsCampaign');

		// check if a session already exists
		$session = NULL;
		if ($this->request->session->get(['analytics_session_id'])) {
			$session = $analytics_session->get([
				'id' => $this->request->session->get(['analytics_session_id']),
			]);
		}
		if (!$session) {
			// create the session
			$session = $analytics_session->getModel('\modules\analytics\classes\models\AnalyticsSession');
			$session->site_id = $this->config->siteConfig()->site_id;
			$session->php_id = session_id();
			$session->ip = $this->request->serverParam('REMOTE_ADDR');
			$session->user_agent = $this->request->serverParam('HTTP_USER_AGENT');

			// create the session record
			$session->insert();
			$this->request->session->set(['analytics_session_id'], $session->id);
		}
		else {
			$session->last_hit = date('c');
			$session->update();
		}

		// insert a request record
		$customer = $this->request->getAuthentication()->customerLoggedIn();
		$administrator = $this->request->getAuthentication()->administratorLoggedIn();
		$request = $analytics_request->getModel('\modules\analytics\classes\models\AnalyticsRequest');

		// get the campaign
		if ($this->request->getParam('utm_source')) {
			// lookup campaign
			$campaign = $analytics_campaign->get([
				'source' => $this->request->getParam('utm_source'),
					'medium' => $this->request->getParam('utm_medium'),
					'campaign' => $this->request->getParam('utm_campaign'),
					'term' => $this->request->getParam('utm_term'),
			]);

			// create the user agent
			if (!$campaign) {
				$campaign = $analytics_campaign->getModel('\modules\analytics\classes\models\AnalyticsCampaign');
				$campaign->source = $this->request->getParam('utm_source') ? : '';
				$campaign->medium = $this->request->getParam('utm_medium') ? : '';
				$campaign->campaign = $this->request->getParam('utm_campaign') ? : '';
				$campaign->term = $this->request->getParam('utm_term') ? : '';
				$campaign->insert();
			}

			// set the campaign id
			$request->analytics_campaign_id = $campaign->id;
		}

		// is there a referer
		$http_referer = $this->request->serverParam('HTTP_REFERER');
		if ($http_referer) {
			$referer = $analytics_referer->get([
				'url' => $http_referer,
			]);
			if (!$referer) {
				// parse the url
				$url = parse_url($http_referer);
				if ($url) {
					$referer = $analytics_referer->getModel('\modules\analytics\classes\models\AnalyticsReferer');
					$referer->url = $http_referer;
					$referer->domain = $url["host"];
					$referer->insert();
				}
			}

			if ($referer) {
				$request->analytics_referer_id = $referer->id;
			}
		}

		// Fill the request record
		$request->analytics_session_id = $session->id;
		$request->controller = $this->request->getControllerName();
		$request->method = $this->request->getMethodName();
		$request->params = json_encode($this->request->getMethodParams());
		$request->customer_id = $customer ? $customer['customer_id'] : NULL;
		$request->administrator_id = $administrator ? $administrator['administrator_id'] : NULL;
		$request->response_code = http_response_code();
		$request->response_time = $response_time;
		$request->insert();
	}
}
