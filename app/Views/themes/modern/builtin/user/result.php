<div class="card">
	<div class="card-body">
	<div class="table-responsive">
		<a href="<?=current_url()?>/add" class="btn btn-success btn-xs"><i class="fa fa-plus pr-1"></i> Tambah Data</a>
		<hr/>
		<?php

		if (!empty($msg)) {
			show_message($msg);
		}
		
		$column =[
					'avatar' => 'Avatar'
					, 'email' => 'Email'
					, 'nama' => 'Nama'
					, 'username' => 'Username'
					, 'judul_role' => 'Role'
					, 'ignore_btn_action' => 'Aksi'
				];
		$th = '';
		foreach ($column as $val) {
			$th .= '<th>' . $val . '</th>'; 
		}
		?>
		<table id="table-result" class="table display nowrap table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
				<?=$th?>
            </tr>
        </thead>
        <tfoot>
            <tr>
				<?=$th?>
            </tr>
        </tfoot>
		</table>
		<?php
			foreach ($column as $key => $val) {
				$column_dt[] = ['data' => $key];
			}
		?>
		<span id="dataTables-column" style="display:none"><?=json_encode($column_dt)?></span>
		<span id="dataTables-url" style="display:none"><?=current_url() . '/getUserDT'?></span>
	</div>
	</div>
</div>