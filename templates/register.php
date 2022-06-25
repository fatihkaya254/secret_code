<?php

function registration_form($username, $password, $email, $first_name, $last_name, $secret_code)
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
    <label for="username">Kullanıcı Adı <strong>*</strong></label><br>
    <input required minlength="5" type="text" name="username" value="' . (isset($_POST['username']) ? $username : null) . '">
    </div>
     
    <div>
    <label for="password">Parola <strong>*</strong></label><br>
    <input required minlength="5" type="password" name="password" value="' . (isset($_POST['password']) ? $password : null) . '">
    </div>
     
    <div>
    <label for="email">E-posta <strong>*</strong></label><br>
    <input required type="text" name="email" value="' . (isset($_POST['email']) ? $email : null) . '">
    </div>
     
    <div>
    <label for="fname">Ad</label><br>
    <input type="text" name="fname" value="' . (isset($_POST['fname']) ? $first_name : null) . '">
    </div>
     
    <div>
    <label for="lname">Soyad</label><br>
    <input type="text" name="lname" value="' . (isset($_POST['lname']) ? $last_name : null) . '">
    </div>

    <div>
    <label for="scode">Özel Kod <strong>*</strong></label><br>
    <input required minlength="12" maxlength="12" type="text" name="scode" value="' . (isset($_POST['scode']) ? $secret_code : null) . '">
    </div>
    <input type="submit" name="submit" value="Register"/>
    </form>
    ';
}

function registration_validation($username, $password, $email, $first_name, $last_name, $secret_code)
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    if (empty($username) || empty($password) || empty($email)) {
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
    if (!is_email($email)) {
        $reg_errors->add('email_invalid', 'E-posta geçersiz');
    }
    if (email_exists($email)) {
        $reg_errors->add('email', 'E-posta adresi zaten kullanılıyor');
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
    global $reg_errors, $username, $password, $email, $first_name, $last_name, $secret_code, $wpdb;
    if (1 > count($reg_errors->get_error_messages())) {
        $userdata = array(
            'user_login'    =>   $username,
            'user_email'    =>   $email,
            'user_pass'     =>   $password,
            'first_name'    =>   $first_name,
            'last_name'     =>   $last_name
        );
        $user = wp_insert_user($userdata);
        $wpdb->query("UPDATE {$wpdb->prefix}sc_secret_codes SET  `user_id` = '$user' WHERE `secret_code`= '$secret_code'");
        echo 'Kayıt tamamlandı. <a href="' . get_site_url() . '/wp-login.php">Giriş Sayfası</a>.';
    }
}

function custom_registration_function()
{
    if (isset($_POST['submit'])) {
        registration_validation(
            $_POST['username'],
            $_POST['password'],
            $_POST['email'],
            $_POST['fname'],
            $_POST['lname'],
            $_POST['scode']
        );

        // sanitize user form input
        global $username, $password, $email, $first_name, $last_name, $secret_code, $wpdb, $reg_errors;
        $username   =   sanitize_user($_POST['username']);
        $password   =   esc_attr($_POST['password']);
        $email      =   sanitize_email($_POST['email']);
        $first_name =   sanitize_text_field($_POST['fname']);
        $last_name  =   sanitize_text_field($_POST['lname']);
        $secret_code  =   sanitize_text_field($_POST['scode']);

        $query = "SELECT secret_code FROM {$wpdb->prefix}sc_secret_codes WHERE `secret_code` = '$secret_code' AND `user_id` is NULL";
        $newline = $wpdb->get_results($query, ARRAY_A);
            if (count($newline)) {
                // call @function complete_registration to create the user
                // only when no WP_error is found
                complete_registration(
                    $username,
                    $password,
                    $email,
                    $first_name,
                    $last_name,
                    $secret_code
                );
            }
            else{
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
    if (!isset($email)) $email = '';
    if (!isset($first_name)) $first_name = '';
    if (!isset($last_name)) $last_name = '';
    if (!isset($secret_code)) $secret_code = '';
    registration_form(
        $username,
        $password,
        $email,
        $first_name,
        $last_name,
        $secret_code
    );
}

echo custom_registration_function();
