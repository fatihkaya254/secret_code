<?php

class Limit
{

    private $wpdb;
    public $limit = 0;
    public $coe = 0;
    public $notice = '';
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options ", ARRAY_A);
        foreach ($st as $key) {
            if ($key['name'] == 'coefficient') {
                $this->coe = $key['value'];
            }
            if ($key['name'] == 'limit') {
                $this->limit = $key['value'];
            }
            if ($key['name'] == 'notice') {
                $this->notice = $key['value'];
            }
        }
    }

    public function change_value($name, $value): string
    {
        global $wpdb;
        $wpdb->get_results("UPDATE {$wpdb->prefix}sc_options SET `value` = '$value' WHERE {$wpdb->prefix}sc_options.`name` = '$name'");
        $st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE name = '$name'", ARRAY_A);
        foreach ($st as $key) {
            return  $key['value'];
        }
        return 0;
    }
    public function user_score($user): int
    {
        $coe = -1;
        $val = -1;
        global $wpdb;
        $query = "SELECT `value` FROM {$wpdb->prefix}sc_secret_codes WHERE  `user_id` = '$user'";
        $st = $wpdb->get_results($query, ARRAY_A);
        foreach ($st as $key) {
            $val = $key['value'];
        }
        $st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE `name` = 'coefficient'", ARRAY_A);
        foreach ($st as $key) {
            $coe = $key['value'];
        }
        $bigcoe = (float)$coe * 10000;
        $bigscroe = (float)$bigcoe *(float)$val;
        return  (float)$bigscroe / 10000;
    }
    public function user_requests($user): array
    {
        global $wpdb;
        $date = '';
        $answer = '';
        $rt = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_interrupt_requests WHERE `user_id` = '$user' AND (`answer` = 'inprocess' OR `answer` = 'undereview') ", ARRAY_A);
        foreach ($rt as $key) {
            $date = $key['date'];
            $answer = $key['answer'];
        }
        return ['date' => $date, 'answer' => $answer];
    }
    public function create_requests($user): string {
        global $wpdb;
        $limit = 0;

        $st = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_options WHERE `name` = 'limit' ", ARRAY_A);
        foreach ($st as $key) {
                $limit = $key['value'];
        }
        $rt = $this->user_requests($user);
        if ($rt['date'] != '') {
            return 'Devam eden talebiniz var';
        } else {
            $user_score = $this->user_score($user);
            if ($user_score > $limit) {
            $wpdb->query("INSERT INTO {$wpdb->prefix}sc_interrupt_requests (`user_id`, `value`, `current_limit`) VALUES ('$user', '$user_score', '$limit')");
                return 'Talebiniz oluşturuldu';
            } else {
                return 'Limitin altındasınız';
            }
        }
    }
}
