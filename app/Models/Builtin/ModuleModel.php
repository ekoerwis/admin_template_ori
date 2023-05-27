<?php
namespace App\Models\Builtin;

class ModuleModel extends \App\Models\BaseModel
{
	public function getAllModules() {
		
		$sql = 'SELECT * FROM module';
		return $this->db->query($sql)->getResultArray();
	}
	
	public function getAllModuleStatus() {
		
		$sql = 'SELECT * FROM module_status';
		return $this->db->query($sql)->getResultArray();
	}
	
	public function getModule($id_module) {
		
		$sql = 'SELECT * FROM module WHERE id_module = ?';
		return $this->db->query($sql, [$id_module])->getRowArray();
	}
	
	public function getAllModuleRole() {
		$sql = 'SELECT * FROM module_role LEFT JOIN module USING(id_module)';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getAllRoles() {
		$sql = 'SELECT * FROM role';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function deleteData() {
		$this->db->table('module')->delete(['id_module' => $this->request->getPost('id')]);
		return $this->db->affectedRows();
	}
	
	public function updateStatus() {
		
		$field = $_POST['switch_type'] == 'aktif' ? 'id_module_status' : 'login';
		$this->db->table('module')
					->update( 
						[$field => $_POST['id_result']], 
						['id_module' => $_POST['id_module']]
					);
	}
	
	public function getModules() {
		$sql = 'SELECT * FROM module LEFT JOIN module_status USING(id_module_status)';
		return $this->db->query($sql)->getResultArray();
	}
	
	public function saveData() 
	{
		$fields = ['nama_module', 'judul_module', 'deskripsi', 'id_module_status'];

		foreach ($fields as $field) {
			$data_db[$field] = $this->request->getPost($field);
		}
		
		// Save database
		if ($this->request->getPost('id')) {
			$id_module = $this->request->getPost('id');
			$save = $this->db->table('module')->update($data_db, ['id_module' => $_POST['id']]);
		} else {
			$save = $this->db->table('module')->insert($data_db);
			$id_module = $this->db->insertID();
		}
		
		if ($save) {
			$result['status'] = 'ok';
			$result['message'] = 'Data berhasil disimpan';
			$result['id_module'] = $id_module;
		} else {
			$result['status'] = 'error';
			$result['message'] = 'Data gagal disimpan';
		}
								
		return $result;
	}
	
	// EDIT
	public function getRole() {
		$id_role = $this->request->getGet('id');
		$sql = 'SELECT * FROM role WHERE id_role = ?';
		$result = $this->db->query($sql, [$id_role])->getRowArray();
		if (!$result)
			$result = [];
		return $result;
	}
	
	
}
?>