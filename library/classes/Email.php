<?php

/**
 * Email class
 * This class handles the creation and sending of emails
 */

class Email {

	// Variables
	private $recipient;
	private $subject;
	private $body;
	private $officer;
	private $from = 'Ashkandari <noreply@ashkandari.com>';

	/**
	 * Construction function
	 * @param $recipient (varchar) => Email address we're sending this too
	 * @param $subject (varachar) => Email subject
	 * @param $body (HTML) => Copy of the content we're sending this too
	 */
	public function __construct($recipient = NULL, $subject = NULL, $body = NULL) {

		// Set the details
		$this->setRecipient($recipient);
		$this->setSubject($subject);
		$this->setBody($body);

	}

	/**
	 * Set the recipient
	 * @param $recipient (varchar) => Email address we're sending this too
	 */
	public function setRecipient($recipient = NULL) {

		if(isset($recipient)) {

			$this->recipient = $recipient;

			return true;
		}

		return false;
	}

	/**
	 * Set the subject
	 * @param $subject (varchar) => Email subject
	 */
	public function setSubject($subject = NULL) {

		if(isset($subject)) {

			$this->subject = $subject;

			return true;
		}

		return false;
	}

	/**
	 * Set the body
	 * @param $body (HTML) => Copy of the content we're sending this too
	 */
	public function setBody($body = NULL) {

		if(isset($body)) {

			$this->body = $body;

			return true;
		}

		return false;
	}

	/**
	 * Set the officer this is being sent as
	 * @param $officer (account object) => Account instance of the officer
	 */
	public function setSender($officer) {

		if($officer->isAdmin()) {

			$this->officer = $officer;
			$this->from = $officer->getPrimaryCharacter()->name .' <noreply@ashkandari.com>';
			$this->subject = '[Ashkandari] '.$this->subject;

			return true;
		}

		return false;
	}

	/**
	 * Send the email
	 */
	public function send() {

		if(isset($this->recipient) && isset($this->subject) && isset($this->body)) {

			// Prepare the full email
			$body = file_get_contents(BASE_PATH.'/data/html/email.html');
			$body = str_replace('%SUBJECT%', $this->subject, $body);
			$body = str_replace('%BASE_URL%', BASE_URL, $body);
			$body = str_replace('%BODY%', $this->body, $body);
			$body = str_replace('%SENDER%', $this->officer->getPrimaryCharacter()->name, $body);
			(isset($this->officer) ? str_replace('%OFFICER', '<p>Officer of Ashkandari</p>', $body) : '');
			$body = str_replace('%YEAR%', date('Y'), $body);

			// Prepare the email headers
			$headers = "MIME-Version: 1.0\r\n
			Content-type: text/html; charset=utf-8\r\n
			From: ". $this->from;

			// Send the email
			mail($this->recipient, $this->subject, $body, $headers);
			
			return true;
		}

		return false;
	}
}