<?php
global $wpdb;
?><h1>Ayarlar</h1><?php
$st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE name = 'coefficient' ", ARRAY_A);
foreach ($st as $key) {
	$value = $key['value']
?>
	<h3>Katsayı</h3>
	<form method="POST">
		<input type="number"  step="0.01" id="coeval" name="value" value="<?php echo $value; ?>">
		<button name="degistir" id="degistir">Değiştir</button>
	</form>
	<p id="message"></p>
<?php

}

$st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE name = 'limit' ", ARRAY_A);
foreach ($st as $key) {
	$value = $key['value']
?>
	<h3>Limit</h3>
	<form method="POST">
		<input type="number"  step="0.01" id="limit" name="limit" value="<?php echo $value; ?>">
		<button name="degistirL" id="degistirL">Değiştir</button>
	</form>
	<p id="messageL"></p>
<?php

}

if (isset($_POST['degistir'])) {
	$value = $_POST['value'];

	$wpdb->query("UPDATE {$wpdb->prefix}sc_options SET `value` = '$value' WHERE {$wpdb->prefix}sc_options.`name` = 'coefficient'");
	$st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE name = 'coefficient' ", ARRAY_A);
foreach ($st as $key) {
	$value = $key['value']
?>
	<script>
		document.getElementById("coeval").value = <?php echo $value ?>;
		document.getElementById("message").innerText = 'Katsayı değeri ' + <?php echo $value ?> + ' olarak değiştirildi';
	</script>

<?php

}
}


if (isset($_POST['degistirL'])) {
	$value = $_POST['limit'];

	$wpdb->query("UPDATE {$wpdb->prefix}sc_options SET `value` = '$value' WHERE {$wpdb->prefix}sc_options.`name` = 'limit'");
	$st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE name = 'limit' ", ARRAY_A);
foreach ($st as $key) {
	$value = $key['value']
?>
	<script>
		document.getElementById("limit").value = <?php echo $value ?>;
		document.getElementById("messageL").innerText = 'Limit değeri ' + <?php echo $value ?> + ' olarak değiştirildi';
	</script>

<?php

}
}
