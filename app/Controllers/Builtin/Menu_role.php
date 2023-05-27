<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers\Builtin;
use App\Models\Builtin\MenuRoleModel;

class Menu_role extends \App\Controllers\BaseController
{
	protected $model;
	private $formValidation;
	
	public function __construct() {
		
		parent::__construct();
		$this->addJs ($this->config->baseURL . 'public/themes/modern/builtin/js/menu-role.js');
		$this->addStyle($this->config->baseURL . 'public/vendors/wdi/wdi-loader.css');

		$this->model = new MenuRoleModel;	
		$this->data['data_menu'] = $this->model->getAllMenu();
		
		$roles = $this->model->getAllRole();
		foreach($roles as $row) {
			$this->data['role'][$row['id_role']] = $row;
		}

		$this->data['menu_role'] = [];
		$menu_role = $this->model->getAllMenuRole();
		foreach($menu_role as $row) {
			$this->data['menu_role'][$row['id_menu']][] = $row['id_role'];
		}
	}
	
	public function index()
	{
		$this->cekHakAkses('read_data', 'all');
		$data = $this->data;
		$this->view('builtin/menu-role-data.php', $data);
	}
	
	public function delete() {
		if (isset($_POST['id_menu'])) 
		{
			$query = $this->model->deleteData();
			if ($query) {
				$message = ['status' => 'ok', 'message' => 'Data berhasil dihapus'];
			} else {
				$message = ['status' => 'error', 'message' => 'Data gagal dihapus'];
			}
			echo json_encode($message);
			exit;
		}
	}
	
	public function checkbox(){
		// helper('html.php');
		$prefix_id = 'role_';
	
		
		$menu_role =$this->model->getMenuRoleById($_GET['id']);
		$checked = [];
		foreach ($menu_role as $row) {
			$checked[] = $prefix_id . $row['id_role'];
		}
	
		$data = $this->data;
		$data['prefix_id'] = $prefix_id;
		$data['checked'] = $checked;
		echo view('themes/modern/builtin/menu-role-form-edit.php', $data);
		exit;
	}
	
	public function edit()
	{
		$this->cekHakAkses('update_data', 'all');
		
		// Submit data
		if (isset($_POST['id_menu'])) 
		{
			$result = $this->model->saveData();
			
			if ($result['status'] == 'ok') {
				$message = ['status' => 'ok', 'message' => 'Data berhasil disimpan', 'data_parent' => json_encode($result['insert_parent'])];
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
		
		if (!$this->auth->validateFormToken('form_edit')) {
			$form_errors['token'] = 'Token tidak ditemukan, submit ulang form dengan mengklik tombol submit';
		}
		
		return $form_errors; */
	}
}