<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$current_module['judul_module']?></h5>
	</div>
	
	<div class="card-body">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Produk</th>
					<th>Deskripsi Produk</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$no = 1;
			foreach ($result as $val) {
				echo '<tr>
						<td>' . $no . '</td>
						<td>' . $val['nama_produk'] . '</td>
						<td>' . $val['deskripsi_produk'] . '</td>
					</tr>';
				$no++;
			}
			?>
			</tbody>
		</table>
	</div>
</div>