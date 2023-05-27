<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers;
use App\Models\DataTablesAjaxModel;

class Data_tables_ajax extends \App\Controllers\BaseController
{
	public function __construct() {
		
		parent::__construct();
		// $this->mustLoggedIn();
		
		$this->model = new DataTablesAjaxModel;	
		$this->data['site_title'] = 'Image Upload';
		
		$this->addJs ( $this->config->baseURL . 'public/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js' );
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/date-picker.js');
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/image-upload.js');
		$this->addJs ( $this->config->baseURL . 'public/vendors/datatables/datatables.min.js');
		$this->addStyle ( $this->config->baseURL . 'public/vendors/datatables/datatables.min.css');
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/data-tables-ajax.js');
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
		
		$this->view('data-tables-ajax-result.php', $data);
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
	
	public function getDataDT() {
		
		$this->cekHakAkses('read_data');
		
		$num_data = $this->model->countAllData();
		$result['draw'] = $start = $this->request->getPost('draw') ?: 1;
		$result['recordsTotal'] = $num_data;
		$result['recordsFiltered'] = $num_data;
		$query = $this->model->getListData();
				
		helper('html');
		$id_user = $this->session->get('user')['id_user'];
		
		foreach ($query as $key => &$val) 
		{
			$foto = 'Anonymous.png';
			if ($val['foto']) {
				if (file_exists('public/images/foto/' . $val['foto'])) {
					$foto = $val['foto'];
				}
				$val['foto'] = '<div class="list-foto"><img src="'. $this->config->baseURL.'public/images/foto/' . $foto . '"/></div>';
			} else {
				$val['foto'] = 'Tidak Ada';
			}
			
			$val['tgl_lahir'] = $val['tempat_lahir'] . ', '. format_tanggal($val['tgl_lahir']);
			
			$val['ignore_search_action'] = btn_action([
									'edit' => ['url' => $this->config->baseURL . $this->currentModule['nama_module'] . '/edit?id='. $val['id_mahasiswa']]
								, 'delete' => ['url' => ''
												, 'id' =>  $val['id_mahasiswa']
												, 'delete-title' => 'Hapus data mahasiswa: <strong>'.$val['nama'].'</strong> ?'
											]
							]);
		}
					
		$result['data'] = $query;
		echo json_encode($result); exit();
	}
	
}
