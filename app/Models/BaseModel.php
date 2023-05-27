<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Models;

class BaseModel extends \CodeIgniter\Model 
{
	protected $request;
	protected $session;
	private $auth;
	protected $user;
	
	public function __construct() {
		parent::__construct();
		$this->request = \Config\Services::request();
		$this->session = \Config\Services::session();
		$user = $this->session->get('user');
		if ($user)
			$this->user = $this->getUserById($user['id_user']);
		
		$this->auth = new \App\Libraries\Auth;
	}
	
	public function getUserById($id_user = null, $array = false) {
		
		if (!$id_user) {
			if (!$this->user) {
				return false;
			}
			$id_user = $this->user->id_user;
		}
		
		$query = $this->db->query('SELECT * FROM user WHERE id_user = ?', [$id_user]);
		if ($array)
			return $query->getRowArray();
		
		return $query->getRow();
	}
	
	public function getUserSetting() {
		
		$result = $this->db->query('SELECT * FROM setting_app_user WHERE id_user = ?', [$this->session->get('user')['id_user']])
						->getRow();
		
		if (!$result) {
			$query = $this->db->query('SELECT * FROM setting_app_tampilan')
						->getResultArray();
			
			foreach ($query as $val) {
				$data[$val['param']] = $val['value'];
			}
			$result = new \StdClass;
			$result->param = json_encode($data);
		}
		return $result;
	}
	
	public function getAppLayoutSetting() {
		$result = $this->db->query('SELECT * FROM setting_app_tampilan')->getResultArray();
		return $result;
	}
	
	public function getDefaultUserModule() {
		
		$query = $this->db->query('SELECT * 
							FROM role 
							LEFT JOIN module USING(id_module)
							WHERE id_role = ? '
							, $this->session->get('user')['id_role']
						)->getRow();
		return $query;
	}
	
	public function getModule($nama_module) {
		$result = $this->db->query('SELECT * FROM module WHERE nama_module = ?', [$nama_module])
						->getRowArray();
		return $result;
	}
	
	public function getMenu($aktif = 'all', $showAll = false, $current_module) {
	
		$result = [];
		$where = ' ';
		$where_aktif = '';
		if ($aktif != 'all') {
			$where_aktif = ' AND aktif = '.$aktif;
		}
		
		$role = '';
		if (!$showAll) {
			$role = ' AND id_role = ' . $_SESSION['user']['id_role'];
		}
		
		$sql = 'SELECT * FROM menu 
					LEFT JOIN menu_role USING (id_menu)
					LEFT JOIN module USING (id_module)
				WHERE 1 = 1 ' . $role
					. $where_aktif.' 
				ORDER BY urut';
		
		$menu_array = $this->db->query($sql)->getResultArray();
		
		$current_id = '';
		foreach ($menu_array as $val) 
		{
			
			$result[$val['id_menu']] = $val;
			$result[$val['id_menu']]['highlight'] = 0;
			$result[$val['id_menu']]['depth'] = 0;

			if ($current_module == $val['nama_module']) {
				
				$current_id = $val['id_menu'];
				$result[$val['id_menu']]['highlight'] = 1;
			}
			
		}
		
		if ($current_id) {
			$this->menuCurrent($result, $current_id);
		}
		
		return $result;
		
	}
	
	private function menuCurrent( &$result, $current_id) 
	{
		$parent = $result[$current_id]['id_parent'];

		$result[$parent]['highlight'] = 1; // Highlight menu parent
		if (@$result[$parent]['id_parent']) {
			$this->menuCurrent($result, $parent);
		}
	}
	
	public function getModuleRole($id_module) {
		 $result = $this->db->query('SELECT * FROM module_role WHERE id_module = ? ', $id_module)->getResultArray();
		 return $result;
	}

	public function validateFormToken($session_name = null, $post_name = 'form_token') {				

		$form_token = explode (':', $this->request->getPost($post_name));
		
		$form_selector = $form_token[0];
		$sess_token = $this->session->get('token');
		if ($session_name)
			$sess_token = $sess_token[$session_name];
	
		if (!key_exists($form_selector, $sess_token))
				return false;
		
		try {
			$equal = $this->auth->validateToken($sess_token[$form_selector], $form_token[1]);

			return $equal;
		} catch (\Exception $e) {
			return false;
		}
		
		return false;
	}
	
	public function checkUser($username) 
	{
		$sql = 'SELECT * FROM user 
				WHERE username = ?';
		
		$query = $this->db->query($sql, [$username]);
		$result = $query->getRowArray();
		
		return $result;		
	}
	
	public function getSettingWeb() {
		$sql = 'SELECT * FROM setting_web';
		$query = $this->db->query($sql)->getResultArray();
		
		$settingWeb = new \stdClass();
		foreach($query as $val) {
			$settingWeb->{$val['param']} = $val['value'];
		}
		return $settingWeb;
	}
	
	public function checkLogin() 
	{
		if ($this->session->get('logged_in')) 
		{
			return true; 
		}
		
		helper('cookie');
		$cookie_login = get_cookie('jwd_remember');
	
		if ($cookie_login) 
		{
			list($selector, $cookie_token) = explode(':', $cookie_login);

			$sql = 'SELECT * 
					FROM token
					WHERE selector = ' . $this->db->escape($selector) . ' 
						AND expires > "' . date('Y-m-d H:i:s') . '"';				
			$db_token = $this->db->query($sql)->getRow();

			if ($db_token)
			{
				if ($db_token->expires >= date('Y-m-d H:i:s')) {
					if ($this->auth->validateToken($db_token->token, $cookie_token)) 
					{
						$id_user = preg_replace('/\D/', '', $db_token->param);
						$result = $this->getUserById($id_user, true);
						$this->session->set('user', $result);
						$this->session->set('logged_in', true);
						return true;
					}
				} else {
					redirect()->to('login/logout');
				}
			}
		}
	}
}