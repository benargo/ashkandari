<?php

/**
 * Page Class
 * This class acts as a router for the application. 
 * It calculates what actions to do based on the given URL the user is visiting.
 */

class Page {

	/* Variables */
	private $page_title;
	private $header;
	private $page;
	private $footer;
	public $account;
	public $character;

	/**
	 * Construction function
	 * This function switches through the requested URL
	 * and uses REGEX to determine which page to display
	 */
	public function __construct() {

		$this->checkAccount();
		$this->getPage();
		$this->getPageTitle();
		$this->setHead();

	}

	/**
	 * Check if there is an account
	 */
	public function checkAccount() {
		if(isset($_COOKIE['account'])) {

			$this->account = new Account(decrypt($_COOKIE['account']));
			$this->character = $this->account->getPrimaryCharacter();

			setcookie('account', encrypt($this->account->id), time()+60*60*24, '/', 'ashkandari.com');
			$_SESSION['account'] = $this->account->id;
		} elseif(isset($_SESSION['account'])) {

			$this->account = new Account($_SESSION['account']);
			$this->character = $this->account->getPrimaryCharacter();
		}
	}

	/**
	 * Get the page
	 */
	private function getPage() {

		if(file_exists(BASE_PATH.'/library/pages/'.$_SERVER['REQUEST_URI'].'.php')) {

			ob_start();
			include(BASE_PATH.'/library/pages/'.$_SERVER['REQUEST_URI'].'.php');
			$this->page = ob_get_contents();
			ob_end_clean();
		}

		ob_start();
		include(BASE_PATH.'/data/html/404.html');
		$this->page = ob_get_contents();
		ob_end_clean();
	}

	/**
	 * Get the page title
	 */
	private function getPageTitle() {

		$this->title = '';

		if($this->title = preg_match('/\<\!\-\-\[\T\I\T\L\E\](.*)\-\-\>', $this->page)) {

			$this->title = str_replace('<!--[TITLE]', '', $this->title);
			$this->title = str_replace('-->', '', $this->title);
			$this->title .= ' - ';
		}

		$this->title .= 'Ashkandari - Tarren Mill';
	}

	/**
	 * Set the page title
	 */
	private function setPageTitle() {

		if(empty($this->title)) {

			$this->getPageTitle();
		}

		$this->head = str_replace('%TITLE%', $this->title, $this->head);
		$this->page = str_replace('search', replace, subject)
	}

	/**
	 * Buffer the head
	 */
	private function setHead() {

		if(file_exists(BASE_PATH.'/data/html/head.html')) {

			$this->head = file_get_contents(BASE_PATH.'/data/html/head.html');
			$this->head = str_replace('%PROTOCOL%', PROTOCOL, $this->head);
			$this->head = str_replace('%BASE_URL%', BASE_URL, $this->head);
			$this->setPageTitle();
			$this->setAdditionalScripts();
			$this->setServiceCell();
			$this->setPrimaryNavigation();
		}
	}

	

}