<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/


namespace App\Controllers\Builtin;
use App\Models\Builtin\SettingModel;

class Setting extends \App\Controllers\BaseController
{
	public function __construct() {
		
		parent::__construct();
		// $this->mustLoggedIn();
		
		$this->model = new SettingModel;	
		$this->data['site_title'] = 'Halaman Setting';
		
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/builtin/js/setting.js?r=' . time());
		$this->addStyle ( $this->config->baseURL . 'public/themes/modern/builtin/css/setting.css');
		
		
		helper(['cookie', 'form']);
	}
	
	public function index() 
	{
		$data = $this->data;
		if (!empty($_POST['submit'])) 
		{
			
			if ($this->moduleRole[$_SESSION['user']['id_role']]['update_data'] != 'all'
				|| $this->moduleRole[$_SESSION['user']['id_role']]['update_data'] != 'own'
			) {
				$save = $this->model->saveData($this->moduleRole);
				
				if ($save) {
					$data['status'] = 'ok';
					$data['message'] = 'Data berhasil disimpan';
				} else {
					$data['status'] = 'error';
					$data['message'] = 'Data gagal disimpan';
				}
			} else {
				$data['status'] = 'error';
				$data['message'] = 'Role anda tidak diperbolehkan melakukan perubahan';
			}
			
			if (!empty($_POST['ajax'])) {
				echo json_encode($data); die;
			}
		}
		
		$user_setting = $this->model->getUserSetting();
					
		if ($user_setting) {
			$user_param = json_decode($user_setting['param'], true);
			$data = array_merge($data, $user_param);
		} else {
			$query = $this->model->getDefaultSetting();
			
			foreach($query as $val) {
				$data[$val['param']] = $val['value'];
			}
		}
	
		$data['title'] = 'Edit ' . $this->currentModule['judul_module'];
		$this->view('builtin/setting-form.php', $data);
	}
	
}