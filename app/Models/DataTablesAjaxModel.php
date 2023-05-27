<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Models;

class DataTablesAjaxModel extends \App\Models\BaseModel
{
	private $fotoPath;
	
	public function __construct() {
		parent::__construct();
		$this->fotoPath = 'public/images/foto/';
	}
	
	public function deleteData() {
		$sql = 'SELECT foto FROM mahasiswa WHERE id_mahasiswa = ?';
		$img = $this->db->query($sql, $_POST['id'])->getRowArray();
		if ($img) {
			if (file_exists($this->fotoPath . $img['foto'])) {
				$unlink = unlink($this->fotoPath . $img['foto']);
				if (!$unlink) {
					return false;
				}
			}
		}
		$result = $this->db->table('mahasiswa')->delete(['id_mahasiswa' => $_POST['id']]);
		return $result;
	}
	
	public function getMahasiswa() 
	{
		$sql = 'SELECT * FROM mahasiswa';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getMahasiswaById($id) {
		$sql = 'SELECT * FROM mahasiswa WHERE id_mahasiswa = ?';
		$result = $this->db->query($sql, trim($id))->getRowArray();
		return $result;
	}
	
	public function saveData() {
		
		$exp = explode('-', $_POST['tgl_lahir']);
		$tgl_lahir = $exp[2].'-'.$exp[1].'-'.$exp[0];
		$data_db['nama'] = $_POST['nama'];
		$data_db['tempat_lahir'] = $_POST['tempat_lahir'];
		$data_db['tgl_lahir'] = $tgl_lahir;
		$data_db['npm'] = $_POST['npm'];
		$data_db['prodi'] = $_POST['prodi'];
		$data_db['fakultas'] = $_POST['fakultas'];
		$data_db['alamat'] = $_POST['alamat'];
		
		$query = false;
		
		$new_name = '';
		$img_db['foto'] = '';
		
		if ($_POST['id']) {
			$sql = 'SELECT foto FROM mahasiswa WHERE id_mahasiswa = ?';
			$img_db = $this->db->query($sql, $_POST['id'])->getRowArray();
			$new_name = $img_db['foto'];
		}
		
		if ($_FILES['foto']['name']) 
		{
			//old file
			if ($_POST['id']) {
				if ($img_db['foto']) {
					if (file_exists($this->fotoPath . $img_db['foto'])) {
						$unlink = unlink($this->fotoPath . $img_db['foto']);
						if (!$unlink) {
							$result['msg']['status'] = 'error';
							$result['msg']['content'] = 'Gagal menghapus gambar lama';
						}
					}
				}
			}
			
			$new_name = upload_image($this->fotoPath, $_FILES['foto'], 300,300);
		}
		
		if ($_POST['id']) 
		{
			if ($new_name) {
				$data_db['foto'] = $new_name;
				$data_db['tgl_edit'] = date('Y-m-d');
				$data_db['id_user_edit'] = $_SESSION['user']['id_user'];
				$query = $this->db->table('mahasiswa')->update($data_db, ['id_mahasiswa' => $_POST['id']]);
				if ($query) {
					$result['msg']['status'] = 'ok';
					$result['msg']['content'] = 'Data berhasil disimpan';
				} else {
					$result['msg']['status'] = 'error';
					$result['msg']['content'] = 'Data gagal disimpan';
				}
			} else {
				$result['msg']['status'] = 'error';
				$result['msg']['content'] = 'Error saat memperoses gambar';
			}
		} else {
			$data_db['foto'] = $new_name;
			$data_db['tgl_input'] = date('Y-m-d');
			$data_db['id_user_input'] = $_SESSION['user']['id_user'];
			$query = $this->db->table('mahasiswa')->insert($data_db);
			$result['id_mahasiswa'] = '';
			if ($query) {
				$result['msg']['status'] = 'ok';
				$result['msg']['content'] = 'Data berhasil disimpan';
				$result['id_mahasiswa'] = $this->db->insertID();
			} else {
				$result['msg']['status'] = 'error';
				$result['msg']['content'] = 'Data gagal disimpan';
			}
		}
		
		return $result;
	}
	
	public function countAllData() {
		$sql = 'SELECT COUNT(*) AS jml FROM mahasiswa';
		$result = $this->db->query($sql)->getRow();
		return $result->jml;
	}
	
	public function getListData() {
		
		$table = 'comment';

		$columns = $this->request->getPost('columns');
		$order_by = '';
		
		// Search
		$search_all = @$this->request->getPost('search')['value'];
		$where = '1 = 1';
		if ($search_all) {
			// Additional Search
			$columns[]['data'] = 'tempat_lahir';
			foreach ($columns as $val) {
				
				if (strpos($val['data'], 'ignore_search') !== false) 
					continue;
				
				if (strpos($val['data'], 'ignore') !== false)
					continue;
				
				$where_col[] = $val['data'] . ' LIKE "%' . $search_all . '%"';
			}
			 $where .= ' AND (' . join(' OR ', $where_col) . ') ';
		}
		
		// Order
		$order = $this->request->getPost('order');
		
		if (@$order[0]['column'] != '' ) {
			$order_by = ' ORDER BY ' . $columns[$order[0]['column']]['data'] . ' ' . strtoupper($order[0]['dir']);
		}

		$start = $this->request->getPost('start') ?: 0;
		$length = $this->request->getPost('length') ?: 10;
		
		// Query Data
		$sql = 'SELECT * FROM mahasiswa WHERE 
				' . $where . $order_by . ' LIMIT ' . $start . ', ' . $length;
				
		return $this->db->query($sql)->getResultArray();
	}
}
?>