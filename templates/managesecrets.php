<?php
global $wpdb;
?><h1>Kodlar</h1><?php
                    $query = "SELECT {$wpdb->prefix}sc_secret_codes.id secretID, secret_code, value, user_email, {$wpdb->prefix}users.ID userID ";
                    $query .= "FROM {$wpdb->prefix}sc_secret_codes";
                    $query .= " LEFT JOIN {$wpdb->prefix}users ON {$wpdb->prefix}sc_secret_codes.`user_id` = {$wpdb->prefix}users.`ID`";
                    $st = $wpdb->get_results($query, ARRAY_A);
                    ?>
<div class="sc_container">
    <form class="sc_title_row" method="post">
        <input type="text" name="secretId" id="secretId" style="display: none;" placeholder="ID" readonly>
        <input type="text" name="code" id="code" required="true" placeholder="Kod" minlength="12" maxlength="12">
        <input type="number" name="value" id="value" required="true" placeholder="Değer">
        <input type="email" name="email" id="email" style="display: none;" placeholder="Kullanıcı E-posta">
        <button name="add" id="add">Ekle</button>
        <button type="submit" name="change" style="display: none;" id="change">Düzenle</button>
        <div class="sc_cancel" name="cancel" style="display: none;" id="cancel" onclick="cancel()"> x </div>
    </form>
    <div class="sc_title_row">
        <div class="sc_title_cell">
            <span>ID</span>
        </div>
        <div class="sc_title_cell">
            <span>Kod</span>
        </div>
        <div class="sc_title_cell">
            <span>Değer</span>
        </div>
        <div class="sc_title_cell">
            <span>Kullanıcı</span>
        </div>
    </div>
    <?php
    $lastId = 0;
    foreach ($st as $key) {
        $id = $key['secretID'];
        $lastId = $id;
        $secretCode = $key['secret_code'];
        $value = $key['value'];
        $mail = $key['user_email'];
    ?>
        <div class="sc_row" id="row<?php echo $id ?>" onclick="rowClick(`<?php echo $id ?>`, `<?php echo $secretCode ?>`,`<?php echo $value ?>`,`<?php echo $mail ?>`)">
            <div class="sc_cell">
                <span id="cellId<?php echo $id ?>"><?php echo $id ?></span>
            </div>
            <div class="sc_cell">
                <span id="cellCode<?php echo $id ?>"><?php echo $secretCode ?></span>
            </div>
            <div class="sc_cell">
                <span id="cellvalue<?php echo $id ?>"><?php echo $value ?></span>
            </div>
            <div class="sc_cell">
                <span id="cellMail<?php echo $id ?>"><?php echo $mail ?></span>
            </div>
        </div>
        <?php

    }
    if (isset($_POST['add'])) {
        $secretCode = $_POST['code'];
        $value = $_POST['value'];

        $wpdb->query("INSERT INTO {$wpdb->prefix}sc_secret_codes (`secret_code`, `value`) VALUES ('$secretCode', '$value')");
        $query = "SELECT {$wpdb->prefix}sc_secret_codes.id secretID, secret_code, value, user_email, {$wpdb->prefix}users.ID userID ";
        $query .= "FROM {$wpdb->prefix}sc_secret_codes";
        $query .= " LEFT JOIN {$wpdb->prefix}users ON {$wpdb->prefix}sc_secret_codes.`user_id` = {$wpdb->prefix}users.`ID` ORDER BY {$wpdb->prefix}sc_secret_codes.`id` DESC LIMIT 1";
        $newline = $wpdb->get_results($query, ARRAY_A);
        foreach ($newline as $key) {
            $id = $key['secretID'];
            $secretCode = $key['secret_code'];
            $value = $key['value'];
            $mail = '';
            $mail = $key['user_email'];
            if ($id != $lastId) {
        ?>
                <div class="sc_row" id="row<?php echo $id ?>" onclick="rowClick(`<?php echo $id ?>`, `<?php echo $secretCode ?>`,`<?php echo $value ?>`,`<?php echo $mail ?>`)">
                    <div class="sc_cell">
                        <span id="cellId<?php echo $id ?>"><?php echo $id ?></span>
                    </div>
                    <div class="sc_cell">
                        <span id="cellCode<?php echo $id ?>"><?php echo $secretCode ?></span>
                    </div>
                    <div class="sc_cell">
                        <span id="cellvalue<?php echo $id ?>"><?php echo $value ?></span>
                    </div>
                    <div class="sc_cell">
                        <span id="cellMail<?php echo $id ?>"><?php echo $mail ?></span>
                    </div>
                </div>
            <?php
            }
        }
    }
    if (isset($_POST['change'])) {
        $secretCode = $_POST['code'];
        $value = $_POST['value'];
        $id = $_POST['secretId'];
        $userId = NULL;
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $query = ("SELECT `ID` FROM {$wpdb->prefix}users WHERE `user_email`= '$email'");
            $who = $wpdb->get_results($query, ARRAY_A);
            foreach ($who as $k) {
                $userId = $k['ID'];
            }
        }
         if(!$userId || $userId == '' || $userId == 0 ) $wpdb->query("UPDATE {$wpdb->prefix}sc_secret_codes SET `value`= '$value', `secret_code` = '$secretCode', `user_id` = NULL WHERE `id`= $id");
         else $wpdb->query("UPDATE {$wpdb->prefix}sc_secret_codes SET `value`= '$value', `secret_code` = '$secretCode', `user_id` = '$userId' WHERE `id`= $id");
        $query = "SELECT {$wpdb->prefix}sc_secret_codes.id secretID, secret_code, value, user_email, {$wpdb->prefix}users.ID userID ";
        $query .= "FROM {$wpdb->prefix}sc_secret_codes";
        $query .= " LEFT JOIN {$wpdb->prefix}users ON {$wpdb->prefix}sc_secret_codes.`user_id` = {$wpdb->prefix}users.`ID` WHERE {$wpdb->prefix}sc_secret_codes.`id` = $id";
        $newline = $wpdb->get_results($query, ARRAY_A);
        foreach ($newline as $key) {
            $id = $key['secretID'];
            $secretCode = $key['secret_code'];
            $value = $key['value'];
            $mail = '';
            $mail = $key['user_email'];
            if ($id != $lastId) {
            ?>
                <script>
                    document.getElementById("cellId<?php echo $id ?>").innerText = '<?php echo $id ?>';
                    document.getElementById("cellCode<?php echo $id ?>").innerText = '<?php echo $secretCode ?>';
                    document.getElementById("cellvalue<?php echo $id ?>").innerText = '<?php echo $value ?>';
                    document.getElementById("cellMail<?php echo $id ?>").innerText = '<?php echo $mail ?>';

                </script>
    <?php
            }
        }
    }
    ?>
</div>
<style>
    .sc_container {
        display: flex;
        flex-direction: column;
        width: 70vw;
        max-width: 820px;
        padding: 12px;
        background-color: rgb(229, 229, 234);
        border-radius: 1em;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }

    .sc_title_row {
        display: flex;
        flex-direction: row;
        gap: 12px;
        background-color: rgb(229, 229, 234);
        border-radius: 1em;
        padding: 6px;
    }

    .sc_row {
        display: flex;
        flex-direction: row;
        gap: 12px;
        background-color: rgb(229, 229, 234);
        cursor: pointer;
        border-radius: 1em;
        padding: 6px;
    }

    .sc_row:hover {
        background-color: rgb(209, 209, 214);
    }

    .sc_title_cell {
        border-radius: 1em;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 25%;
        height: 18px;
        overflow-x: initial;
        overflow-y: hidden;
        padding: 3px;
    }

    .sc_cell {
        cursor: pointer;
        border-radius: 1em;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 25%;
        height: 18px;
        overflow-x: initial;
        overflow-y: hidden;
        padding: 3px;
        background-color: rgb(242, 242, 247);
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }

    .sc_cancel {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 12px;
        width: 12px;
        color: red;
        font-weight: bold;
    }

    .sc_cell:hover {
        background-color: rgb(232, 232, 237);
    }

    input[type=text],
    input[type=number],
    input[type=email] {
        outline: none;
        padding: 6px;
        border-radius: 1em;
        width: 25%;
        background-color: rgb(242, 242, 247);
        height: 18px;
        border: none;
    }
</style>
<script>
    function rowClick(id, sc, v, m) {
        document.getElementById("secretId").value = id;
        document.getElementById("code").value = sc;
        document.getElementById("value").value = v;
        document.getElementById("email").value = m;
        document.getElementById("email").style.display = "inline";
        document.getElementById("secretId").style.display = "inline";
        document.getElementById("add").style.display = "none";
        document.getElementById("cancel").style.display = "inline";
        document.getElementById("change").style.display = "inline";

    }

    function cancel() {
        document.getElementById("secretId").value = '';
        document.getElementById("code").value = '';
        document.getElementById("value").value = '0';
        document.getElementById("email").value = '';
        document.getElementById("email").style.display = "none";
        document.getElementById("secretId").style.display = "none";
        document.getElementById("add").style.display = "inline";
        document.getElementById("cancel").style.display = "none";
        document.getElementById("change").style.display = "none";
    }
</script>
<?php
