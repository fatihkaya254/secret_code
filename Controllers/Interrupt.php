<?php
require_once plugin_dir_path(__FILE__) . "../Controllers/Limit.php";
class Interrupt extends Limit
{

    public $list = [];
    public function __construct()
    {
       $this->get_requests(); 
    }
    public function get_requests(){
        global $wpdb;
        $this->list = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_interrupt_requests WHERE `answer` != 'denied' AND `answer` != 'accepted'", ARRAY_A);
    }
    public function send_response($answer, $id, $user)
    {
        global $wpdb;
        $wpdb->get_results("UPDATE {$wpdb->prefix}sc_interrupt_requests SET `answer` = '$answer' WHERE `id` = '$id'");
        $this->get_requests(); 
        if($user){
            $this->accept($user);
        }
    }
    public function accept($user){
        global $wpdb;
        $score = $this->user_score($user);
        $wpdb->get_results("UPDATE {$wpdb->prefix}sc_secret_codes SET `value` = 0 WHERE `user_id` = '$user'");
        $wpdb->query("INSERT INTO {$wpdb->prefix}sc_user_history (`user_id`, `value`) VALUES ('$user', '$score')");
    }
    public function get_history($user){
        global $wpdb;
        $history = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sc_user_history WHERE `user_id` = '$user'", ARRAY_A);
        return $history;
    }
}
