<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$current_module['judul_module']?></h5>
	</div>
	
	<div class="card-body">
		<a href="<?=current_url() . '/add'?>" class="btn btn-success btn-xs"><i class="fa fa-plus pr-1"></i> Tambah Data</a>
		<hr/>
		<?php 
		helper ('html');
		if (!$result) {
			show_message('Data tidak ditemukan', 'error', false);
		} else {
			if (!empty($msg)) {
				show_alert($msg);
			}
			?>
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
			<thead>
			<tr>
				<th>No</th>
				<th>Gelar Depan</th>
				<th>Nama Penghadap</th>
				<th>Gelar Belakang</th>
				<th>Aksi</th>
			</tr>
			</thead>
			<tbody>
			<?php
			helper('html');
			
			$i = 1;
			foreach ($result as $key => $val) {
				echo '<tr>
						<td>' . $i . '</td>
						<td>' . $val['gelar_depan'] . '</td>
						<td>' . $val['nama_penghadap'] . '</td>
						<td>' . $val['gelar_belakang'] . '</td>
						<td>'. btn_action([
									'edit' => ['class' => 'mb-2', 'url' => current_url() . '/edit?id='. $val['id_penghadap']]
									, 'delete' => ['class' => 'mb-2', 'url' => ''
												, 'id' =>  $val['id_penghadap']
												, 'delete-title' => 'Hapus data ?'
											]
							]) .
						'</td>
					</tr>';
					$i++;
			}
			?>
			</tbody>
			</table>
			</div>
			<?php 
		} ?>
	</div>
</div>