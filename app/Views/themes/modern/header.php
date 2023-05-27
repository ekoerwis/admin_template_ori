<?php 
/**
*	App Name	: Admin Template Dashboard Codeigniter 4
*	Developed by: Agus Prawoto Hadi
*	Website		: www.jagowebdev.com
*	Year		: 2020
*/
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<title><?=$current_module['judul_module']?> | <?=$settingWeb->judul_web?></title>
<meta name="descrition" content="<?=$current_module['deskripsi']?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?=$config->baseURL . '/public/images/favicon.png?r='.time()?>" />
<link rel="stylesheet" type="text/css" href="<?=$config->baseURL . '/public/vendors/font-awesome/css/all.css'?>"/>
<link rel="stylesheet" type="text/css" href="<?=$config->baseURL . '/public/vendors/bootstrap/css/bootstrap.min.css?r='.time()?>"/>
<link rel="stylesheet" type="text/css" href="<?=$config->baseURL . '/public/vendors/datatables/datatables.min.css?r='.time()?>"/>
<link rel="stylesheet" type="text/css" href="<?=$config->baseURL . '/public/vendors/datatables/dist/css/dataTables.bootstrap4.min.css?r='.time()?>"/>
<link rel="stylesheet" type="text/css" href="<?=$config->baseURL . '/public/vendors/iconpicker/css/bulma-iconpicker.min.css?r='.time()?>"/>
<link rel="stylesheet" type="text/css" href="<?=$config->baseURL . '/public/themes/modern/builtin/css/bootstrap-custom.css?r=' . time()?>"/>
<link rel="stylesheet" type="text/css" href="<?=$config->baseURL . '/public/vendors/sweetalert2/sweetalert2.min.css?r='.time()?>"/>
<link rel="stylesheet" type="text/css" href="<?=$config->baseURL . '/public/vendors/overlayscrollbars/OverlayScrollbars.min.css?r='.time()?>"/>
<link rel="stylesheet" type="text/css" href="<?=$config->baseURL . 'public/themes/modern/builtin/css/site.css?r='.time()?>"/>

<link rel="stylesheet" id="style-switch" type="text/css" href="<?=$config->baseURL . 'public/themes/modern/builtin/css/color-schemes/'.$app_layout['color_scheme'].'.css?r='.time()?>"/>
<link rel="stylesheet" id="style-switch-sidebar" type="text/css" href="<?=$config->baseURL . 'public/themes/modern/builtin/css/color-schemes/'.$app_layout['sidebar_color'].'-sidebar.css?r='.time()?>"/>
<link rel="stylesheet" id="font-switch" type="text/css" href="<?=$config->baseURL . 'public/themes/modern/builtin/css/fonts/'.$app_layout['font_family'].'.css?r='.time()?>"/>
<link rel="stylesheet" id="font-size-switch" type="text/css" href="<?=$config->baseURL . 'public/themes/modern/builtin/css/fonts/font-size-'.$app_layout['font_size'].'.css?r='.time()?>"/>
<link rel="stylesheet" id="logo-background-color-switch" type="text/css" href="<?=$config->baseURL . 'public/themes/modern/builtin/css/color-schemes/'.$app_layout['logo_background_color'].'-logo-background.css?r='.time()?>"/>
<?php
if (@$styles) {
	foreach($styles as $file) {
		echo '<link rel="stylesheet" type="text/css" href="'.$file.'?r='.time().'"/>';
	}
}
?>
<script type="text/javascript">
	var base_url = "<?=$config->baseURL?>";
	var module_url = "<?=$module_url?>";
	var current_url = "<?=current_url()?>";
	var theme_url = "<?=$config->baseURL . '/public/themes/modern/builtin/'?>";
</script>
<script type="text/javascript" src="<?=$config->baseURL . '/public/vendors/jquery/jquery-3.4.1.js'?>"></script>
<script type="text/javascript" src="<?=$config->baseURL . '/public/vendors/bootstrap/js/bootstrap.min.js'?>"></script>
<script type="text/javascript" src="<?=$config->baseURL . '/public/vendors/bootbox/bootbox.min.js'?>"></script>
<!-- <script type="text/javascript" src="<?=$config->baseURL . '/public/vendors/zenscroll/zenscroll-min.js'?>"></script> -->
<script type="text/javascript" src="<?=$config->baseURL . '/public/vendors/iconpicker/js/bulma-iconpicker.min.js'?>"></script>
<script type="text/javascript" src="<?=$config->baseURL . '/public/vendors/sweetalert2/sweetalert2.min.js'?>"></script>
<script type="text/javascript" src="<?=$config->baseURL . '/public/vendors/overlayscrollbars/jquery.overlayScrollbars.min.js'?>"></script>
<script type="text/javascript" src="<?=$config->baseURL . '/public/vendors/datatables/datatables.min.js'?>"></script>
<script type="text/javascript" src="<?=$config->baseURL . '/public/themes/modern/builtin/js/functions.js?r='.time()?>"></script>
<script type="text/javascript" src="<?=$config->baseURL . '/public/themes/modern/builtin/js/site.js?r='.time()?>"></script>

<?php
if (@$scripts) {
	foreach($scripts as $file) {
		echo '<script type="text/javascript" src="'.$file.'?r='.time().'"></script>';
	}
}

$user = $_SESSION['user'];

?>
</head>
<body>
	<header class="nav-header">
		<div class="nav-header-logo pull-left">
			<a class="header-logo" href="<?=$config->baseURL?>" title="Jagowebdev">
				<img src="<?=$config->baseURL . '/public/images/' . $settingWeb->logo_app?>"/>
			</a>
		</div>
		<div class="pull-left nav-header-left">
			<ul class="nav-header">
				<li>
					<a href="#" id="mobile-menu-btn">
						<i class="fa fa-bars"></i>
					</a>
				</li>
			</ul>
		</div>
		<div class="pull-right mobile-menu-btn-right">
			<a href="#" id="mobile-menu-btn-right">
				<i class="fa fa-ellipsis-h"></i>
			</a>
		</div>
		<div class="pull-right nav-header nav-header-right">
			
			<ul>
				<li><a class="icon-link" href="<?=$config->baseURL?>builtin/setting"><i class="fas fa-cog"></i></a></li>
				
				<li>
					<?php $img_url = !empty($user['avatar']) ? $config->baseURL . '/public/images/user/' . $user['avatar'] : $config->baseURL . '/public/images/user/default.png';
					$account_link = $config->baseURL . 'user';
					?>
					<a class="profile-btn" href="<?=$account_link?>"><img src="<?=$img_url?>" alt="user_img"></a>
					<div class="account-menu-container">
						<?php
						if ($isloggedin) { 
							?>
							<ul class="account-menu">
								<li class="account-img-profile">
									<div class="avatar-profile">
										<img src="<?=$img_url?>" alt="user_img">
									</div>
									<div class="card-content">
									<p><?=strtoupper($user['nama'])?></p>
									<p><small>Email: <?=$user['email']?></small></p>
									</div>
								</li>
								<li><a href="<?=$config->baseURL?>builtin/user/edit-password?id=<?=$user['id_user']?>">Change Password</a></li>
								<li><a href="<?=$config->baseURL?>login/logout">Logout</a></li>
							</ul>
						<?php } else { ?>
							<div class="float-login">
							<form method="post" action="<?=$config->baseURL?>login">
								<input type="email" name="email" value="" placeholder="Email" required>
								<input type="password" name="password" value="" placeholder="Password" required>
								<div class="checkbox">
									<label style="font-weight:normal"><input name="remember" value="1" type="checkbox">&nbsp;&nbsp;Remember me</label>
								</div>
								<button type="submit"  style="width:100%" class="btn btn-success" name="submit">Submit</button>
								<?php
								$form_token = $auth->generateFormToken('login_form_token_header');
								?>
								<input type="hidden" name="form_token" value="<?=$form_token?>"/>
								<input type="hidden" name="login_form_header" value="login_form_header"/>
							</form>
							<a href="<?=$config->baseURL . 'recovery'?>">Lupa password?</a>
							</div>
						<?php }?>
					</div>
				</li>
			</ul>
		
		</div>
	</header>
	<div class="site-content">
		<?php
		
		// MENU - SIDEBAR
		$list_menu = menu_list($menu);
	
		?>
		<div class="sidebar">
			<nav>
			<?php
			echo build_menu($current_module, $list_menu);
			?>
			</nav>
		</div>
		<div class="content">
		<?=!empty($breadcrumb) ? breadcrumb($breadcrumb) : ''?>
		<div class="content-wrapper">
		