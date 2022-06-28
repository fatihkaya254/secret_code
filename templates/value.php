<?php
global $wpdb;
$coe = -1;
$val = -1;
$st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE name = 'coefficient' ", ARRAY_A);
foreach ($st as $key) {
	$coe = $key['value'];
}
$userId = get_current_user_id();
$query = "SELECT `value` FROM {$wpdb->prefix}sc_secret_codes WHERE  `user_id` = '$userId'";
$st = $wpdb->get_results($query, ARRAY_A);
foreach ($st as $key) {
	$val = $key['value'];
}
if($val != -1){
echo $coe*$val;	
}
