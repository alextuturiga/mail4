<?php

namespace Ashleydw\Mail;

class Mailer extends \Illuminate\Mail\Mailer {

	/**
	 * the same as the original, but returns the status
	 * {@inheritdoc }
	 */
	public function send($view, array $data, $callback) {
		// First we need to parse the view, which could either be a string or an array
		// containing both an HTML and plain text versions of the view which should
		// be used when sending an e-mail. We will extract both of them out here.
		list($view, $plain) = $this->parseView($view);

		$data['message'] = $message = $this->createMessage();

		$this->callMessageBuilder($callback, $message);

		// Once we have retrieved the view content for the e-mail we will set the body
		// of this message using the HTML type, which will provide a simple wrapper
		// to creating view based emails that are able to receive arrays of data.
		$this->addContent($message, $view, $plain, $data);

		$message = $message->getSwiftMessage();

		return $this->sendSwiftMessage($message);
	}

	/**
	 * the same as the original, but returns the status
	 * {@inheritdoc }
	 */
	protected function sendSwiftMessage($message) {
		if ($this->events) {
			$this->events->fire('mailer.sending', array($message));
		}

		if (!$this->pretending) {
			return $this->swift->send($message, $this->failedRecipients);
		}
		elseif (isset($this->logger)) {
			$this->logMessage($message);

			return true;
		}
	}

}