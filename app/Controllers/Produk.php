<?php 
namespace App\Controllers;
use App\Models\ProdukModel;

class Produk extends BaseController
{
	private $produkModel;
	
	public function __construct() {
		parent::__construct();
		$this->produkModel = new ProdukModel;
	}
	
	public function index()
	{
		$data = $this->data;
		$data['result'] = $this->produkModel->getDataProduk();
		
		if (!$data['result'])
			$this->errorDataNotFound();
		
		$this->view('produk-result.php', $data);
	}
}