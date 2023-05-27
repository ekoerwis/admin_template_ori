<?php 
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Auth;
use \Config\App;
use App\Models\BaseModel;

class Base extends Controller
{
	protected $data;
	protected $config;
	protected $session;
	protected $request;
	protected $isLoggedIn;
	protected $auth;
	protected $user;
	protected $uriPath;
	
	public function __construct() 
	{ 
		date_default_timezone_set('Asia/Jakarta');
		$this->session = \Config\Services::session();
		$this->request = \Config\Services::request();
		$this->config = new App;
		$this->auth = new Auth;
		$model = new BaseModel;
		$model->checkLogin();

		$this->isLoggedIn = $this->session->get('logged_in');
		
		$path = $this->request->uri->getPath();
		$this->uriPath = explode('/', $path);
		$this->data['uri_path'] = $this->uriPath;
		
		$this->user = $this->session->get('user');
		$this->data['scripts'] = array($this->config->baseURL . '/public/assets/vendors/jquery/jquery.min.js'
										, $this->config->baseURL . '/public/assets/vendors/flatpickr/flatpickr.js'
										, $this->config->baseURL . '/public/themes/modern/assets/js/site.js?r='.time()
										, $this->config->baseURL . '/public/assets/vendors/zenscroll/zenscroll-min.js'
										, $this->config->baseURL . '/public/assets/vendors/bootstrap/js/bootstrap.js'
								);
		$this->data['styles'] = array(
									$this->config->baseURL . '/public/assets/vendors/bootstrap/css/bootstrap.css'
									, $this->config->baseURL . '/public/themes/modern/assets/css/site.css?r='.time()
								);
		$this->data['config'] = $this->config;
		$this->data['title'] = 'TITLE';
		$this->data['request'] = $this->request;
		$this->data['isloggedin'] = $this->isLoggedIn;
		$this->data['session'] = $this->session;
		$this->data['site_title'] = 'Premium member';
		$this->data['site_desc'] = 'Premium Jagowebdev - Resouce premium untuk belajar web development';
		
		if ($this->user)
			$this->data['user'] = $model->getUserById($this->session->get('user')['id_user']);
		else
			$this->data['user'] = [];
		
		$this->data['auth'] = $this->auth;
		
	}
	
	protected function addStyle($file) {
		$this->data['styles'][] = $file;
	}
	
	protected function addJs($file) {
		$this->data['scripts'][] = $file;
	}
	
	protected function view($file, $data = false) 
	{
		echo view('themes/modern/header.php', $data);
		echo view('themes/modern/' . $file, $data);
		echo view('themes/modern/footer.php');
	}
	
	protected function loginRequired() {
		if (!$this->isLoggedIn) {
			redirect($this->config->baseURL . 'login/');
		}
	}
	
	protected function redirectOnLoggedIn() {
		if ($this->isLoggedIn) {
			redirect($this->router->default_controller);
		}
	}
	
	protected function mustNotLoggedIn() 
	{
		if ($this->isLoggedIn) {		
			
			if ($this->uriPath[0] != 'admin') {
				header('Location: ' . $this->config->baseURL);
				exit();
			}
		}
	}
	
	protected function mustLoggedIn() {
		if (!$this->isLoggedIn) {
			
			header('Location: ' . $this->config->baseURL . '/login');
			exit();
		}
	}
}