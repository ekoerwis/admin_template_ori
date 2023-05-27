<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers;
use App\Models\ImageUploadModel;

class Image_upload extends \App\Controllers\BaseController
{
	public function __construct() {
		
		parent::__construct();
		// $this->mustLoggedIn();
		
		$this->model = new ImageUploadModel;	
		$this->data['site_title'] = 'Image Upload';
		
		$this->addJs ( $this->config->baseURL . 'public/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js' );
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/date-picker.js');
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/image-upload.js');
		$this->addStyle ( $this->config->baseURL . 'public/vendors/bootstrap-datepicker/css/bootstrap-datepicker3.css');
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
				$data['msg'] = ['status' => 'ok', 'message' => 'Data akta berhasil dihapus'];
			} else {
				$data['msg'] = ['status' => 'error', 'message' => 'Data akta gagal dihapus'];
			}
		}
		
		$data['result'] = $this->model->getMahasiswa();
		
		$this->view('image-upload-result.php', $data);
	}
	
	public function add() 
	{
		$data = $this->data;
		$data['title'] = 'Tambah Data Mahasiswa';
		$data['breadcrumb']['Add'] = '';
		
		$data['msg'] = [];
		if (isset($_POST['submit'])) 
		{
			// $form_errors = validate_form();
			$form_errors = false;
							
			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['msg']['content'] = $form_errors;
			} else {
				
				// $query = false;
				$message = $this->model->saveData();
				
				$data = array_merge($data, $message);
				$data['breadcrumb']['Edit'] = '';
				$data_mahasiswa = $this->model->getMahasiswaById($message['id_mahasiswa']);
				$data = array_merge($data, $data_mahasiswa);
			}
		}
	
		$this->view('image-upload-form.php', $data);
	}
	
	public function edit()
	{
		$this->data['title'] = 'Edit ' . $this->currentModule['judul_module'];;
		$data = $this->data;
		
		if (empty($_GET['id'])) {
			$this->errorDataNotFound();
		}
				
		// Submit
		$data['msg'] = [];
		if (isset($_POST['submit'])) 
		{
			// $form_errors = validate_form();
			$form_errors = false;
							
			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['msg']['content'] = $form_errors;
			} else {
				
				// $query = false;
				$message = $this->model->saveData();
				
				$data = array_merge($data, $message);
			}
		}
		
		$data['breadcrumb']['Edit'] = '';
		
		$data_mahasiswa = $this->model->getMahasiswaById($_GET['id']);
		if (empty($data_mahasiswa)) {
			$this->errorDataNotFound();
		}
		$data = array_merge($data, $data_mahasiswa);
		
		$this->view('image-upload-form.php', $data);
	}
	
	private function setDataOptions() 
	{
		$result = $this->model->getPenghadap();
		$penghadap = [];
		foreach($result as $val) {
			$penghadap[$val['id_penghadap']] = $val['nama_penghadap'];
		}
			
		$data['penghadap'] = $penghadap;
		
		$result = $this->model->getPenanggungJawab();
		$penanggung_jawab = [];
		foreach($result as $val) {
			$penanggung_jawab[$val['id_penanggung_jawab']] = $val['nama_penanggung_jawab'];
		}
			
		$data['penanggungjawab'] = $penanggung_jawab;
		
		return $data;
	}
	
	private function setData($id) {
		
		$data = [];
		$result = $this->model->getAkta($id);
		foreach ($result as $arr) {
			foreach ($arr as $key => $val) {
				$data[$key]	= $val;
			}
		}
		
		$result = $this->model->getAktaPenanggungJawab($id);
		foreach ($result as $arr) {
			foreach ($arr as $key => $val) {
				$data['id_penanggung_jawab'][]	= $val;
			}
		}
		
		$result = $this->model->getAktaPenghadap($id);
		foreach ($result as $arr) {
			foreach ($arr as $key => $val) {
				$data['id_penghadap'][]	= $val;
			}
		}

		$result = $this->model->getAktaFile($id);
		foreach ($result as $key => $arr) {
			foreach ($arr as $key_data => $val_data) {
				$data[$key_data][$key]	= $val_data;
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
