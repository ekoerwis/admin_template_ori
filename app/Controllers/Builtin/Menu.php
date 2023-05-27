<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Controllers\Builtin;
use App\Models\Builtin\MenuModel;

class Menu extends \App\Controllers\BaseController
{
	protected $model;
	
	public function __construct() {
		
		parent::__construct();
		// $this->mustLoggedIn();
		
		$this->model = new MenuModel;	
		$this->data['site_title'] = 'Halaman Menu';
		
		$this->addStyle ( $this->config->baseURL . 'public/vendors/jquery-nestable/jquery.nestable.min.css?r='.time());
		$this->addStyle ( $this->config->baseURL . 'public/vendors/wdi/wdi-modal.css?r=' . time());
		$this->addStyle ( $this->config->baseURL . 'public/vendors/wdi/wdi-fapicker.css?r=' . time());
		$this->addStyle ( $this->config->baseURL . 'public/vendors/wdi/wdi-loader.css?r=' . time());
		$this->addStyle ( $this->config->baseURL . 'public/vendors/bulma-switch/bulma-switch.min.css?r=' . time());
		
		$this->addJs ( $this->config->baseURL . 'public/vendors/wdi/wdi-fapicker.js?r=' . time());
		$this->addJs ($this->config->baseURL . 'public/themes/modern/js/admin-menu.js');
		$this->addJs ( $this->config->baseURL . 'public/vendors/jquery-nestable/jquery.nestable.js?r=' . time());
		// $js[] = $config['base_url'] . 'public/vendors/jquery-nestable/jquery.nestable-edit.js?r=' . time();
		$this->addJs ( $this->config->baseURL . 'public/vendors/js-yaml/js-yaml.min.js?r=' . time());
		$this->addJs ( $this->config->baseURL . 'public/vendors/jquery-nestable/jquery.wdi-menueditor.js?r=' . time());

		helper(['cookie', 'form']);
	}
	
	public function index()
	{
		$this->cekHakAkses('read_data');
		
		$data = $this->data;
		
		$menu_updated = [];
		$msg = [];
		if (!empty($_POST['submit'])) 
		{
			$menu_updated = $this->model->updateData();
			
			if ($menu_updated) {
				$msg['status'] = 'ok';
				$msg['content'] = 'Menu berhasil diupdate';
			} else {
				$msg['status'] = 'warning';
				$msg['content'] = 'Tidak ada menu yang diupdate';
			}
		}
		// End Submit

		// helper('builtin/admin_menu');
		$result = $this->model->getMenu('all',true, $this->currentModule['nama_module']);
		$list_menu = menu_list($result);
	
		$data['list_menu'] = $this->buildMenuList($list_menu); 
		$data['list_module'] = 	$this->model->getListModules();
		$data['role'] = 	$this->model->getAllRole();
		$data['msg'] = $msg;
		
		$this->view('builtin/menu-form.php', $data);
	}
	
	public function edit()
	{
		$data['msg'] = [];
		if (isset($_POST['nama_menu'])) 
		{
			$error = $this->checkForm();
			if ($error) {
				$data['msg']['status'] = 'error';
				$data['msg']['message'] = '<ul class="list-error"><li>' . join($error, '</li><li>') . '</li></ul>';
			} else {
				
				
				if (empty($_POST['id'])) {
					$query = $this->model->saveData();
					$message = 'Menu berhasil ditambahkan';
					$data['msg']['id_menu'] = $query;
				} else {
					$query = $this->model->saveData($_POST['id']);
					$message = 'Menu berhasil diupdate';
				}
				
				$query = true;
				if ($query) {
					$data['msg']['status'] = 'ok';
					$data['msg']['message'] = $message;
					// $data['msg']['message'] = 'Menu berhasil diupdate';
				} else {
					$data['msg']['status'] = 'error';
					$data['msg']['message'] = 'Data gagal disimpan';
					$data['msg']['error_query'] = true;
				}	
			}
			echo json_encode($data['msg']);
			exit();
		}
		$this->view('builtin/module-form.php', $data);
	}
	
	public function delete() {
		$result = $this->model->deleteData('menu', ['id_menu' => $_POST['id']]);
		
		if ($result) {
			$message = ['status' => 'ok', 'message' => 'Data menu berhasil dihapus'];
			echo json_encode($message);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Data menu gagal dihapus']);
		}
	}
	
	public function menuDetail() {
		
		$result = $this->model->getMenuDetail();
		if (!empty($_GET['ajax'])) {
			echo json_encode($result);
		}
	}
	
	private function checkForm() 
	{
		$error = [];
		if (trim($_POST['nama_menu']) == '') {
			$error[] = 'Nama menu harus diisi';
		}
		
		if (trim($_POST['url']) == '') {
			$error[] = 'Url harus diisi';
		}
		
		return $error;
	}
	
	
	function buildMenuList($arr)
	{
		$menu = "\n" . '<ol class="dd-list">'."\r\n";

		foreach ($arr as $key => $val) 
		{
			// Check new
			$new = @$val['new'] == 1 ? '<span class="menu-baru">NEW</span>' : '';
			$icon = '';
			if ($val['class']) {
				$icon = '<i class="'.$val['class'].'"></i>';
				
			}
			
			$menu .= '<li class="dd-item" data-id="'.$val['id_menu'].'"><div class="dd-handle">'.$icon.'<span class="menu-title">'.$val['nama_menu'].'</span></div>';
			
			if (key_exists('children', $val))
			{ 	
				$menu .= $this->buildMenuList($val['children'], ' class="submenu"');
			}
			$menu .= "</li>\n";
		}
		$menu .= "</ol>\n";
		return $menu;
	}
}