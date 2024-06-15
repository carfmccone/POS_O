<?php
namespace App\Config\Validation;

use App\Models\Employee;
use CodeIgniter\HTTP\IncomingRequest;
use Config\Services;

/**
 * @property employee employee
 * @property IncomingRequest request
 */
class OSPOSRules
{
	/**
	 * Validates the username and password sent to the login view. User is logged in on successful validation.
	 *
	 * @param string $username Username to check against.
	 * @param string $fields Comma separated string of the fields for validation.
	 * @param array $data Data sent to the view.
	 * @param string|null $error The error sent back to the validation handler on failure.
	 * @return bool True if validation passes or false if there are errors.
	 */
	public function login_check(string $username, string $fields , array $data, ?string &$error = null): bool
	{
		$this->employee = model('Employee');
		$this->request = Services::request();

		//Installation Check
		if(!$this->installation_check())
		{
			$error = lang('Login.invalid_installation');

			return false;
		}

		//Username and Password Check
		$password = $data['password'];
		if(!$this->employee->login($username, $password))
		{
			$error = lang('Login.invalid_username_and_password');

			return false;
		}

		//GCaptcha Check
		$gcaptcha_enabled = array_key_exists('gcaptcha_enable', config('OSPOS')->settings)
			? config('OSPOS')->settings['gcaptcha_enable']
			: false;

		if($gcaptcha_enabled)
		{
			$g_recaptcha_response = $this->request->getPost('g-recaptcha-response');

			if(!$this->gcaptcha_check($g_recaptcha_response))
			{
				$error = lang('Login.invalid_gcaptcha');

				return false;
			}
		}

		return true;
	}

	/**
	 * Checks to see if GCaptcha verification was successful.
	 *
	 * @param $response
	 * @return bool true on successful GCaptcha verification or false if GCaptcha failed.
	 */
	private function gcaptcha_check($response): bool
	{
		if(!empty($response))
		{
			$check = [
				'secret'   => config('OSPOS')->settings['gcaptcha_secret_key'],
				'response' => $response,
				'remoteip' => $this->request->getIPAddress()
			];

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($check));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			$result = curl_exec($ch);

			curl_close($ch);

			$status = json_decode($result, TRUE);

			if(!empty($status['success']))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks to make sure dependency PHP extensions are installed
	 *
	 * @return bool
	 */
	private function installation_check(): bool
	{
		$installed_extensions = implode(', ', get_loaded_extensions());
		$required_extensions = ['bcmath', 'intl', 'gd', 'openssl', 'mbstring', 'curl'];
		$pattern = '/';

		foreach($required_extensions as $extension)
		{
			$pattern .= '(?=.*\b' . preg_quote($extension, '/') . '\b)';
		}

		$pattern .= '/i';
		$is_installed = preg_match($pattern, $installed_extensions);

		if(!$is_installed)
		{
			log_message('error', '[ERROR] Check your php.ini.');
			log_message('error',"PHP installed extensions: $installed_extensions");
			log_message('error','PHP required extensions: ' . implode(', ', $required_extensions));
		}

		return $is_installed;
	}
}
