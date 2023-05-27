<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Models\Builtin;

class MenuModel extends \App\Models\BaseModel
{
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getMenuDb($aktif = 'all', $show_all = false) {
		
		global $db;
		global $app_module;
		
		$result = [];
		$nama_module = $app_module['nama_module'];
		
		$where = ' ';
		$where_aktif = '';
		if ($aktif != 'all') {
			$where_aktif = ' AND aktif = '.$aktif;
		}
		
		$role = '';
		if (!$show_all) {
			$role = ' AND id_role = ' . $_SESSION['user']['id_role'];
		}
		
		$sql = 'SELECT * FROM menu 
					LEFT JOIN menu_role USING (id_menu)
					LEFT JOIN module USING (id_module)
				WHERE 1 = 1 ' . $role
					. $where_aktif.' 
				ORDER BY urut';
		
		$this->db->query($sql)->resultArray();
		
		$current_id = '';
		foreach ($query->getResult('array') as $row) {
			$result[$row['id_menu']] = $row;
			$result[$row['id_menu']]['highlight'] = 0;
			$result[$row['id_menu']]['depth'] = 0;

			if ($nama_module == $row['nama_module']) {
				
				$current_id = $row['id_menu'];
				$result[$row['id_menu']]['highlight'] = 1;
			}
		}
		
		if ($current_id) {
			menu_current($result, $current_id);
		}
		
		return $result;
	}
	
	public function getListModules() {
		
		$sql = 'SELECT * FROM module LEFT JOIN module_status USING(id_module_status)';
		return $this->db->query($sql)->getResultArray();
	}
	
	public function getAllRole() {
		$sql = 'SELECT * FROM role';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getMenuDetail() {
		$sql = 'SELECT * FROM menu WHERE id_menu = ?';
		$result = $this->db->query($sql, $_GET['id'])->getRowArray();
		return $result;
	}
	
	public function saveData($id = null) 
	{
		$data_db['nama_menu'] = $_POST['nama_menu'];
		$data_db['id_module'] = $_POST['id_module'];
		$data_db['url'] = $_POST['url'];
		if (empty($_POST['aktif'])) {
			$data_db['aktif'] = 0;
		} else {
			$data_db['aktif'] = 1;
		}
		
		if ($_POST['use_icon']) {
			$data_db['class'] = $_POST['icon_class'];
		} else {
			$data_db['class'] = NULL;
		}
		
		if ($id) {
			$this->db->table('menu')->update($data_db, 'id_menu = ' . $id);
			return $this->db->affectedRows();
		} else {
			$save = $this->db->table('menu')->insert($data_db);
			$insert_id = $this->db->insertID();
		}
	}
	
	public function deleteData() {
		$this->db->table('menu')->delete(['id_menu' => $this->request->getPost('id')]);
		return $this->db->affectedRows();
	}
	
	public function getAllMenu() {
		$result = $this->db->query('SELECT * FROM menu')->getResultArray();
		return $result;
	}
	
	public function updateData() {
		
		$json = json_decode(trim($_POST['data']), true);
		$array = $this->buildChild($json);
		
		foreach ($array as $id_parent => $arr) {
			foreach ($arr as $key => $id_menu) {
				$list_menu[$id_menu] = ['id_parent' => $id_parent, 'urut' => ($key + 1)];
			}
		}
		// echo '<pre>'; print_r($list_menu);die;
		$result = $this->getAllMenu();
		$menu_updated = [];
		foreach ($result as $key => $row) 
		{
			$update = [];
			if ($list_menu[$row['id_menu']]['id_parent'] != $row['id_parent']) {
				$id_parent =  $list_menu[$row['id_menu']]['id_parent'] == 0 ? NULL : $list_menu[$row['id_menu']]['id_parent'];
				$update['id_parent'] = $id_parent;
			}
			
			if ($list_menu[$row['id_menu']]['urut'] != $row['urut']) {
				$update['urut'] = $list_menu[$row['id_menu']]['urut'];
			}
			
			if ($update) {
				$result = $this->db->table('menu')->update($update, ['id_menu=' => $row['id_menu']]);
				if ($result) {
					$menu_updated[$row['id_menu']] = $row['id_menu'];
				}
			}
		}
		return $menu_updated;
	}
	
	private function buildChild($arr, $parent=0, &$list=[]) 
	{
		foreach ($arr as $key => $val) 
		{
			$list[$parent][] = $val['id'];

			if (key_exists('children', $val))
			{ 
				$this->buildChild($val['children'], $val['id'], $list);
			}
		}
		
		
		return $list;
	}
}
?>