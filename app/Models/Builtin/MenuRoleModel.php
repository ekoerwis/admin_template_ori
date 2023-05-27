<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Models\Builtin;

class MenuRoleModel extends \App\Models\BaseModel
{
	public function getAllMenu() {
		$sql = 'SELECT * FROM menu';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getAllRole() {
		$sql = 'SELECT * FROM role';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getAllMenuRole() {
		$sql = 'SELECT * FROM menu_role';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getMenuRoleById($id) {
		$sql = 'SELECT * FROM menu_role WHERE id_menu = ?';
		$result = $this->db->query($sql, $id)->getResultArray();
		return $result;
	}
	
	public function deleteData() {
		$this->db->table('menu_role')->delete(['id_menu' => $this->request->getPost('id_menu'), 'id_role' => $_POST['id_role']]);
		return $this->db->affectedRows();
	}
	
	public function saveData() 
	{
		// Find all parent
		$menu_parent = $this->allParents($_POST['id_menu']);
		$role_del = [];

		// Cek role yang tercentang
		foreach ($_POST as $key => $val) {
			$exp = explode('_', $key);
			if ($exp[0] == 'role') {
				$id_role = $exp[1];
				$insert[] = ['id_menu' => $_POST['id_menu'], 'id_role' => $exp[1]];
				$curr_id_role[$id_role] = $id_role;
			}
		}
		// echo '<pre>'; print_r($insert);
		
		$insert_parent = [];
		if ($menu_parent) 
		{
			// Cek apakah parent telah diassign di role yang tercentang, jika belum buat insert nya
			foreach($menu_parent as $id_menu_parent) {
				foreach ($curr_id_role as $id_role) {
					$sql = 'SELECT * FROM menu_role WHERE id_menu = ? AND id_role = ?';
					$data = [$id_menu_parent, $id_role];
					$query = $this->db->query($sql, $data)->getResultArray();
					if (!$query) {
						$insert_parent[] = ['id_menu' => $id_menu_parent, 'id_role' => $id_role];
					}
				}
			}

			// Delete parent
			// Cari role yang tidak tercentang, kemudian hapus dari tabel
			$sql = 'SELECT * FROM role';
			$result = $this->db->query($sql)->getResultArray();
			foreach($result as $val) {
				if (!key_exists($val['id_role'], $curr_id_role)) {
					$role_del[$val['id_role']] = $val['id_role'];
				}
			}
		}
		

		// INSERT - DELETE
		$this->db->transStart();
		if ($insert_parent) {
			$this->db->table('menu_role')->insertBatch($insert_parent);
		}
		
		// Hapus role yang tidak tercentang
		foreach ($role_del as $id_role) {
			$this->db->table('menu_role')->delete(['id_menu' => $_POST['id_menu'], 'id_role' => $id_role]);
		}

		// Insert role yang tercentang
		foreach ($curr_id_role as $id_role) 
		{
			$sql = 'SELECT * FROM menu_role WHERE id_menu = ? AND id_role = ?';
			$query = $this->db->query($sql, [$_POST['id_menu'], $id_role])->getRowArray();
			if (!$query) {
				$this->db->table('menu_role')->insert(['id_menu' => $_POST['id_menu'], 'id_role' => $id_role]);
			}
		}

		$this->db->transComplete();
		$trans = $this->db->transStatus();
		
		if ($trans) {
			$result['status'] = 'ok';
			$result['insert_parent'] = $insert_parent;
		} else {
			$result['status'] = 'error';
		}
		return $result;
	}
	
	private function allParents($id_menu, &$list_parent = []) {
		
		$query = $this->db->query('SELECT * FROM menu')->getResultArray();
		foreach($query as $val) {
			$menu[$val['id_menu']] = $val;
		}
		
		if (key_exists($id_menu, $menu)) {
			$parent = $menu[$id_menu]['id_parent'];
			if ($parent) {
				$list_parent[$parent] = &$parent;
				$this->allParents($parent, $list_parent);
			}
		}
		
		return $list_parent;
	}
}
?>