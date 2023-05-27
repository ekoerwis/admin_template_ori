<?php
namespace App\Models;

class ProdukModel extends \App\Models\BaseModel
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getDataProduk() {
		$sql = 'SELECT * FROM produk';
		$result = $this->db->query($sql)->getResultArray();
		return $result;
	}
}