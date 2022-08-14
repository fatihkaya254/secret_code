<?php

/**
 * @package SecretCode
 */

class Activate
{
  public static function __activate()
  {
    global $wpdb;
    $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($wpdb->prefix . 'sc_secret_codes'));

    if ($wpdb->get_var($query) !=  $wpdb->prefix . 'sc_secret_codes') {
      $wpdb->query("
        CREATE TABLE `{$wpdb->prefix}sc_secret_codes` (
          `id` bigint(20) NOT NULL,
          `secret_code` varchar(12) COLLATE utf8_turkish_ci NOT NULL,
          `value` int(11) NOT NULL,
          `user_id` bigint(20)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
      ");

      $wpdb->query("
      ALTER TABLE `{$wpdb->prefix}sc_secret_codes`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `secret_code` (`secret_code`),
      ADD UNIQUE KEY `user_id` (`user_id`);
    ");
      $wpdb->query("
      ALTER TABLE `{$wpdb->prefix}sc_secret_codes`
      MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
    ");

      $wpdb->query("

  INSERT INTO `{$wpdb->prefix}sc_secret_codes` (`secret_code`, `value`) VALUES
  ('AW453FG', 44),
  ('ADFE344', 55)
  ");
    }
    $query2 = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($wpdb->prefix . 'sc_options'));
    if ($wpdb->get_var($query2) !=  $wpdb->prefix . 'sc_options') {
      $wpdb->query("
        CREATE TABLE `{$wpdb->prefix}sc_options` (
          `id` bigint(20) NOT NULL,
          `name` varchar(40) COLLATE utf8_turkish_ci NOT NULL,
          `value` varchar(255) COLLATE utf8_turkish_ci NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
      ");

      $wpdb->query("
      ALTER TABLE `{$wpdb->prefix}sc_options`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `name` (`name`);
    ");
      $wpdb->query("
      ALTER TABLE `{$wpdb->prefix}sc_options`
      MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
    ");

      $wpdb->query("INSERT INTO `{$wpdb->prefix}sc_options` (`name`, `value`) VALUES ('coefficient', '1')");
      $wpdb->query("INSERT INTO `{$wpdb->prefix}sc_options` (`name`, `value`) VALUES ('limit', '900')");
      $wpdb->query("INSERT INTO `{$wpdb->prefix}sc_options` (`name`, `value`) VALUES ('notice', 'Duyuruları buradan takip edebilirsiniz')");
    }
    $query3 = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($wpdb->prefix . 'sc_users'));
    if ($wpdb->get_var($query3) !=  $wpdb->prefix . 'sc_users') {
      $wpdb->query("
        CREATE TABLE `{$wpdb->prefix}sc_users` (
          `id` bigint(20) NOT NULL,
          `phone` varchar(13),
          `adress` varchar(50) COLLATE utf8_turkish_ci,
          `user_id` bigint(20)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
      ");

      $wpdb->query("
      ALTER TABLE `{$wpdb->prefix}sc_users`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `phone` (`phone`),
      ADD UNIQUE KEY `user_id` (`user_id`);
      ");
      $wpdb->query("
      ALTER TABLE `{$wpdb->prefix}sc_users`
      MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
    ");
    }
    $query4 = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($wpdb->prefix . 'sc_interrupt_requests'));
    if ($wpdb->get_var($query4) !=  $wpdb->prefix . 'sc_interrupt_requests') {
      $wpdb->query("    
        CREATE TABLE `{$wpdb->prefix}sc_interrupt_requests` ( 
          `id` BIGINT(20) NOT NULL AUTO_INCREMENT , 
          `user_id` BIGINT(20) NOT NULL , 
          `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
          `value` INT(11) NOT NULL , 
          `current_limit` VARCHAR(40) NOT NULL , 
          `answer` ENUM('inprocess','undereview','accepted','denied') NOT NULL DEFAULT 'inprocess' , 
          PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
      ");
    }
    $query5 = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($wpdb->prefix . 'sc_user_history'));
    if ($wpdb->get_var($query5) !=  $wpdb->prefix . 'sc_user_history') {
      $wpdb->query("    
        CREATE TABLE `{$wpdb->prefix}sc_user_history` ( 
          `id` BIGINT(20) NOT NULL AUTO_INCREMENT , 
          `user_id` BIGINT(20) NOT NULL , 
          `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
          `value` INT(11) NOT NULL , 
          PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
      ");
    }
    flush_rewrite_rules();
  }
}



