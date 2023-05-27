<?php
namespace App\Models\Builtin;

class UserModel extends \App\Models\BaseModel
{
	public function getListUsers($action_user) {
		
		// Get user
		$where = ' WHERE 1 = 1 ';
		if ($action_user['read_data'] == 'own') {
			$where .= ' AND id_user = ' . $this->session->get('user')['id_user'];
		}
		$columns = $this->request->getPost('columns');
		$order_by = '';
		
		// Search
		$search_all = @$this->request->getPost('search')['value'];
		if ($search_all) {
			
			foreach ($columns as $val) {
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
		if (!$start) {
			
		}
		$sql = 'SELECT * FROM user LEFT JOIN role USING(id_role) ' . $where . $order_by . ' LIMIT ' . $start . ', ' . $length;
		// echo $sql;
		return $this->db->query($sql)->getResultArray();
	}
	
	public function getRole() {
		$sql = 'SELECT * FROM role';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
	
	public function getMembership() {
		$sql = 'SELECT * FROM membership';
		$query = $this->db->query($sql)->getResultArray();
		foreach ($query as $key => $val) {
			$result[$val['id_membership']] = $val['judul_membership'];
		}
		return $result;
	}
	
	public function saveData($action_user = []) 
	{ 
		$fields = ['id_role', 'nama', 'email', 'aktif'];
		if ($action_user['update_data'] == 'all') {
			$fields[] = 'username';
		}

		foreach ($fields as $field) {
			$data_db[$field] = $this->request->getPost($field);
		}
		
		if (!$this->request->getPost('id')) {
			$data_db['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
			$exp = explode('-', $this->request->getPost('tgl_lahir'));
		}
		
		// Save database
		if ($this->request->getPost('id')) {
			$id_user = $this->request->getPost('id');
			$save = $this->db->table('user')->update($data_db, ['id_user' => $id_user]);
		} else {
			$data_db['aktif'] = 1;
			$save = $this->db->table('user')->insert($data_db);
			$id_user = $this->db->insertID();
		}
		
		if ($save) {
			
			$file = $this->request->getFile('avatar');
			
			if ($file && $file->getName()) 
			{
				$error = false;
				$path = 'public/images/user/';
				
				//old file
				if ($this->request->getPost('id')) 
				{
					$sql = 'SELECT avatar FROM user WHERE id_user = ?';
					$img_db = $this->db->query($sql, $id_user)->getRow();
					if ($img_db->avatar) {
						$unlink = unlink($path . '/' . $img_db->avatar);
						if (!$unlink) {
							$result['message'] = 'Data berhasil disimpan, tetapi gagal memproses foto: gagal menghapus gambar lama';
							return $result;
						}
					}
				}
				
				if (!is_dir($path)) {
					if (!mkdir($path, 0777, true)) {
						$result['message'] = 'Data berhasil disimpan, tetapi gagal memproses foto: Unable to create a directory';
						return $result;
					}
				}
				
				$new_name =  get_filename($file->getName(), $path);
					
				$file->move($path, $new_name);
					
				if (!$file->hasMoved()) {
					$result['message'] = 'Error saat memperoses gambar';
					return $result;
				}
				
				// Update avatar
				$data_db = [];
				$data_db['avatar'] = $new_name;
				$save = $this->db->table('user')->update($data_db, ['id_user' => $id_user]);
			}
		}
		
		if ($save) {
			$result['status'] = 'ok';
			$result['message'] = 'Data berhasil disimpan';
		} else {
			$result['status'] = 'error';
		}
								
		return $result;
	}
	
	public function deleteUser() {
		$this->db->table('user')->delete(['id_user' => $this->request->getPost('id')]);
		return $this->db->affectedRows();
	}
	
	public function countAllUsers() {
		$query = $this->db->query('SELECT COUNT(*) as jml FROM user')->getRow();
		return $query->jml;
	}

	public function updatePassword() {
		$password_hash = password_hash($this->request->getPost('password_new'), PASSWORD_DEFAULT);
		$update = $this->db->query('UPDATE user SET password = ? 
									WHERE id_user = ? ', [$password_hash, $this->user->id_user]
								);		
		return $update;
	}
}
?>