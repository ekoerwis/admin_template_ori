<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers;
use App\Models\Builtin\LoginModel;
use \Config\App;

class Login extends \App\Controllers\BaseController
{
	protected $model = '';
	
	public function __construct() {
		parent::__construct();
		$this->model = new LoginModel;	
		$this->data['site_title'] = 'Login ke akun Anda';
		
		helper(['cookie', 'form']);
		
	}
	
	public function index()
	{
		
		$this->mustNotLoggedIn();
		$this->data['status'] = '';
		if ($this->request->getPost('password')) {
			
			$this->login();
			if ($this->session->get('logged_in')) {
				return redirect()->back();
			}
		}
		
		echo view('themes/modern/builtin/login-page', $this->data);
	}
	
	private function login()
	{
		// Check Token
		$sess_index = $this->request->getPost('login_form_header') ? 'login_form_token_header' : 'login_form_token';
		if (!$this->auth->validateFormToken($sess_index))
		{
			$this->data['status'] = 'error';
			$this->data['message'] = 'Token tidak ditemukan, silakan submit ulang form dengan mengklik tombol Submit';
			return;
		}
		
		$error = false;
		$result = $this->model->checkUser($this->request->getPost('username'));
		
		if ($result) {
			if (!password_verify($this->request->getPost('password'), $result['password'])) {
			
				$error = true;
			}
			
		} else {
			$error = true;
		}
		
		if ($error)
		{
			$this->data['status'] = 'error';
			$this->data['message'] = 'Email dan Password tidak cocok';
			return;
		}
		
		if ($this->request->getPost('remember')) 
		{
			// $expired_time = 3600*24*30; // 1 month
			$expired_time = 60*5; // 1 month
			$param = ['param' => 'remember=' . $result['id_user']
						, 'expires' => date('Y-m-d H:i:s', time() + $expired_time)
					];
					
			$token = $this->model->generateDbToken($param);
			
			//set_cookie('jwd_remember', $token->selector . ':' . $token->external, time() + $expired_time, '', '/');
			setcookie('jwd_remember', $token->selector . ':' . $token->external, time() + $expired_time, '/');
		}
		
		$this->session->set('user', $result);
		$this->session->set('logged_in', true);
		$this->model->recordLogin();
	}
	
	public function refreshLoginData() 
	{
		$email = $this->session->get('user')['email'];
		$result = $this->model->checkUser($email);
		$this->session->set('user', $result);
	}
	
	public function logout() 
	{
		$this->session->destroy();
		setcookie('jwd_remember', '', -1, '/');
		return redirect()->to($this->config->baseURL . 'login');
	}
}
