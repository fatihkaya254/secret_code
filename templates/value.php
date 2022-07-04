<?php
global $wpdb;
$userId = get_current_user_id();
$coe = -1;
$limit = -1;
$val = -1;
$lt = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE name = 'limit' ", ARRAY_A);
foreach ($lt as $key) {
	$limit = $key['value'];
}
$st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE name = 'coefficient' ", ARRAY_A);
foreach ($st as $key) {
	$coe = $key['value'];
}
$rt = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_interrupt_requests WHERE `user_id` = '$userId' AND (`answer` = 'inprocess' OR `answer` = 'undereview') ", ARRAY_A);
foreach ($rt as $key) {
	$date = $key['date'];
	$answer = $key['answer'];
}
$query = "SELECT `value` FROM {$wpdb->prefix}sc_secret_codes WHERE  `user_id` = '$userId'";
$st = $wpdb->get_results($query, ARRAY_A);
foreach ($st as $key) {
	$val = $key['value'];
}
if($val != -1){
$myScore = $coe*$val;
if (count($rt)) {
	?>
		<h5>Açık Talebiniz Bulunmaktadır</h5>
		<p>Talep Tarihi: <?php echo $date ?></p>
		<p>Durum: <?php echo $answer ?></p>
	<?php 
}
?>
<div>
	<h5>Değer</h5>
	<p><?php echo $myScore ?></p>
	<h5>Limit</h5>
	<p><?php echo $limit ?></p>
	<?php 

	if ((float)$limit < (float)$myScore && !count($rt)) {
		?>
			<p>Limitin Üzerindesiniz</p>
				<form method="POST">
					<button name="interrupt" id="interrupt">Kesme Talebi Oluştur</button>
				</form>
			<p id="message"></p>
		<?php 
	}

	?>
</div>

<?php
if (isset($_POST['interrupt'])) {

$coe = -1;
$limit = -1;
$val = -1;
$lt = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE name = 'limit' ", ARRAY_A);
foreach ($lt as $key) {
	$limit = $key['value'];
}
$st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE name = 'coefficient' ", ARRAY_A);
foreach ($st as $key) {
	$coe = $key['value'];
}
$query = "SELECT `value` FROM {$wpdb->prefix}sc_secret_codes WHERE  `user_id` = '$userId'";
$st = $wpdb->get_results($query, ARRAY_A);
foreach ($st as $key) {
	$val = $key['value'];
}
if($val != -1){
	$myScore = $coe*$val;

}
$rt = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_interrupt_requests WHERE `user_id` = '$userId' AND (`answer` = 'inprocess' OR `answer` = 'undereview') ", ARRAY_A);
if ((float)$limit < (float)$myScore  && !count($rt)) {
	$wpdb->query("INSERT INTO {$wpdb->prefix}sc_interrupt_requests (`user_id`, `value`, `current_limit`) VALUES ('$userId', '$myScore', '$limit')");
	?>
	<script>
        document.getElementById("message").innerText = 'Talep Alındı';
	</script>
	<?php
}
}
}
