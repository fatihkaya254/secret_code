<?php 
global $wpdb;

		?>

		<form method="POST">
			<input type="hidden" name="smsid" value="<?php echo $smsid; ?>">
			<input type="text" name="phonenumber" value="<?php echo $number; ?>">
			<button name="degistir" id="degistir">Değiştir</button>
		</form>

		<?php 