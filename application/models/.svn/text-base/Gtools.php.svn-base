<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gtools extends CI_Model {

    function __construct() {
	parent::__construct();
	//$this->load->database();
    }

    public function s_session($name, $value){
//	set_cookie($this->config->item('sess_cookie_name').'_'.$name, $value, $this->config->item('sess_expiration'));
	$_SESSION[$this->config->item('sess_cookie_name') . '_' . $name] = $value;
    }

    public function g_session($name) {
//	return isset($_COOKIE[$this->config->item('sess_cookie_name') . '_' . $name]) ? $_COOKIE[$this->config->item('sess_cookie_name') . '_' . $name] : FALSE;
	return isset($_SESSION[$this->config->item('sess_cookie_name') . '_' . $name]) ? $_SESSION[$this->config->item('sess_cookie_name') . '_' . $name] : FALSE;
    }
    
    public function isLogin() {
	$curr = $this->g_session('username');
//	echo '<pre>isLogin : '.$curr.'</pre>';
	if ($curr != FALSE && $curr != '')
	    $curr = TRUE;
	else
	    $curr = FALSE;
	return $curr;
    }
}
