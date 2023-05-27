<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers\Builtin;
use App\Models\Builtin\ModuleModel;

class Module extends \App\Controllers\BaseController
{
	protected $model;
	private $formValidation;
	
	public function __construct() {
		
		parent::__construct();
		// $this->mustLoggedIn();
		
		$this->model = new ModuleModel;	
		$this->data['site_title'] = 'Halaman Module';
		
		$this->addStyle ( $this->config->baseURL . 'public/vendors/bulma-switch/bulma-switch.min.css?r=' . time());
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/builtin/js/module.js');
		
		helper(['cookie', 'form']);
	}
	
	public function index()
	{
		$this->cekHakAkses('read_data');
		
		$data = $this->data;
		$data['module'] = $this->model->getAllModules();
		$roles = $this->model->getAllRoles();
		foreach ($roles as $val) {
			$data['role'][$val['id_role']] = $val;
		}

		// Delete
		if (!empty($_POST['delete'])) {
			$result = $this->model->deleteData();
			// $result = false;
			if ($result) {
				$data['msg'] = ['status' => 'ok', 'message' => 'Data role berhasil dihapus'];
			} else {
				$data['msg'] = ['status' => 'error', 'message' => 'Data role gagal dihapus'];
			}
		}
		
		// Module Aktif/Nonaktif/Login
		if (!empty($_POST['change_module_attr'])) 
		{
			$update_status = $this->model->updateStatus();
					
			if (!empty($_POST['ajax'])) {
				if ($update_status) {
					echo 'ok';
				} else {
					echo 'error';
				}
				die();
			}
		}
		
		
		$data['result'] = $this->model->getModules();
		
		// Cek file module
		$files = \list_files('app/Controllers');
		$files = array_map('strtolower', $files);
		
		$data['file_module'] = $files;
		
		$this->view('builtin/module-data.php', $data);
	}
	
	public function add() 
	{
		$this->cekHakAkses('create_data');
		
		$this->data['module_status'] = $this->model->getAllModuleStatus();
		$data = $this->data;
		
		$breadcrumb['Add'] = '';
		$data['title'] = 'Tambah ' . $this->currentModule['judul_module'];
		$data['msg'] = [];
		
		if ($this->request->getPost('submit'))
		{
			$save_msg = $this->saveData();
			$data = array_merge( $data, $save_msg);
		}
		
		$this->view('builtin/module-form.php', $data);
	}
	
	public function edit()
	{
		$this->cekHakAkses('update_data', 'all');
		
		$data = $this->data;
		
		$data['title'] = 'Edit Data Module';
		
		$module = $this->model->getModule($_GET['id']);
		$data = array_merge($data, $module);
		
		$data['module_status'] = $this->model->getAllModuleStatus();
		
		// Submit data
		$data['msg'] = [];
		if ($this->request->getPost('submit'))
		{
			$save_msg = $this->saveData();
			$data = array_merge( $data, $save_msg);
		}

		$breadcrumb['Edit'] = '';
		$this->view('builtin/module-form.php', $data);
	}
	
	private function saveData() 
	{
		$unique = false;
		if ($_POST['nama_module'] != $_POST['nama_module_old']) {
			$unique = true;
		}
		
		$form_errors = $this->validateForm($unique);
	
		if ($form_errors) {
			$data['msg']['status'] = 'error';
			$data['form_errors'] = $form_errors;
			$data['msg']['message'] = $form_errors;
		} else {
			$save = $this->model->saveData();
			if ($save['status'] == 'ok') {
				$data['msg']['status'] = 'ok';
				$data['msg']['message'] = 'Data berhasil disimpan';
			} else {
				$data['msg']['status'] = 'error';
				$data['msg']['message'] = $save['message'];
			}
		}
		
		return $data;
	}
	
	private function validateForm($check_unique = false) {
	
		$validation =  \Config\Services::validation();
		$unique = '';
		if ($check_unique) {
			$unique = '|is_unique[module.nama_module]';
		}
		$validation->setRule('nama_module', 'Nama Module', 'trim|required' . $unique);
		$validation->setRule('judul_module', 'Judul Module', 'trim|required');
		$validation->setRule('deskripsi', 'Deskripsi Module', 'trim|required');
		$validation->setRule('id_module_status', 'ID Module Status', 'trim|required');
		$validation->withRequest($this->request)->run();
		$form_errors = $validation->getErrors();
		
		return $form_errors;
	}
	
}
