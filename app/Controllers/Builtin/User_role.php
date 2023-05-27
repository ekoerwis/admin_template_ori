<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers\Builtin;
use App\Models\Builtin\UserRoleModel;

class User_role extends \App\Controllers\BaseController
{
	protected $model;
	private $formValidation;
	
	public function __construct() {
		
		parent::__construct();
		$this->addJs ($this->config->baseURL . 'public/themes/modern/builtin/js/user-role.js');
		$this->addStyle ($this->config->baseURL . 'public/vendors/wdi/wdi-loader.css');
		
		$this->model = new UserRoleModel;	
		$this->data['site_title'] = 'Halaman Role';
		
		$roles= $this->model->getAllRole();
		foreach($roles as $row) {
			$this->data['role'][$row['id_role']] = $row;
		}

		$this->data['user_role'] = [];
		$user_role = $this->model->getUserRole();
	
		foreach($user_role as $row) {
			$this->data['user_role'][$row['id_user']][] = $row['id_role'];
		}
	}
	
	public function index()
	{
		$this->cekHakAkses('read_data');
		
		$data = $this->data;
		if (!empty($_POST['delete'])) {
			$result = $this->model->deleteData();
			
			if ($result) {
				$data['msg'] = ['status' => 'ok', 'message' => 'Data user-role berhasil dihapus'];
			} else {
				$data['msg'] = ['status' => 'error', 'message' => 'Data user-role gagal dihapus'];
			}
		}
		
		// Get user
		$data['users'] = $this->model->getAllUser();
		$this->view('builtin/user-role-data.php', $data);
	}
	
	public function checkbox() {
		
		$user_role = $this->model->getUserRoleByID($_GET['id']);
		$this->data['user_role'] = $user_role;
		
		echo view('themes/modern/builtin/user-role-form-edit.php', $this->data);
	}
	
	public function delete() {
		if (isset($_POST['pair_id'])) 
		{
			$result = $this->model->deleteData();
			if ($result) {
				$message = ['status' => 'ok', 'message' => 'Data berhasil dihapus'];
			} else {
				$message = ['status' => 'error', 'message' => 'Data gagal dihapus'];
			}
			echo json_encode($message);
			exit;
		}
	}
	
	public function edit() {
		if (isset($_POST['pair_id'])) 
		{	
			$result = $this->model->saveData();
			
			if ($result) {
				$message = ['status' => 'ok', 'message' => 'Data berhasil disimpan'];
			} else {
				$message = ['status' => 'error', 'message' => 'Data gagal disimpan'];
			}
		
			echo json_encode($message);
			exit;
		}
	}
	
	private function validateForm() {
		
		/* $validation =  \Config\Services::validation();
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
		
		return $form_errors; */
	}
	
}