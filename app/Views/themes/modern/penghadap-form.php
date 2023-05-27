<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	
	<div class="card-body">
		<?php 
		helper ('html');
		
		if (!empty($msg)) {
			show_message($msg['content'], $msg['status']);
		}
		
		echo btn_label(['class' => 'btn btn-success btn-xs',
			'url' => $config->baseURL . 'input-dinamis/add',
			'icon' => 'fa fa-plus',
			'label' => 'Tambah Data'
		]);
		
		echo btn_label(['class' => 'btn btn-light btn-xs',
			'url' => $config->baseURL . 'input-dinamis',
			'icon' => 'fa fa-arrow-circle-left',
			'label' => 'Data Penghadap'
		]);
		?>
		<hr/>
		<form method="post" action="" id="form-setting">
			<div class="tab-content" id="form-container">
				<?php
				
				if (empty($_POST['nama_penghadap'])) {
					$_POST['gelar_depan'][0] = '';
					$_POST['nama_penghadap'][0] = '';
					$_POST['gelar_belakang'][0] = '';
				}
				
				foreach ($_POST['nama_penghadap'] as $key => $val) 
				{
					$options = options(['name' => 'jenis_kelamin[]'], ['L' => 'Tn.', 'P' => 'Ny.'], set_value('jenis_kelamin['.$key.']', @$jenis_kelamin));
					$btn_icon = $key == 0 ? 'fa-plus' : 'fa-times';
					$btn_add = $key == 0 ? 'id="add-row"' : '';
					$btn_remove = $key == 0 ? '' : 'delete-row';
					$btn_color = $key == 0 ? 'btn-success' : 'btn-danger';
					?>
						<div class="form-group row">
							<div class="col-sm-5 form-inline clearfix">
								<?=$options?>
								<input class="form-control" type="text" name="gelar_depan[]" size="4" value="<?=set_value('gelar_depan['.$key.']', '')?>" placeholder=""/>
								<input class="form-control" type="text" name="nama_penghadap[]" value="<?=set_value('nama_penghadap['.$key.']', '')?>" placeholder="Nama Penghadap" required="required"/>
								<input class="form-control" type="text" size="8" name="gelar_belakang[]" value="<?=set_value('gelar_belakang['.$key.']', '')?>" placeholder=""/>
								<?php
								$router = service('router');
								if ($router->methodName() == 'add') {
									echo '<a href="javascript:void(0)" ' . $btn_add . ' class="btn ' . $btn_color . ' ' . $btn_remove . '"><i class="fas ' . $btn_icon . '"></i></a>';
								}
								?>
							</div>
							
						</div>
					<?php 
				} ?>
				<div class="form-group row" style="margin-top:-7px">
					<div class="col-sm-5 form-inline clearfix">
						<div class="text-muted">Jenis kelamin | gelar depan | nama | gelar belakang</div>
						</div>
				</div>				
				<div class="form-group row mb-0">
					<div class="col-sm-5">
						<button type="submit" name="submit" id="btn-submit" value="submit" class="btn btn-primary">Submit</button>
						<input type="hidden" name="id" value="<?=@$_GET['id']?>"/>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>