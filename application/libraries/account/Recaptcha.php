<?php
/**
 * Recaptcha library
 *
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Recaptcha library
 * @package A3M
 * @subpackage Libraries
 */
class Recaptcha
{
	/**
	 * The CodeIgniter Object
	 * @var object
	 */
	var $CI;

	/**
	 * Constructor
	 */
	function __construct()
	{
		// Obtain a reference to the ci super object
		$this->CI =& get_instance();

		// Load reCAPTCHA config
		$this->CI->config->load('umf/recaptcha');
	}

	// --------------------------------------------------------------------

	/**
	 * Verify recaptcha submission if valid captcha pass is not found
	 *
	 * @access private
	 * @return mixed
	 */
	function check()
	{
		$response = FALSE;

		if ($this->CI->input->post('g-recaptcha-response'))
		{
			$response = TRUE;
		}

		return $response;
	}

	// --------------------------------------------------------------------

	/**
	 * Load reCAPTCHA
	 *
	 * @access private
	 * @param string $error @decaprated
	 * @param bool   $ssl @decaprated
	 * @return string
	 */
	function load()
	{
		$recaptcha_site_key = $this->CI->config->item('recaptcha_site_key');
		$captcha = '<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<div class="g-recaptcha" data-sitekey="'.$recaptcha_site_key.'"></div>';

		return $captcha;
	}
}
/* End of file Recaptcha.php */
/* Location: ./application/account/libraries/Recaptcha.php */
