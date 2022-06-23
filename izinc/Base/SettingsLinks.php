<?php

/**
* @package SecretCode
*/

require_once dirname(__FILE__, 2).'/Base/BaseController.php';

class SettingsLinks extends BaseController
{
	public function register(){
		add_filter("plugin_action_links_$this->plugin", array($this, 'settings_link'));
	}

	public function settings_link($links){
		$settings_link = '<a href="admin.php?page=secret_code">Settings</a>';
		array_push($links, $settings_link);
		return $links;
	}
}