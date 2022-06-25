<?php

/**
 * @package SecretCode
 */

class Activate
{
  public function __activate()
  {
    global $wpdb;
    $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($wpdb->prefix . 'sc_secret_codes'));

    if ($wpdb->get_var($query) !=  $wpdb->prefix . 'sc_secret_codes') {
      $wpdb->query("
        CREATE TABLE `{$wpdb->prefix}sc_secret_codes` (
          `id` int(6) NOT NULL,
          `secret_code` varchar(12) COLLATE utf8_turkish_ci NOT NULL,
          `value` int(6) NOT NULL,
          `user_id` int(6) 
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
      MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
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
          `id` int(6) NOT NULL,
          `name` varchar(40) COLLATE utf8_turkish_ci NOT NULL,
          `value` varchar(40) COLLATE utf8_turkish_ci NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
      ");

      $wpdb->query("
      ALTER TABLE `{$wpdb->prefix}sc_options`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `name` (`name`);
    ");
      $wpdb->query("
      ALTER TABLE `{$wpdb->prefix}sc_options`
      MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
    ");

      $wpdb->query("

  INSERT INTO `{$wpdb->prefix}sc_options` (`option`, `value`) VALUES
  ('coefficient', '1')
  ");
    }
    flush_rewrite_rules();
  }
}
