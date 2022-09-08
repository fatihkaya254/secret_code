<?php

function registration_form($username, $password, $phone, $adress, $secret_code)
{
    echo '
    <style>
    div {
      margin-bottom:2px;
    }
     
    input{
        margin-bottom:4px;
    }
    </style>
    ';

    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
    <div>
    <label for="username">Operator <strong>*</strong></label><br>
    <input required minlength="5" type="text" name="username" value="' . (isset($_POST['username']) ? $username : null) . '">
    </div>
     
    <div>
    <label for="password">Parola <strong>*</strong></label><br>
    <input required minlength="5" type="password" name="password" value="' . (isset($_POST['password']) ? $password : null) . '">
    </div>

    <div>
    <label for="phone">Telefon <strong>*</strong></label><br>
    <input required minlength="10" maxlength="11" id="phone" type="text" name="phone" value="' . (isset($_POST['phone']) ? $phone : null) . '">
    </div>

    <div>
    <label for="adress">Adres <strong>*</strong></label><br>
    <input maxlength="50" type="text" name="adress" value="' . (isset($_POST['adress']) ? $adress : null) . '">
    </div>

    <div>
    <label for="scode">Özel Kod <strong>*</strong></label><br>
    <input required minlength="12" maxlength="12" type="text" name="scode" value="' . (isset($_POST['scode']) ? $secret_code : null) . '">
    </div>
    <input type="submit" name="submit" value="Register"/>
    </form>
    <script>
    function setInputFilter(textbox, inputFilter, errMsg) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop", "focusout"].forEach(function(event) {
          textbox.addEventListener(event, function(e) {
            if (inputFilter(this.value)) {
              // Accepted value
              if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
                this.classList.remove("input-error");
                this.setCustomValidity("");
              }
              this.oldValue = this.value;
              this.oldSelectionStart = this.selectionStart;
              this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
              // Rejected value - restore the previous one
              this.classList.add("input-error");
              this.setCustomValidity(errMsg);
              this.reportValidity();
              this.value = this.oldValue;
              this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
              // Rejected value - nothing to restore
              this.value = "";
            }
          });
        });
      }
      setInputFilter(document.getElementById("phone"), function(value) {
        return /^-?\d*$/.test(value); }, "Sadece Sayısal Değerler Kabul Edilir");
</script>
    ';
}
function registration_validation($username, $password, $phone, $adress, $secret_code)
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    if (empty($username) || empty($password)) {
        $reg_errors->add('field', 'Lütfen tüm yıldızlı alanları doldurunuz');
    }
    if (5 > strlen($username)) {
        $reg_errors->add('username_length', 'Kullanıcı adı çok kısa. En az 5 karakter gerekiyor');
    }
    if (username_exists($username))
        $reg_errors->add('user_name', 'Üzgünüm, bu kullanıcı adını başka bir üye kullanıyor!');
    if (!validate_username($username)) {
        $reg_errors->add('username_invalid', 'Üzgünüm, girdiğiniz kullanıcı adı geçersiz');
    }
    if (5 > strlen($password)) {
        $reg_errors->add('password', 'Parola 5 karakterden uzun olmalı');
    }

    if (is_wp_error($reg_errors)) {

        foreach ($reg_errors->get_error_messages() as $error) {

            echo '<div>';
            echo '<strong>HATA</strong>: ';
            echo $error . '<br/>';
            echo '</div>';
        }
    }
}
function complete_registration()
{
    global $reg_errors, $username, $password, $phone, $adress, $secret_code, $wpdb;
    if (1 > count($reg_errors->get_error_messages())) {
        $userdata = array(
            'user_login'    =>   $username,
            'user_pass'     =>   $password,
        );
        $user = wp_insert_user($userdata);
        $wpdb->query("UPDATE {$wpdb->prefix}sc_secret_codes SET  `user_id` = '$user' WHERE `secret_code`= '$secret_code'");
        $wpdb->query("INSERT INTO `{$wpdb->prefix}sc_users` (`phone`, `adress`, `user_id`) VALUES ('$phone', '$adress', '$user')");
      echo 'Kayıt tamamlandı. <a href="' . get_site_url() . '/wp-login.php">Giriş Sayfası</a>.';
        auth_redirect();
    }
}

function custom_registration_function()
{
    if (isset($_POST['submit'])) {
        registration_validation(
            $_POST['username'],
            $_POST['password'],
            $_POST['phone'],
            $_POST['adress'],
            $_POST['scode']
        );

        // sanitize user form input
        global $username, $password, $phone, $adress, $secret_code, $wpdb, $reg_errors;
        $username   =   sanitize_user($_POST['username']);
        $password   =   esc_attr($_POST['password']);
        $phone  =   sanitize_text_field($_POST['phone']);
        $adress  =   sanitize_text_field($_POST['adress']);
        $secret_code  =   sanitize_text_field($_POST['scode']);
        
        $query = "SELECT secret_code FROM {$wpdb->prefix}sc_secret_codes WHERE `secret_code` = '$secret_code' AND `user_id` is NULL";
        $newline = $wpdb->get_results($query, ARRAY_A);
            if (count($newline)) {
                // call @function complete_registration to create the user
                // only when no WP_error is found
                complete_registration(
                    $username,
                    $password,
                    $phone,
                    $adress,
                    $secret_code
                );
            }
            else if(!count($newline)){
                $reg_errors->add('scode', 'Kod geçersiz veya başka bir kullanıcıya tanımlanmış.');
                foreach ($reg_errors->get_error_messages() as $error) {

                    echo '<div>';
                    echo '<strong>HATA</strong>: ';
                    echo $error . '<br/>';
                    echo '</div>';
                }
            } 
    }
    if (!isset($username)) $username = '';
    if (!isset($password)) $password = '';
    if (!isset($phone)) $phone = '';
    if (!isset($adress)) $adress = '';
    if (!isset($secret_code)) $secret_code = '';
    registration_form(
        $username,
        $password,
        $phone,
        $adress,
        $secret_code
    );
}

echo custom_registration_function();

?>
