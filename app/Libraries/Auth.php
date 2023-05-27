<?php
namespace App\Libraries;
use App\Models\AuthModel;

class Auth 
{
	private $form_token = '';
	private $session;
	private $model;
	
	public function __construct() {
		$this->session = \Config\Services::session();
	}

	public function generateToken($n) {
		// PHP 7
		if (function_exists('random_bytes')) {
			return random_bytes($n);
		
		// Fallback to PHP 5
		} else {
			require_once APPPATH . "third_party/Random-compat/lib/random.php";
			try {
				$string = random_bytes($n);
			} catch (TypeError $e) {
				// Well, it's an integer, so this IS unexpected.
				// die("An unexpected error has occurred"); 
				$string = null;
			} catch (Error $e) {
				// This is also unexpected because 32 is a reasonable integer.
				// die("An unexpected error has occurred");
				$string = null;
			} catch (Exception $e) {
				// If you get this message, the CSPRNG failed hard.
				// die("Could not generate a random string. Is our OS secure?");
				$string = null;
			}
			return $string;
		}
	}
	
	public function generateSelector($n) {
		return $this->generateToken($n);
	}
	
	public function generateSessionFormToken($session_name = null, $token_string) 
	{
		// echo $token_string; die;
		$exp = explode (':', $token_string);
		$selector = $exp[0];
		$token = $exp[1];
		
		if ($session_name) {
			$_SESSION['token'][$session_name] = [$selector => hash('sha256', hex2bin($token))];
		} else {
			$_SESSION['token'] = [$selector => hash('sha256', hex2bin($token))];
		}
		
		// $this->session->set($data);
	}
	
	public function generateFormToken($session_name = null) 
	{
		$random_bytes = $this->generateToken(33);
		$form_token = bin2hex($random_bytes);
		
		$selector  = bin2hex($this->generateSelector(9));
		// $token['selector'] = $selector;
		$token = $selector . ':' . $form_token;
		
		$this->generateSessionFormToken($session_name, $token);
		
		/* echo $_SESSION['form_token'];
		echo '<br/>';
		echo $form_token;
		echo '<br/>';
		echo @hash('sha256', hex2bin($form_token)); */
		return $token;
	}
	
	public function generateDbToken()
	{
		$random_bytes = $this->generateToken(33);
		$selector  = $this->generateSelector(9);
		$token = new \stdClass;
		$token->selector = bin2hex($selector);
		$token->external = bin2hex($random_bytes);
		$token->db = hash('sha256', $random_bytes);
		
		return $token;
	}
	
	public function validateFormToken($session_name = null, $post_name = 'form_token') {
		// print_r($this->session->get($session_name)); die;
		$request = \Config\Services::request();
		$form_token = explode (':', $request->getVar($post_name));
		
		$form_selector = $form_token[0];
		$sess_index = $session_name ? $_SESSION['token'][$session_name] : $_SESSION['token'];
		// print_r($form_selector);
		// echo '<pre>'; print_r($sess_index); die;
		
		if (!key_exists($form_selector, $sess_index))
				return false;
			
		return $this->validateToken($sess_index[$form_selector], $form_token[1]);
	}
	
	public function validateToken($known_string, $submitted_string) 
	{
		
		$hash = hash('sha256', hex2bin($submitted_string));
		 //print_r($known_string); echo '----------------';
		//print_r($hash); die;
		return hash_equals($known_string, $hash);
	}
	
	public function createFormToken($name) {
		return '<input type="hidden" name="form_token" value="' . $this->generateFormToken($name) . '"/>';
	}
}