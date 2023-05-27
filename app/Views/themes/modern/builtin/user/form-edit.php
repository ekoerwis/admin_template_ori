<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$title?></h5>
	</div>
	
	<div class="card-body">
		<?php 
	
			helper('html');
			helper('builtin/util');
			echo btn_label(['class' => 'btn btn-success btn-xs',
				'url' => $module_url . '/add',
				'icon' => 'fa fa-plus',
				'label' => 'Tambah User'
			]);
			
			echo btn_label(['class' => 'btn btn-light btn-xs',
				'url' => $module_url,
				'icon' => 'fa fa-arrow-circle-left',
				'label' => 'Daftar ' . $current_module['judul_module']
			]);
		?>
		<hr/>
		<?php
		if (!empty($msg)) {
			show_message($msg);
		}
		?>
		<form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
			<div class="tab-content">
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Foto</label>
					<div class="col-sm-5">
						<?php 
						$avatar = @$_FILES['file']['name'] ?: @$avatar;
						if (!empty($avatar))
						echo '<div class="list-foto" style="margin:inherit;margin-bottom:10px"><img src="'.$config->baseURL. '/public/images/user/' . $avatar . '"/></div>';
						
						?>
						<input type="file" class="file" name="avatar">
							<?php if (!empty($form_errors['avatar'])) echo '<small style="display:block" class="alert alert-danger mb-0">' . $form_errors['avatar'] . '</small>'?>
						<small class="small" style="display:block">Maksimal 300Kb, Minimal 100px x 100px, Tipe file: .JPG, .JPEG, .PNG</small>
						<div class="upload-img-thumb mb-2"><span class="img-prop"></span></div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Username</label>
					<div class="col-sm-8 col-md-6 col-lg-4">
						<input class="form-control" type="text" name="username" readonly="readonly" class="disabled" value="<?=set_value('username', $username)?>" placeholder="" required="required"/>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Nama</label>
					<div class="col-sm-8 col-md-6 col-lg-4">
						<input class="form-control" type="text" name="nama" value="<?=set_value('nama', $nama)?>" placeholder="" required="required"/>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Email</label>
					<div class="col-sm-8 col-md-6 col-lg-4">
						<input class="form-control" type="text" name="email" value="<?=set_value('email', $email)?>" placeholder="" required="required"/>
						<input type="hidden" name="email_lama" value="<?=set_value('email_lama', $email)?>" />
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-form-label">Role</label>
					<div class="col-sm-8 col-md-6 col-lg-4">
						<?php
						foreach ($role as $key => $val) {
							$options[$val['id_role']] = $val['judul_role'];
						}
						echo options(['name' => 'id_role'], $options, set_value('id_role', $id_role));
						?>
					</div>
				</div>
				<div class="form-group row mb-0">
					<div class="col-sm-8">
						<button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
						<input type="hidden" name="id" value="<?=$request->getGet('id')?>"/>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>