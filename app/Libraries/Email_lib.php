<?php

namespace app\Libraries;

use CodeIgniter\Email\Email;
use CodeIgniter\Encryption\Encryption;
use CodeIgniter\Encryption\EncrypterInterface;
use Config\Services;


/**
 * Email library
 *
 * Library with utilities to configure and send emails
 *
 * @property email email
 * @property encryption encryption
 * @property encrypterinterface encrypter
 * @property array config
 */

class Email_lib
{
  	public function __construct()
	{
		$this->email = new Email();
		$this->config = config('OSPOS')->settings;
		$encrypter = Services::encrypter();
		
		
		$email_config = [
			'mailtype' => 'html',
			'useragent' => 'OSPOS',
			'validate' => TRUE,
			'protocol' => $this->config['protocol'],
			'mailpath' => $this->config['mailpath'],
			'smtp_host' => $this->config['smtp_host'],
			'smtp_user' => $this->config['smtp_user'],
			'smtp_pass' => $encrypter->decrypt($this->config['smtp_pass']),
			'smtp_port' => $this->config['smtp_port'],
			'smtp_timeout' => $this->config['smtp_timeout'],
			'smtp_crypto' => $this->config['smtp_crypto']
		];

		$this->email->initialize($email_config);
	}

	/**
	 * Email sending function
	 * Example of use: $response = sendEmail('john@doe.com', 'Hello', 'This is a message', $filename);
	 */
	public function sendEmail(string $to, string $subject, string $message, string $attachment = NULL): bool
	{
		$email = $this->email;

		$email->setFrom($this->config['email'], $this->config['company']);
		$email->setTo($to);
		$email->setSubject($subject);
		$email->setMessage($message);

		if(!empty($attachment))
		{
			$email->attach($attachment);
		}

		$result = $email->send();

		if(!$result)
		{
			error_log($email->printDebugger());
		}

		return $result;
	}
}
