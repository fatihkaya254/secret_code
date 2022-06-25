<?php

/**
 *@package SecretCode
 */

require_once dirname(__FILE__, 3) . '/Base/BaseController.php';

class SecretCallBacks extends BaseController
{
	public function managecoe()
	{
		return require_once("$this->plugin_path/templates/managecoe.php");
	}
	public function managesecrets()
	{
		return require_once("$this->plugin_path/templates/managesecrets.php");
	}
}
