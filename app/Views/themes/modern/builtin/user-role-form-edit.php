<?php
helper('html');

$prefix_id = 'role_';
		
$checked = [];
foreach ($user_role as $row) {
	$checked[] = $prefix_id . $row['id_role'];
}

$checkbox[] = ['id' => 'check-all', 'name' => 'check_all', 'label' =>'Check All / Uncheck All'];
$check_all_checked = $checked ? ['check_all'] : [];
echo checkbox($checkbox, $check_all_checked);

echo '<hr class="mt-1 mb-2"/>';
echo '<form method="post" id="check-all-wrapper" action="">';
$checkbox = [];
foreach ($role as $val) {
	$checkbox[] = ['id' => $val['id_role'], 'name' => $prefix_id . $val['id_role'], 'label' => $val['judul_role']];
}

echo checkbox($checkbox, $checked);
echo '</form>';