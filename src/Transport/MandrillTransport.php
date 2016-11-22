<?php namespace Ashleydw\Mail\Transport;

use Swift_Mime_Message;

class MandrillTransport extends \Illuminate\Mail\Transport\MandrillTransport {

	protected $_options = array(
		'async' => true, // true to default to laravel 4's default
	);

	/**
	 * Same as the original but allows additional options to be set for the body (async etc)
	 * {@inheritdoc }
	 */
	public function __construct($key, array $options = array()) {
		$this->key = $key;
		$this->_options = $options;
	}

	/**
	 * Same as the original but includes _options and returns the response
	 * {@inheritdoc }
	 */
	public function send(Swift_Mime_Message $message, &$failedRecipients = null) {

		return $this->getHttpClient()->post('https://mandrillapp.com/api/1.0/messages/send-raw.json', [
			'body' => array_merge($this->_options, [
				'key' => $this->key,
				'raw_message' => (string) $message,
			]),
		]);

	}

	public function setOptions(array $options) {
		$this->_options = $options;
		return $this;
	}

	public function getAsync() {
		return $this->_options;
	}

}