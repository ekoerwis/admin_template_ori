<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$current_module['judul_module']?></h5>
	</div>
	
	<div class="card-body">
		<a href="<?=current_url()?>/add" class="btn btn-success btn-xs"><i class="fa fa-plus pr-1"></i> Tambah Data</a>
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
				<th>Tanggal</th>
				<th>Nama Akta</th>
				<th>Afiliasi</th>
				<th>Penghadap</th>
				<th>Penanggung Jawab</th>
				<th>Dokumen</th>
				<th>Aksi</th>
			</tr>
			</thead>
			<tbody>
			<?php

			$i = 1;
			foreach ($result as $key => $val) {
				
				$nama_penanggung_jawab = '<ol><li>' . join( '</li><li>', explode(',', $val['nama_penanggung_jawab'])) . '</li></ol>';
				$nama_penghadap = '<ol><li>' . join( '</li><li>', explode(',', $val['nama_penghadap'])) . '</li></ol>';
				
				$dokumen = [];
				
				if (key_exists($val['id_akta'], $akta_file)) {
					foreach ($akta_file[$val['id_akta']] as $val_file) {
						$dokumen[] = '<a href="' . $config->baseURL . 'public/files/dokumen/' . $val_file['nama_file'] . '" target="_blank">' . $val_file['judul_file'] . '</a>';
					}
				}
				
				$list_dokumen = '';
				if ($dokumen) {
					$list_dokumen = '<ol><li>' . join ('</li><li>', $dokumen) . '</li></ol>';
				}
				
				echo '<tr>
						<td>' . $i . '</td>
						<td>' . format_tanggal($val['tgl_akta']) . '</td>
						<td>' . $val['nama_akta'] . '</td>
						<td>' . $val['nama_customer'] . '</td>
						<td>' . $nama_penghadap . '</td>
						<td>' . $nama_penanggung_jawab . '</td>
						<td>' . $list_dokumen . '</td>
						<td>'. btn_action([
									'edit' => ['class' => 'mb-2', 'url' => $config->baseURL . $current_module['nama_module'] . '/edit?id='. $val['id_akta']]
									, 'delete' => ['class' => 'mb-2', 'url' => ''
												, 'id' =>  $val['id_akta']
												, 'delete-title' => 'Hapus data akta: <strong>' . $val['nama_akta'] . '</strong>?'
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