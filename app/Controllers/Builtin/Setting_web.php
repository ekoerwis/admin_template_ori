<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers\Builtin;
use App\Models\Builtin\SettingWebModel;

class Setting_web extends \App\Controllers\BaseController
{
	public function __construct() {
		
		parent::__construct();
		// $this->mustLoggedIn();
		
		$this->model = new SettingWebModel;	
		$this->data['site_title'] = 'Halaman Setting Web';
		
		$this->addJs ( $this->config->baseURL . 'public/vendors/spectrum/spectrum.min.js?r=' . time());
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/setting-logo.js?r=' . time());
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/setting-web.js?r=' . time());
		$this->addStyle ( $this->config->baseURL . 'public/vendors/spectrum/spectrum.css');
		$this->addStyle ( $this->config->baseURL . 'public/themes/modern/builtin/css/login-header.css');
		
		
		helper(['cookie', 'form']);
	}
	
	public function index() 
	{
		$data = $this->data;
		if (!empty($_POST['submit'])) 
		{
			$form_errors = $this->validateForm();
			
			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['msg']['content'] = $form_errors;
			} else {
				
				if ($this->moduleRole[$_SESSION['user']['id_role']]['update_data'] != 'all')
				{
					$data['status'] = 'error';
					$data['message'] = 'Role anda tidak diperbolehkan melakukan perubahan';
				} else {
					$result = $this->model->saveData();
					$data['status'] = $result['status'];
					$data['message'] = $result['message'];
				}
			}
		}
		
		$query = $this->model->getSettingWeb();
		foreach($query as $val) {
			$data[$val['param']] = $val['value'];
		}

		$data['title'] = 'Edit ' . $this->currentModule['judul_module'];
		
		$this->view('builtin/settingweb-form.php', $data);
	}

	private function validateForm() 
	{
		$validation =  \Config\Services::validation();		
		$validation->setRule('footer_app', 'Footer Aplikasi', 'trim|required');
		$validation->setRule('background_logo', 'Background Logo', 'trim|required');
		$validation->setRule('judul_web', 'Judul Website', 'trim|required');
		$validation->setRule('deskripsi_web', 'Deskripsi Web', 'trim|required');
		
		$validation->withRequest($this->request)
					->run();
		$form_errors =  $validation->getErrors();
						
		// $form_errors = [];
		if ($_FILES['logo_login']['name']) {
			
			$file_type = $_FILES['logo_login']['type'];
			$allowed = ['image/png', 'image/jpeg', 'image/jpg'];
			
			if (!in_array($file_type, $allowed)) {
				$form_errors['logo_login'] = 'Tipe file harus ' . join(', ', $allowed);
			}
			
			if ($_FILES['logo_login']['size'] > 300 * 1024) {
				$form_errors['logo_login'] = 'Ukuran file maksimal 300Kb';
			}
			
			$info = getimagesize($_FILES['logo_login']['tmp_name']);
			if ($info[0] < 20 || $info[1] < 20) { //0 Width, 1 Height
				$form_errors['logo_login'] = 'Dimensi logo login minimal: 20px x 20px, dimensi anda ' . $info[0] . 'px x ' . $info[1] . 'px';
			}
		}
		
		if ($_FILES['logo_app']['name']) {
			
			$file_type = $_FILES['logo_app']['type'];
			$allowed = ['image/png', 'image/jpeg', 'image/jpg'];
			
			if (!in_array($file_type, $allowed)) {
				$form_errors['logo_app'] = 'Tipe file harus ' . join(', ', $allowed);
			}
			
			if ($_FILES['logo_app']['size'] > 300 * 1024) {
				$form_errors['logo_app'] = 'Ukuran file maksimal 300Kb';
			}
			
			$info = getimagesize($_FILES['logo_app']['tmp_name']);
			if ($info[0] < 20 || $info[1] < 20) { //0 Width, 1 Height
				$form_errors['logo_app'] = 'Dimensi logo aplikasi minimal: 20px x 20px, dimensi anda ' . $info[0] . 'px x ' . $info[1] . 'px';
			}
		}
		
		if ($_FILES['favicon']['name']) {
			
			$file_type = $_FILES['favicon']['type'];
			$allowed = ['image/png'];
			
			if (!in_array($file_type, $allowed)) {
				$form_errors['favicon'] = 'Tipe file harus ' . join(', ', $allowed) . ' tipe file Anda: ' . $file_type;
			}
			
			if ($_FILES['favicon']['size'] > 300 * 1024) {
				$form_errors['favicon'] = 'Ukuran file maksimal 300Kb';
			}
		}
		
		return $form_errors;
	}	
}