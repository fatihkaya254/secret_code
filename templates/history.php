<?php
require_once plugin_dir_path(__FILE__) . "../Controllers/Interrupt.php";
$userId = get_current_user_id();
$ir = new Interrupt;
$history = $ir->get_history($userId);
foreach($history as $key){
    $date = $key['date'];
    $value = $key['value'];
    ?>
        <p> <?php echo $date.' - '. $value ?></p>
    <?php
}
?>