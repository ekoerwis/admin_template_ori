<?php
namespace App\Models\Builtin;

class ModuleRoleModel extends \App\Models\BaseModel
{
	public function getAllModule() {
		$sql = 'SELECT * FROM module';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getModule($id) {
		$sql = 'SELECT * FROM module WHERE id_module = ?';
		$result = $this->db->query($sql, [$id])->getRowArray();

		return $result;
	}
	
	public function getAllRole() {
		$sql = 'SELECT * FROM role';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getRoleDetail() {
		$sql = 'SELECT * FROM role_detail';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getAllModuleRole() {
		$sql = 'SELECT * FROM module_role';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getModuleRoleById($id) {
		$sql = 'SELECT * FROM module_role WHERE id_module = ?';
		$result = $this->db->query($sql, [$id])->getResultArray();
		// echo '<pre>'; print_r($result); die;
		return $result;
	}
	
	public function getModuleStatus() {
		$sql = 'SELECT * FROM module
				LEFT JOIN module_status USING(id_module_status)';
				
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function deleteData() {
		$this->db->table('module_role')->delete(['id_module' => $this->request->getPost('pair_id'), 'id_role' => $_POST['id_role']]);
		return $this->db->affectedRows();
	}
	
	public function saveData() 
	{
		foreach ($_POST as $key => $val) {
			$exp = explode('_', $key);
			if ($exp[0] == 'role') {
				$id_role = $exp[1];
				$data_db[] = ['id_module' => $_POST['id']
								, 'id_role' => $id_role
								, 'read_data' => $_POST['akses_read_data_' . $id_role]
								, 'create_data' => $_POST['akses_create_data_' . $id_role]
								, 'update_data' => $_POST['akses_update_data_' . $id_role]
								, 'delete_data' => $_POST['akses_delete_data_' . $id_role]
							];
			}
		}
		
		// INSERT - UPDATE
		$this->db->transStart();
		$this->db->table('module_role')->delete(['id_module' => $_POST['id']]);
		$this->db->table('module_role')->insertBatch($data_db);
		$this->db->transComplete();
		$result = $this->db->transStatus();
								
		return $result;
	}
}
?>