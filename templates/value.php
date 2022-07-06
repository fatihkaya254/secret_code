<?php
require_once plugin_dir_path(__FILE__) . "../Controllers/Limit.php";
global $wpdb;
$userId = get_current_user_id();
$lim = new Limit;
$req = $lim->user_requests($userId);
if ($req['date'] != '') {
	?>
		<h5>Açık Talebiniz Bulunmaktadır</h5>
		<p>Talep Tarihi: <?php echo $req['date'] ?></p>
		<p>Durum: <?php echo $req['answer'] ?></p>
	<?php 
}
?>
<div>
	<h5>Değer</h5>
	<p><?php echo $lim->user_score($userId) ?></p>
	<h5>Limit</h5>
	<p><?php echo $lim->limit ?></p>
	<?php 

	if ((float)$lim->limit < (float)$lim->user_score($userId) && $req['date'] == '') {
		?>
			<p>Limitin Üzerindesiniz</p>
				<form method="POST">
					<button name="interrupt" id="interrupt">Kesme Talebi Oluştur</button>
				</form>
			<p id="response"></p>
		<?php 
	}
	?>
</div>

<?php
if (isset($_POST['interrupt'])) {
	$message = $lim->create_requests($userId);
	?>
	<script>
        document.getElementById("response").innerText = "<?php echo $message ?>";
	</script>
	<?php
}
