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
		if (count($this->request->getEvents()) == 0) {
			if ($this->config->is_robot) return;
			if (http_response_code() == 404) return;
			if ($this->request->getAuthentication()->administratorLoggedIn()) return;
		}

		// create the model objects
		$model = new Model($this->config, $this->database);
		$analytics_session = $model->getModel('\modules\analytics\classes\models\AnalyticsSession');
		$analytics_request = $model->getModel('\modules\analytics\classes\models\AnalyticsRequest');
		$analytics_referer = $model->getModel('\modules\analytics\classes\models\AnalyticsReferer');
		$analytics_campaign = $model->getModel('\modules\analytics\classes\models\AnalyticsCampaign');
		$analytics_url = $model->getModel('\modules\analytics\classes\models\AnalyticsUrl');
		$analytics_event_type = $model->getModel('\modules\analytics\classes\models\AnalyticsEventType');

		// check if a session already exists
		$session = NULL;
		if ($this->request->session->get(['analytics_session_id'])) {
			$session = $analytics_session->get([
				'id' => $this->request->session->get(['analytics_session_id']),
			]);
		}
		if (!$session) {
			// create the session
			$session = $model->getModel('\modules\analytics\classes\models\AnalyticsSession');
			$session->site_id = $this->config->siteConfig()->site_id;
			$session->php_id = session_id();
			$session->ip = $this->request->serverParam('REMOTE_ADDR');
			$session->user_agent = $this->request->serverParam('HTTP_USER_AGENT');

			// create the session record
			$session->insert();
			$this->request->session->set(['analytics_session_id'], $session->id);
		}
		else {
			$session->updateLastHit();
		}

		// create a request record
		$request = $model->getModel('\modules\analytics\classes\models\AnalyticsRequest');

		// get the campaign
		if ($this->request->getParam('utm_source')) {
			// lookup campaign
			$campaign = $analytics_campaign->get([
				'site_id' => $this->config->siteConfig()->site_id,
				'source' => $this->request->getParam('utm_source'),
				'medium' => $this->request->getParam('utm_medium'),
				'campaign' => $this->request->getParam('utm_campaign'),
				'term' => $this->request->getParam('utm_term'),
			]);

			// create the user agent
			if (!$campaign) {
				$campaign = $model->getModel('\modules\analytics\classes\models\AnalyticsCampaign');
				$campaign->site_id = $this->config->siteConfig()->site_id;
				$campaign->source = $this->request->getParam('utm_source') ? : '';
				$campaign->medium = $this->request->getParam('utm_medium') ? : '';
				$campaign->campaign = $this->request->getParam('utm_campaign') ? : '';
				$campaign->term = $this->request->getParam('utm_term') ? : '';
				$campaign->insert();
			}
			else {
				$campaign->updateLastHit();
			}

			// set the campaign id
			$request->analytics_campaign_id = $campaign->id;
		}

		// is there a referer
		$http_referer = $this->request->serverParam('HTTP_REFERER');
		if ($http_referer) {
			$referer = $analytics_referer->get([
				'site_id' => $this->config->siteConfig()->site_id,
				'url' => $http_referer,
			]);
			if (!$referer) {
				// parse the url
				$url = parse_url($http_referer);
				if ($url) {
					$referer = $model->getModel('\modules\analytics\classes\models\AnalyticsReferer');
					$referer->site_id = $this->config->siteConfig()->site_id;
					$referer->url = $http_referer;
					$referer->domain = $url["host"];
					$referer->insert();
				}
			}
			else {
				$referer->updateLastHit();
			}

			if ($referer) {
				$request->analytics_referer_id = $referer->id;
			}
		}

		// find the URL object
		$url = $analytics_url->get([
			'site_id' => $this->config->siteConfig()->site_id,
			'controller' => $this->request->getControllerName(),
			'method' => $this->request->getMethodName(),
			'params' => json_encode($this->request->getMethodParams()),
		]);
		if (!$url) {
			$url = $model->getModel('\modules\analytics\classes\models\AnalyticsUrl');
			$url->site_id = $this->config->siteConfig()->site_id;
			$url->controller = $this->request->getControllerName();
			$url->method = $this->request->getMethodName();
			$url->params = json_encode($this->request->getMethodParams());
			$url->insert();
		}
		else {
			$url->updateLastHit();
		}

		// Fill and insert the request record
		$request->analytics_session_id = $session->id;
		$request->analytics_url_id = $url->id;
		$request->response_code = http_response_code();
		$request->response_time = $response_time;
		$request->insert();

		// insert any event that occured
		foreach ($this->request->getEvents() as $page_event) {
			if (!isset($page_event['name']) || empty($page_event['name'])) continue;

			// find the event type object
			$event_type = $analytics_event_type->get([
				'site_id' => $this->config->siteConfig()->site_id,
				'name' => $page_event['name'],
			]);
			if (!$event_type) {
				$event_type = $model->getModel('\modules\analytics\classes\models\AnalyticsEventType');
				$event_type->site_id = $this->config->siteConfig()->site_id;
				$event_type->name = $page_event['name'];
				$event_type->insert();
			}
			else {
				$url->updateLastHit();
			}

			// create the event
			$event = $model->getModel('\modules\analytics\classes\models\AnalyticsEvent');
			$event->analytics_request_id = $request->id;
			$event->type_id = $event_type->id;
			$event->int = isset($page_event['int']) ? $page_event['int'] : NULL;
			$event->double = isset($page_event['double']) ? $page_event['double'] : NULL;
			$event->text  = isset($page_event['text']) ? $page_event['text'] : NULL;
			$event->insert();
		}
	}
}
