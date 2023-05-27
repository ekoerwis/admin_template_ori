<?php
/**
*	App Name	: Admin Template Dashboard Codeigniter 4	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2020
*/

namespace App\Models\Builtin;

class LoginModel extends \App\Models\BaseModel
{	
	public function recordLogin() 
	{
		$username = $this->request->getPost('username'); 
		$data_user = $this->db->query('SELECT id_user 
									FROM user
									LEFT JOIN role USING (id_role)
									LEFT JOIN module USING (id_module)
									WHERE username = ?', [$username]
								)
							->getRow();
		// echo '<pre>'; print_r($data_user->id_user); die;		
		$data = array('id_user' => $data_user->id_user
					, 'id_activity' => 1
					, 'time' => date('Y-m-d H:i:s')
				);
		
		//$db      = \Config\Database::connect();		
		//$db->table('user_login_activity')->insert($data);
		$this->db->table('user_login_activity')->insert($data);
	}
	
	
	/* See base model
	public function checkUser($username) 
	{
		
	} */
}
?>