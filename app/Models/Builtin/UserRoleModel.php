<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Models\Builtin;

class UserRoleModel extends \App\Models\BaseModel
{
	public function getAllRole() {
		$sql = 'SELECT * FROM role';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getUserRole() {
		$sql = 'SELECT * FROM user_role';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getUserRoleByID($id) {
		$sql = 'SELECT * FROM user_role WHERE id_user = ?';
		$result = $this->db->query($sql, $id)->getResultArray();
		return $result;
	}
	
	public function getAllUser() {
		$sql = 'SELECT * FROM user';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function deleteData() {
		$this->db->table('user_role')->delete(['id_user' => $_POST['pair_id'], 'id_role' => $_POST['id_role']]);
		return $this->db->affectedRows();
	}
	
	public function saveData() {
		foreach ($_POST as $key => $val) {
			$exp = explode('_', $key);
			if ($exp[0] == 'role') {
				$insert[] = ['id_user' => $_POST['pair_id'], 'id_role' => $exp[1]];
			}
		}
		
		// INSERT - UPDATE
		$this->db->transStart();
		$this->db->table('user_role')->delete(['id_user' => $_POST['pair_id']]);
		$this->db->table('user_role')->insertBatch($insert);
		$this->db->transComplete();
		$result = $this->db->transStatus();
		
		return $result;
	}
}
?>