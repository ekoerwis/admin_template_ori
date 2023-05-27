<?php
namespace App\Models\Builtin;

class RoleModel extends \App\Models\BaseModel
{
	public function getAllModules() {
		
		$sql = 'SELECT * FROM module';
		return $this->db->query($sql)->getResultArray();
	}
	
	public function getModuleStatus() {
		$sql = 'SELECT * FROM module_status';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function listModuleRole() {
		$sql = 'SELECT * FROM module_role LEFT JOIN module USING(id_module)';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getAllRole() {
		$sql = 'SELECT * FROM role';
		$result = $this->db->query($sql)->getResultArray();
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
	
	public function saveData() 
	{
		$fields = ['judul_role', 'keterangan'];

		foreach ($fields as $field) {
			$data_db[$field] = $this->request->getPost($field);
		}
		$fields['id_module'] = $this->request->getPost('id_module') ?: 0;
		
		// Save database
		if ($this->request->getPost('id')) {
			$id_role = $this->request->getPost('id');
			$save = $this->db->table('role')->update($data_db, ['id_role' => $id_role]);
		} else {
			$save = $this->db->table('role')->insert($data_db);
			$id_role = $this->db->insertID();
		}
		
		if ($save) {
			$result['status'] = 'ok';
			$result['message'] = 'Data berhasil disimpan';
			$result['id_role'] = $id_role;
		} else {
			$result['status'] = 'error';
			$result['message'] = 'Data gagal disimpan';
		}
								
		return $result;
	}
	
	public function deleteData() {
		$this->db->table('role')->delete(['id_role' => $this->request->getPost('id')]);
		return $this->db->affectedRows();
	}
}
?>