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
			'url' => $config->baseURL . $current_module['nama_module'] . '/add',
			'icon' => 'fa fa-plus',
			'label' => 'Tambah Data'
		]);
		
		echo btn_label(['class' => 'btn btn-light btn-xs',
			'url' => $config->baseURL . $current_module['nama_module'],
			'icon' => 'fa fa-arrow-circle-left',
			'label' => $current_module['judul_module']
		]);
		?>
		<hr/>
		<form method="post" action="" id="form-container" enctype="multipart/form-data">
			<div class="tab-content" id="form-container">
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nomor Akta</label>
					<div class="col-sm-5 form-inline">
						<input class="form-control" type="text" name="no_akta" value="<?=set_value('no_akta', @$no_akta)?>" placeholder="Nomor Akta" required="required"/>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Tanggal Akta</label>
					<div class="col-sm-5 form-inline">
						<input class="form-control date-picker" type="text" name="tgl_akta" value="<?=set_value('tgl_akta', @format_tanggal($tgl_akta, 'dd-mm-yyyy'))?>" required="required"/>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Akta</label>
					<div class="col-sm-5 form-inline">
						<input class="form-control" type="text" name="nama_akta" value="<?=set_value('nama_akta', @$nama_akta)?>" placeholder="Nama Akta" required="required"/>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Customer</label>
					<div class="col-sm-5 form-inline">
						<input class="form-control" type="text" name="nama_customer" value="<?=set_value('nama_customer', @$nama_customer)?>" placeholder="Nama Customer" required="required"/>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Minuta</label>
					<div class="col-sm-5 form-inline">
						<?php
						echo options(['name' => 'minuta']
										, ['Y' => 'Ya', 'N' => 'Tidak']
										, set_value('minuta', @$minuta)
									);
						?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama Penghadap</label>
					<div class="col-sm-5 form-inline">
						<?php
			
						if (!$penghadap) {
							echo '<div class="alert alert-danger">Data penghadap masih kosong, silakan diisi terlebih dahulu</div>';
						} else {
							echo options(['class' => 'form-control nama-penghadap'
											, 'name' => 'id_penghadap[]'
											, 'multiple' => 'multiple'
											, 'required' => 'required'
											]
										, $penghadap
										, set_value('id_penghadap', @$id_penghadap)
									);
						} ?>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Penaggung Jawab</label>
					<div class="col-sm-5 form-inline">
						<?php
						if (!$penanggungjawab) {
							echo '<div class="alert alert-danger">Data penanggung jawab masih kosong, silakan diisi terlebih dahulu</div>';
						} else {
							echo options(['class' => 'form-control penanggung-jawab'
											, 'name' => 'id_penanggung_jawab[]'
											, 'multiple' => 'multiple'
											, 'required' => 'required'
											]
										, $penanggungjawab
										, set_value('id_penanggung_jawab', @$id_penanggung_jawab)
									);
					}?>
						
					</div>
				</div>
				
				<?php
				if (empty($_POST['nama_file'])) {
					$_POST['nama_file'][0] = '';
					$_POST['deskripsi_file'][0] = '';
				}
				
				foreach ($_POST['nama_file'] as $key => $val) {
						$btn_class = $key == 0 ? 'add-row' : 'delete-row';
						$btn_color = $key == 0 ? 'btn-success' : 'btn-danger';
						$btn_text = $key == 0 ? 'Tambah File' : 'Delete File';
				}
				?>
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Upload Dokumen (Multiple File Upload)</label>
					<div class="col-sm-5"> 
						<div class="mt-2">
							<div style="display:none">
								<input class="form-control mb-2" type="text" name="judul_file[]" value="<?=set_value('judul_file['.$key.']', '')?>" placeholder="Nama File"/>
								<textarea class="form-control mb-2" name="deskripsi_file[]" placeholder="Deskripsi File"/><?=set_value('deskripsi_file['.$key.']', '')?></textarea>
								
								<input type="file" class="file" name="nama_file[]" style="display:block">
								<?php if (!empty($form_errors['file_dokumen[' . $key . ']'])) echo '<small class="alert alert-danger">' . $form_errors['file_dokumen['. $key . ']'] . '</small>'?>
								<div class="upload-img-thumb"><span class="img-prop"></span></div>
							</div>
							<a class="btn btn-success btn-xs mt-2 add-row" href="javascript:void(0)">Tambah File</a>
						</div>
					<?php
					
					if (!empty($nama_file)) {
						foreach ($nama_file as $key_data => $val_data) {
							if (file_exists(ROOTPATH . '/public/files/dokumen/' . $val_data)){
								$nama_file = $val_data;
								$nama_file_input = '<input type="text" class="form-control" name="nama_file_edit[]" value="' . $val_data . '" placeholder="Nama File"/>';
							} else {
								$nama_file = 'File tidak titemukan';
								$nama_file_input = '<span class="text-muted"><strong>Tidak ditemukan</strong></span>';
							}
							echo '
								<div class="mt-2">
									<div>
										<input class="form-control mb-2" type="text" name="judul_file_edit[]" value="'. $judul_file[$key_data]. '" placeholder="Judul File"/>
										<textarea class="form-control mb-2" name="deskripsi_file_edit[]" placeholder="Deskripsi File"/>'. $deskripsi_file[$key_data] .'</textarea>' 
										. $nama_file_input . '
									 </div>
									 <a href="javascript:void(0)" class="btn btn-danger btn-xs mt-2 remove-current-file">Hapus File</a>
									 <input type="hidden" name="current_file_id_akta_file[]" value="'. $id_akta_file[$key_data].'"/>
									<input type="hidden" name="current_file_nama_file[]" value="'. $nama_file.'"/>
									<input type="hidden" name="delete_current_file[]" value="" class="delete-current-file" value="0"/>
								</div>';
						}
					}?>
					</div>
				</div>
				<div class="form-group row mb-0">
					<div class="col-sm-5">
						<?php 
						$disabled = '';
						if (!$penghadap || !$penanggungjawab) {
							$disabled = 'disabled';
						}
						?>
						<button type="submit" name="submit" id="btn-submit" value="submit" <?=$disabled?> class="btn btn-primary">Submit</button>
						<input type="hidden" name="id" value="<?=@$_GET['id']?>"/>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>