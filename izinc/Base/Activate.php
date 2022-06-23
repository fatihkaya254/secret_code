<?php

/**
* @package SecretCode
*/

class Activate
{
  public function activate()
  {
    global $wpdb;
    if ($wpdb->get_var("SHOW TABLES LİKE $wpdb->prefix.'sc_secret_codes'") != $wpdb->prefix . 'sc_secret_codes') {
      $wpdb->query("
        CREATE TABLE `{$wpdb->prefix}sc_secret_codes` (
          `id` int(6) NOT NULL,
          `secret_code` varchar(40) COLLATE utf8_turkish_ci NOT NULL,,
          `value` int(6) NOT NULL,
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
      ");
    }
    $wpdb->query("
      ALTER TABLE `{$wpdb->prefix}sc_secret_codes`
      ADD PRIMARY KEY (`id`),
      ADD KEY `isnp_id` (`secret_code`);
    ");
    $wpdb->query("
      ALTER TABLE `{$wpdb->prefix}sc_secret_codes`
      MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
    ");
    $wpdb->query("

  INSERT INTO `{$wpdb->prefix}sc_secret_codes` (`secret_code`, `value`) VALUES
  ('AW453FG', 44),
  ('ADFE344', 55),

  ");
  flush_rewrite_rules();
  
  }
}
