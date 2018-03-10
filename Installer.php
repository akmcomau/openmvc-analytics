<?php

namespace modules\analytics;

use ErrorException;
use core\classes\Config;
use core\classes\Database;
use core\classes\Language;
use core\classes\Model;
use core\classes\Menu;

class Installer {
	protected $config;
	protected $database;

	public function __construct(Config $config, Database $database) {
		$this->config = $config;
		$this->database = $database;
	}

	public function install() {
		$model = new Model($this->config, $this->database);

		$table = $model->getModel('\\modules\\analytics\\classes\\models\\AnalyticsReferer');
		$table->createTable();
		$table->createIndexes();
		$table->createForeignKeys();

		$table = $model->getModel('\\modules\\analytics\\classes\\models\\AnalyticsCampaign');
		$table->createTable();
		$table->createIndexes();
		$table->createForeignKeys();

		$table = $model->getModel('\\modules\\analytics\\classes\\models\\AnalyticsSession');
		$table->createTable();
		$table->createIndexes();
		$table->createForeignKeys();

		$table = $model->getModel('\\modules\\analytics\\classes\\models\\AnalyticsRequest');
		$table->createTable();
		$table->createIndexes();
		$table->createForeignKeys();
	}

	public function uninstall() {
		$model = new Model($this->config, $this->database);

		$table = $model->getModel('\\modules\\analytics\\classes\\models\\AnalyticsRequest');
		$table->dropTable();

		$table = $model->getModel('\\modules\\analytics\\classes\\models\\AnalyticsSession');
		$table->dropTable();

		$table = $model->getModel('\\modules\\analytics\\classes\\models\\AnalyticsCampaign');
		$table->dropTable();

		$table = $model->getModel('\\modules\\analytics\\classes\\models\\AnalyticsReferer');
		$table->dropTable();
	}

	public function enable() {
		$language = new Language($this->config);
		$language->loadLanguageFile('analytics.php', DS.'modules'.DS.'analytics');

		$layout_strings = $language->getFile('administrator/layout.php');
		$layout_strings['analytics'] = $language->get('analytics');
		$language->updateFile('administrator/layout.php', $layout_strings);

		$main_menu = new Menu($this->config, $language);
		$main_menu->loadMenu('menu_admin_main.php');
		$main_menu->insert_menu(['content'], 'analytics', [
			'controller' => 'administrator/Analytics',
			'method' => 'index',
			'text_tag' => 'analytics',
			'icon' => 'icon-eye',
			'children' => [
				'sessions' => [
					'controller' => 'administrator/Analytics',
					'method' => 'sessions',
				],
			],
		]
		);
		$main_menu->update();
	}

	public function disable() {
		$language = new Language($this->config);

		$layout_strings = $language->getFile('administrator/layout.php');
		unset($layout_strings['analytics']);
		$language->updateFile('administrator/layout.php', $layout_strings);

		// Remove some menu items to the admin menu
		$main_menu = new Menu($this->config, $language);
		$main_menu->loadMenu('menu_admin_main.php');
		$menu = $main_menu->getMenuData();
		unset($menu['analytics']);
		$main_menu->setMenuData($menu);
		$main_menu->update();
	}
}
