<?php 
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers;

use App\Controllers\Base;
use App\Libraries\Auth;
use \Config\App;
use App\Models\BaseModel;

class BaseController extends Base
{
	protected $data;
	protected $config;
	protected $session;
	protected $request;
	protected $isLoggedIn;
	protected $auth;
	protected $user;
	protected $model;
	
	public $currentModule;
	private $controllerName;
	private $methodName;
	protected $actionUser;
	protected $queryString;
	protected $moduleURL;
	protected $moduleRole;
	protected $router;
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->model = new BaseModel;
		
		$this->data['scripts'] = [];
		$this->data['styles'] = [];
		
		$this->session = session();
		
		helper('util');
		
		// Attribute
		
		$router = service('router');
		$controller  = $router->controllerName();
		$exp  = explode('\\', $controller);
		
		if (strpos($controller, 'App\Controllers\Builtin') !== false) {
			
			$module_name = 'Builtin\\' . strtolower($exp[count($exp) - 1]);
			$this->moduleURL = $this->config->baseURL . 'builtin/' . strtolower($exp[count($exp) - 1]);

		} else {
			$module_name = strtolower($exp[count($exp) - 1]);
			$this->moduleURL = $this->config->baseURL . $module_name;
		}
	
		$this->methodName = $router->methodName();
		
		$this->isLoggedIn = $this->session->get('logged_in');

		if ($module_name !== 'login') {
			$this->mustLoggedIn();
		}
		
		$this->user = $this->session->get('user');	
		$this->data['config'] = $this->config;
		$this->data['module_url'] = $this->moduleURL;
		$this->data['title'] = 'TITLE';
		$this->data['request'] = $this->request;
		$this->data['isloggedin'] = $this->isLoggedIn;
		$this->data['session'] = $this->session;
		$this->data['site_title'] = 'Premium member';
		$this->data['site_desc'] = 'Premium Jagowebdev - Resouce premium untuk belajar web development';
			
		if ($this->user)
			$this->data['user'] = $this->model->getSettingWeb();
		else
			$this->data['user'] = [];
		
		$this->data['auth'] = $this->auth;
		$this->data['settingWeb'] = $this->model->getSettingWeb();
		
		if ($this->session->get('user')) {
			$user_setting = $this->model->getUserSetting();
			if ($user_setting)
				$app_layout = json_decode($user_setting->param, true);
			
		} else {
			$query = $this->model->getAppLayoutSetting();
			foreach ($query as $val) {
				$app_layout[$val['param']] = $val['value'];
			}
		}
		
		$this->data['app_layout'] = $app_layout;
		
		
		// Module Detail
		$default_module = 'welcome';
		if ($this->isLoggedIn) 
		{
			$module_role = $this->model->getDefaultUserModule();
			$default_module = $module_role->nama_module;
			
			$nama_module = $this->controllerName ?: $default_module;
			
			$exp  = explode('\\', $controller);			
			foreach ($exp as $key => $val) {
				
				if (!$val || strtolower($val) == 'app' || strtolower($val) == 'controllers')
					unset($exp[$key]);
				
			}
			
			// Dash tidak valid untuk nama class, sehingga jika ada dash di url maka otomatis akan diubah menjadi underscore, hal tersebut berpengaruh ke nama controller
			
			$nama_module = str_replace('_', '-', strtolower(join('/', $exp))); 
			
			// List action assigned to role
			$this->data['action_user'] = $this->actionUser;
			
			$module = $this->model->getModule($nama_module);
			$this->currentModule = $module;
			
			if (!$module) { 
				$this->setCurrentModule('error');
			}
			
			$this->data['current_module'] = $this->currentModule;
			
			if (!$module) {
				$this->data['status'] = 'error';
				$this->data['title'] = 'ERROR';
				$this->data['content'] = 'Module ' . $nama_module . ' tidak ditemukan di database';
				$this->viewError($this->data);
				exit();
			}

			$this->data['menu'] = $this->model->getMenu('all',false, $this->currentModule['nama_module']);
			
			// Breadcrumb
			$this->data['breadcrumb'] = ['Home' => $this->config->baseURL, $this->currentModule['judul_module'] => $this->moduleURL];
			$this->getModuleRole();
			$this->data['module_role'] = $module_role;
			$this->getListAction();
			
			
			
			if ($module_name == 'login') {
				$this->redirectOnLoggedIn();
			}
		}
		
		$this->model->checkLogin();

	}
	
	private function getModuleRole(){
		$query = $this->model->getModuleRole($this->currentModule['id_module']);
		$this->moduleRole = [];
		foreach ($query as $val) {
			$this->moduleRole[$val['id_role']] = $val;
		}
	}
	
	private function getListAction() 
	{
		
		$list_action = [];
		$id_role = $this->session->get('user')['id_role'];
		
		if ($this->isLoggedIn && $this->currentModule['nama_module'] != 'login') {
			
			if ($this->moduleRole) {
				if (key_exists($id_role, $this->moduleRole)) {
					
					$this->actionUser = $this->moduleRole[$id_role];
				}
				if ($this->currentModule['nama_module'] != 'login' ) {
					
					if (!key_exists($id_role, $this->moduleRole)) {
						$this->setCurrentModule('error');
						$this->data['title'] = 'Error';
						$this->data['msg']['status'] = 'error';
						$this->data['msg']['message'] = 'Anda tidak berhak mengakses halaman ini';
						$this->view('error.php', $this->data);
						
						exit();
					}
				}
			} else {
				$this->data['title'] = 'Error';
				$this->setCurrentModule('error');
				$this->data['msg']['status'] = 'error';
				$this->data['msg']['message'] = 'Role untuk module ini belum diatur'; 
				$this->view('error.php',$this->data);
				exit();
			}
		}
	}
	
	private function setCurrentModule($module) {
		$this->currentModule['nama_module'] = $module;
	}
	
	protected function getControllerName() {
		return $this->controllerName;
	}
	
	protected function getMethodName() {
		return $this->methodName;
	}
	
	protected function addStyle($file) {
		$this->data['styles'][] = $file;
	}
	
	protected function addJs($file) {
		$this->data['scripts'][] = $file;
	}
	
	protected function viewError($data) {
		echo view('app_error.php', $data);
	}
	
	protected function view($file, $data = false, $file_only = false) 
	{
		// echo 'themes/modern/admin/' . $file;
		echo view('themes/modern/header.php', $data);
		echo view('themes/modern/' . $file, $data);
		echo view('themes/modern/footer.php');
		exit();
	}
	
	protected function loginRequired() {
		if (!$this->isLoggedIn) {
			redirect($this->config->baseURL . 'login');
		}
	}
	
	protected function redirectOnLoggedIn() {

		if ($this->isLoggedIn) {
			header('Location: ' . $this->config->baseURL . $this->data['module_role']->nama_module);
		}
	}
	
	protected function mustNotLoggedIn() {
		if ($this->isLoggedIn) {	
			if ($this->currentModule['nama_module'] == 'login') {
				header('Location: ' . $this->config->baseURL . $this->data['module_role']->nama_module);
				exit();
			}
		}
	}
	
	protected function mustLoggedIn() {
		if (!$this->isLoggedIn) {
			header('Location: ' . $this->config->baseURL . 'login');
			exit();
		}
	}
	
	protected function cekHakAkses($action, $scope = '') {
	
		$allowed = $this->actionUser[$action];
		if ($scope) {
			if ($allowed != $scope) {
				$this->module->nama_module = 'error';
				$this->view('views/error.php', ['status' => 'error', 'message' => 'Anda tidak berhak mengakses halaman ini']);
			}
		}
		if ($allowed == 'no') {
			$this->module->nama_module = 'error';
			load_view('views/error.php', ['status' => 'error', 'message' => 'Anda tidak berhak mengakses halaman ini']);
		}
	}
	
	protected function printError($message) {
		$this->data['title'] = 'Error...';
		$this->data['msg'] = $message;
		$this->view('error.php', $this->data);
		exit();
	}
	
	/* Used for modules when edited data not found */
	protected function errorDataNotFound($addData = null) {
		$data = $this->data;
		$data['title'] = 'Error';
		$data['msg']['status'] = 'error';
		$data['msg']['content'] = 'Data tidak ditemukan';
		
		if ($addData) {
			$data = array_merge($data, $addData);
		}
		$this->view('error-data-notfound.php', $data);
		exit;
	}
}