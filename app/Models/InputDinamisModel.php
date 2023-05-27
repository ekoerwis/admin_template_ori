<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Models;

class InputDinamisModel extends \App\Models\BaseModel
{
	public function getPenghadap() {
		
		$sql = 'SELECT * FROM penghadap';
		return $this->db->query($sql)->getResultArray();
	}
	
	public function getPenghadapById() {
		$sql = 'SELECT * FROM penghadap WHERE id_penghadap = ?';
		$result = $this->db->query($sql, trim($_GET['id']))->getResultArray();
		return $result;
	}
	
	public function saveData() 
	{
		foreach ($_POST['nama_penghadap'] as $key => $val) {
					$data_db[] = ['nama_penghadap' => $val
								, 'gelar_depan' => $_POST['gelar_depan'][$key]
								, 'gelar_belakang' => $_POST['gelar_belakang'][$key]
								, 'jenis_kelamin' => $_POST['jenis_kelamin'][$key]
							];
				}
				
		$result = false;
		// echo '<pre>'; print_r($data_db); die;
		// EDIT
		if (!empty($_POST['id'])) 
		{
			$result = $this->db->table('penghadap')->update($data_db[0], 'id_penghadap = ' . $_POST['id']);
			
		} else {
			$result = $this->db->table('penghadap')->insertBatch($data_db);		
		}
				
		return $result;
	}
	
	public function deleteData() {
		$this->db->table('role')->delete(['id_role' => $this->request->getPost('id')]);
		return $this->db->affectedRows();
	}
}
?>