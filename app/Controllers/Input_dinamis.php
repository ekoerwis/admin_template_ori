<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers;
use App\Models\InputDinamisModel;

class Input_dinamis extends \App\Controllers\BaseController
{
	protected $model;
	private $formValidation;
	
	public function __construct() {
		
		parent::__construct();
		// $this->mustLoggedIn();
		
		$this->model = new InputDinamisModel;	
		$this->data['site_title'] = 'Input Dinamis';
		
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/penghadap.js');
	}
	
	public function index()
	{
		$this->cekHakAkses('read_data');
		
		$data = $this->data;
		if (!empty($_POST['delete'])) 
		{
			$result = $this->model->deleteData();
			// $result = true;
			if ($result) {
				$data['msg'] = ['status' => 'ok', 'message' => 'Data penghadap berhasil dihapus'];
			} else {
				$data['msg'] = ['status' => 'error', 'message' => 'Data penghadap gagal dihapus'];
			}
		}
		
		$data['result'] = $this->model->getPenghadap();
		
		if (!$data['result']) {
			$data['msg']['status'] = 'error';
			$data['msg']['content'] = 'Data tidak ditemukan';
		}
		$this->view('penghadap-result.php', $data);
	}
	
	public function add() 
	{
		$this->cekHakAkses('create_data');
		
		$data = $this->data;
		$data['title'] = 'Tambah Data Penghadap';
		if (empty($_GET['id'])) 
		{
			$breadcrumb['Add'] = '';
		}
		
		// Submit
		$data['msg'] = [];
		if (isset($_POST['submit'])) 
		{
			$form_errors = $this->validateForm();
			$form_errors = false;
							
			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['msg']['content'] = $form_errors;
			} else {
				
				$result = $this->model->saveData();
			
				if ($result) {
					$data['msg']['status'] = 'ok';
					$data['msg']['content'] = 'Data berhasil disimpan';
				} else {
					$data['msg']['status'] = 'error';
					$data['msg']['content'] = 'Data gagal disimpan';
				}
				
				$data['title'] = 'Edit Data Penghadap';
			}
		}

		$this->view('penghadap-form.php', $data);
	}
	
	public function edit()
	{
		$data = $this->data;
		
		$data['msg'] = [];
		if (isset($_POST['submit'])) 
		{
			$form_errors = $this->validateForm();
			$form_errors = false;
							
			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['msg']['content'] = $form_errors;
			} else {
				
				$result = $this->model->saveData();
			
				if ($result) {
					$data['msg']['status'] = 'ok';
					$data['msg']['content'] = 'Data berhasil disimpan';
				} else {
					$data['msg']['status'] = 'error';
					$data['msg']['content'] = 'Data gagal disimpan';
				}
				
				$data['title'] = 'Edit Data Penghadap';
			}
		}
		
		if (!empty($_GET['id'])) 
		{
			if (empty($_POST['nama_penghadap'])) {
				$result = $this->model->getPenghadapById();
				if ($result) {
					foreach ($result as $arr) {
						foreach ($arr as $key => $val) {
							$_POST[$key][]	= $val;
						}
					}
				} else {
					$data['msg']['status'] = 'error';
					$data['msg']['content'] = 'Data penghadap tidak ditemukan';
				}
			}
		} else {
			$data['msg']['status'] = 'error';
			$data['msg']['content'] = 'Data penghadap tidak ditemukan';
		}
		
		$this->view('penghadap-form.php', $data);
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
		$validation->setRule('nama_penghadap[]', 'Nama Penghadap', 'trim|required');
		$validation->withRequest($this->request)->run();
		$form_errors = $validation->getErrors();
		
		return $form_errors;
	}
	
}