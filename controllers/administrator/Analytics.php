<?php

namespace modules\analytics\controllers\administrator;

use core\classes\renderable\Controller;
use core\classes\Model;
use core\classes\Pagination;
use core\classes\FormValidator;

class Analytics extends Controller {

	protected $show_admin_layout = TRUE;

	protected $permissions = [
		'config' => ['administrator'],
	];

	public function config() {
	}

	public function index() {
	}

	public function sessions() {
		$this->language->loadLanguageFile('analytics.php', 'modules/analytics');

		$model    = new Model($this->config, $this->database);
		$session  = $model->getModel('\modules\analytics\classes\models\AnalyticsSession');
		$pagination = new Pagination($this->request, 'created', 'desc');

		$params = [
		];
		$sessions = $session->getMulti($params, $pagination->getOrdering(), $pagination->getLimitOffset());
		$pagination->setRecordCount($session->getCount($params));

		$data = [
			'sessions' => $sessions,
			'pagination' => $pagination,
		];

		$template = $this->getTemplate('pages/administrator/analytics/sessions.php', $data, 'modules/analytics');
		$this->response->setContent($template->render());
	}

	public function viewSession($session_id) {
		$this->language->loadLanguageFile('analytics.php', 'modules/analytics');

		$model    = new Model($this->config, $this->database);
		$session  = $model->getModel('\modules\analytics\classes\models\AnalyticsSession');

		$session = $session->get(['id' => $session_id]);
		if (!$session) {
			throw new RedirectException($this->getUrl('administrator/Error', 'error_404'));
		}

		$pagination = new Pagination($this->request, 'created', 'asc');
		$pagination->setRecordCount($session->getHitCount());
		$data = [
			'session' => $session,
			'pagination' => $pagination,
		];

		$template = $this->getTemplate('pages/administrator/analytics/view_session.php', $data, 'modules/analytics');
		$this->response->setContent($template->render());
	}
}
