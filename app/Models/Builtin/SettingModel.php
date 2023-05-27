<?php
namespace App\Models\Builtin;

class SettingModel extends \App\Models\BaseModel
{
	public function getDefaultSetting() {
		$sql = 'SELECT * FROM setting_app_tampilan';
		$data = $this->db->query($sql)->getResultArray();
		return $data;
	}
	
	public function getUserSetting() {
		$sql = 'SELECT * FROM setting_app_user WHERE id_user = ?';
		$data = $this->db->query($sql, $_SESSION['user']['id_user'])
					->getRowArray();
		return $data;
	}
	
	public function saveData($module_role) 
	{
		$query = false;
		
		$params = ['color_scheme' => 'Color Scheme'
			, 'sidebar_color' => 'Sidebar Color'
			, 'logo_background_color' => 'Background Logo'
			, 'font_family' => 'Font Family'
			, 'font_size' => 'Font Size'
		];
		
		$num = 1;
		foreach ($params as $param => $title) {
			$data_db[] = ['id' => $num, 'param' => $param, 'value' => $_POST[$param]];
			$arr[$param] = $_POST[$param];
			$num++;
		}
		
		if ($module_role[$_SESSION['user']['id_role']]['update_data'] == 'all')
		{
			$this->db->transStart();
			$this->db->query('DELETE FROM setting_app_tampilan');
			$result = $this->db->table('setting_app_tampilan')->insertBatch($data_db);
			$this->db->transComplete();
			$result = $this->db->transStatus();
			
			if ($this->db->transStatus()) {
				$file_name = ROOTPATH . 'public/themes/modern/builtin/css/fonts/font-size-' . $_POST['font_size'] . '.css';
				if (!file_exists($file_name)) {
					file_put_contents($file_name, 'html, body { font-size: ' . $_POST['font_size'] . 'px }');
				}						
			}
			
		} else if ($module_role[$_SESSION['user']['id_role']]['update_data'] == 'own') 
		{
			$this->db->transStart();
			$this->db->table('setting_app_user')->delete(['id_user' => $_SESSION['user']['id_user'] ]);
			$result = $this->db->table('setting_app_user')->insert(['id_user' => $_SESSION['user']['id_user']
															, 'param' => json_encode($arr)
														]
							);
			$this->db->transComplete();
			$result = $this->db->transStatus();
		}
		
		return $result;
	}
}
?>