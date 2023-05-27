<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers;
use App\Models\OptionsDinamisModel;

class Options_dinamis extends \App\Controllers\BaseController
{
	protected $model;
	private $formValidation;
	
	public function __construct() {
		
		parent::__construct();
		// $this->mustLoggedIn();
		
		$this->model = new OptionsDinamisModel;	
		$this->data['site_title'] = 'Options Dinamis';
		
		$this->addJs ( $this->config->baseURL . 'public/vendors/jquery.select2/js/select2.full.min.js' );
		$this->addStyle ( $this->config->baseURL . 'public/vendors/jquery.select2/css/select2.min.css' );
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/options-dinamis.js' );
		$this->addJs ( $this->config->baseURL . 'public/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js' );
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/date-picker.js' );
		$this->addStyle ( $this->config->baseURL . 'public/vendors/bootstrap-datepicker/css/bootstrap-datepicker3.css' );
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
		
		$data_akta = $this->model->getData();
		$data['result'] = $data_akta['data_akta'];
		$data['akta_file'] = $data_akta['akta_file'];
		
		if (!$data['result']) {
			$data['msg']['status'] = 'error';
			$data['msg']['content'] = 'Data tidak ditemukan';
		}
		
		$this->view('options-dinamis-result.php', $data);
	}
	
	public function add() 
	{
		$this->cekHakAkses('create_data');
		
		$data = $this->data;
		$data['title'] = 'Tambah Data Akta';
		
		// Submit
		$data['msg'] = [];
		$id_akta = false;
		if (isset($_POST['submit'])) 
		{
			// $form_errors = validate_form();
			$form_errors = false;
							
			if ($form_errors) {
				$data['msg']['status'] = 'error';
				$data['msg']['content'] = $form_errors;
			} else {
				
				// $query = false;
				$id_akta = $this->model->saveData();
			
				if ($id_akta) {
					$data['msg']['status'] = 'ok';
					$data['msg']['content'] = 'Data berhasil disimpan';
				} else {
					$data['msg']['status'] = 'error';
					$data['msg']['content'] = 'Data gagal disimpan';
				}
				
				$data['title'] = 'Edit Data Repotarium';
			}
		}
		
		if ($id_akta) 
		{
			$breadcrumb['Edit'] = '';
			
			$data['title'] = 'Edit Data Repotarium';
			$data['id_akta'] = $id_akta;
			
			$data_akta = $this->setData($id_akta);
			if (empty($data_akta['nama_akta'])) {
				$this->errorDataNotFound();
			}
			$data = array_merge ($data, $data_akta);
			
		}
		
		$data_options = $this->setDataOptions();
		$data = array_merge ($data, $data_options);
	
		$this->view('options-dinamis-form.php', $data);
	}
	
	public function edit()
	{
		$this->data['title'] = 'Edit Data';
		$data = $this->data;
		
		if (empty($_GET['id'])) {
			$this->errorDataNotFound();
		}
		
		$this->cekHakAkses('create_data');
		$data['title'] = 'Tambah Data Akta';
		
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
				$query = $this->model->saveData();
				
				if ($query) {
					$data['msg']['status'] = 'ok';
					$data['msg']['content'] = 'Data berhasil disimpan';
				} else {
					$data['msg']['status'] = 'error';
					$data['msg']['content'] = 'Data gagal disimpan';
				}
				
				$data['title'] = 'Edit Data Repotarium';
			}
		}
		
		if (!empty($_GET['id'])) 
		{
			$breadcrumb['Edit'] = '';
			
			$data['title'] = 'Edit Data Repotarium';
			
			$data_akta = $this->setData($_GET['id']);
			if (empty($data_akta['nama_akta'])) {
				$this->errorDataNotFound();
			}
			
			$data = array_merge ($data, $data_akta);
			$data['id_akta'] = $_GET['id'];
		}
		
		$data_options = $this->setDataOptions();
		$data = array_merge ($data, $data_options);
		
		$this->view('options-dinamis-form.php', $data);
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
