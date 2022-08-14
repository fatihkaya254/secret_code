<?php
global $wpdb;
require_once plugin_dir_path(__FILE__) . "../Controllers/Limit.php";
$lim = new Limit;
?><h1>Ayarlar</h1><?php
?>
	<h3>Katsayı</h3>
	<form method="POST">
		<input type="number"  step="0.0001" id="coeval" name="value" value="<?php echo $lim->coe; ?>">
		<button name="degistir" id="degistir">Değiştir</button>
	</form>
	<p id="message"></p>

	<h3>Limit</h3>
	<form method="POST">
		<input type="number"  step="0.01" id="limit" name="limit" value="<?php echo $lim->limit; ?>">
		<button name="degistirL" id="degistirL">Değiştir</button>
	</form>
	<p id="messageL"></p>

	<h3>Duyuru</h3>
	<form method="POST" id="noticeform">
		<textarea  maxlength="255" rows="4" cols="150" id="notice" name="notice" form="noticeform"><?php echo $lim->notice; ?></textarea>
		<br>
		<button name="degistirN" id="degistirN">Değiştir</button>
	</form>
	<p id="messageN"></p>
<?php

if (isset($_POST['degistir'])) {
	$coe = $lim->change_value('coefficient', $_POST['value']);
?>
	<script>
		document.getElementById("coeval").value = <?php echo $coe ?>;
		document.getElementById("message").innerText = 'Katsayı değeri ' + <?php echo $coe ?> + ' olarak değiştirildi';
	</script>
<?php
}

if (isset($_POST['degistirL'])) {
	$limit = $lim->change_value('limit', $_POST['limit']);
?>
	<script>
		document.getElementById("limit").value = <?php echo $limit ?>;
		document.getElementById("messageL").innerText = 'Katsayı değeri ' + <?php echo $limit ?> + ' olarak değiştirildi';
	</script>
<?php
}


if (isset($_POST['degistirN'])) {
	$notice = $lim->change_value('notice', $_POST['notice']);
	?>
	<script>
		document.getElementById("messageN").innerText = 'Duyuru; "' + `<?php echo $notice ?>` + '" olarak değiştirildi';
		document.getElementById("notice").value = `<?php echo $notice ?>`;
	</script>
<?php
}
