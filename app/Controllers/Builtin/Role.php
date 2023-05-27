<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers\Builtin;
use App\Models\Builtin\RoleModel;

class Role extends \App\Controllers\BaseController
{
	protected $model;
	private $formValidation;
	
	public function __construct() {
		
		parent::__construct();
		// $this->mustLoggedIn();
		
		$this->model = new RoleModel;	
		$this->data['site_title'] = 'Halaman Role';
		
		helper(['cookie', 'form']);
	}
	
	public function index()
	{
		$this->cekHakAkses('read_data');
		$this->setData();
		$data = $this->data;
		if ($this->request->getPost('delete')) 
		{
			$this->cekHakAkses('delete_data', 'all');
			$result = $this->model->deleteData();
			if ($result) {
				$data['msg'] = ['status' => 'ok', 'message' => 'Data berhasil dihapus'];
			} else {
				$data['msg'] = ['status' => 'warning', 'message' => 'Tidak ada data yang dihapus'];
			}
		}
		
		$data['module'] = $this->model->getAllModules();
		$data['result'] = $this->model->getAllRole();
		
		$this->view('builtin/role-result.php', $data);
	}
	
	public function add() 
	{
		$this->cekHakAkses('create_data');
		
		$this->setData();
		$data = $this->data;
		
		$breadcrumb['Add'] = '';
		$data['title'] = 'Tambah ' . $this->currentModule['judul_module'];
		$data['msg'] = [];
		
		$error = false;
		if ($this->request->getPost('submit'))
		{
			$save_msg = $this->saveData();
			$data = array_merge( $data, $save_msg);
		}
		
		$this->view('builtin/role-form.php', $data);
	}
	
	public function edit()
	{
		$this->cekHakAkses('update_data', 'all');
		
		if (!$this->request->getGet('id')) {
			$this->printError(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
		}
		
		$this->setData();
		$data = $this->data;
		$data['title'] = 'Edit ' . $this->currentModule['judul_module'];
		$breadcrumb['Edit'] = '';
	
		// Submit
		$data['msg'] = [];
		if ($this->request->getPost('submit')) 
		{
			$save = $this->saveData();
			$data = array_merge($data, $save);
		}

		$this->view('builtin/role-form.php', $data);
	}
	
	public function setData() {
		$this->data['module_role'] = $this->model->listModuleRole();
		$this->data['module_status'] = $this->model->getModuleStatus();
		$this->data['role'] = $this->model->getRole();
	}
	
	private function saveData() 
	{
		$form_errors = $this->validateForm();
	
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
	
	private function validateForm() {

		$validation =  \Config\Services::validation();
		if ($this->request->getPost('id_role') == '') {
			$validation->setRule('nama_role', 'Nama Role', 'trim|required');
		}
		$validation->setRule('judul_role', 'Judul Role', 'trim|required');
		$validation->setRule('keterangan', 'keterangan', 'trim|required');
		$validation->withRequest($this->request)->run();
		$form_errors = $validation->getErrors();
		
		if (!$this->auth->validateFormToken('form_role')) {
			$form_errors['token'] = 'Token tidak ditemukan, submit ulang form dengan mengklik tombol submit';
		}
		
		return $form_errors;
	}
	
}
