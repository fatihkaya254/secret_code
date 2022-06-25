<?php 


/**
*@package MeetingParents
*/

require_once dirname(__FILE__, 2).'/Api/SettingsApi.php';
require_once dirname(__FILE__, 2).'/Api/Callbacks/secretCallBacks.php';
require_once dirname(__FILE__, 2).'/Base/BaseController.php';


/**
* @package SecretCode
*/

class Admin extends BaseController
{
	public $settings;
	public $callbacks;
	public $secretCallBack;
	public $pages = array();
	public $subpages = array();



	public function register()
	{
		$this->secretCallBack = new SecretCallBacks();
		$this->settings = new SettingsApi();
		$this->setPages();
		$this->setSubPages();
		$this->settings->addPages( $this->pages)->withSubPage('Ayarlar')->addSubPages( $this->subpages)->register();
	}


	public function setPages(){
		$this->pages = array(
			array(
				'page_title' => 'Secret Code',
				'menu_title' => 'Secret Code',
				'capability' => 'delete_private_pages',
				'menu_slug' => 'secret_code',
				'callback' => array( $this->secretCallBack, 'managecoe'),
				'icon_url' => 'dashicons-editor-removeformatting',
				'position' => 110
			)
		);
	}

	public function setSubPages(){
		$this->subpages = array(
			array(
				'parent_slug' => 'secret_code',
				'page_title' => 'Admin',
				'menu_title' => 'Kodlar',
				'capability' => 'delete_private_pages',
				'menu_slug' => 'manage_secrets',
				'callback' => array( $this->secretCallBack, 'managesecrets'),
			),
		);
	}
}
