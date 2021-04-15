<?php

class Vmt_controller extends CI_Controller {

    function __construct() {
	parent::__construct();
        $this->load->database();
        $this->load->model(array('gtools', 'vmt_models'));
	
	if(session_id() == '') {
	    session_start();
	}
    }
    
    public function index(){
//        echo 'vmt : '.$_GET['vmt'];
//        $this->gtools->s_session('vmt', $_GET['vmt']);
//        echo 'vmt : '.$this->gtools->g_session('vmt');exit;
//	echo '<pre>';print_r($_SESSION);echo '<pre>';exit;
//        $data['vmt'] = $_GET['vmt'];
        $this->load->view('vmt');
    }
    
    public function vmt_login(){
	$qryMchType = "SELECT DISTINCT MCH_SUB_TYPE FROM M_MACHINE WHERE MCH_TYPE = 'YARD' AND MCH_SUB_TYPE IS NOT NULL";
        $getMchType = $this->db->query($qryMchType)->result_array();
	$data['machine_type'] = $getMchType;
	$data['yard'] = $this->db->get_where('M_YARD', array('STATUS' => '1'))->result_array();
        $this->load->view('vmt_login', $data);
    }
    
    public function auth(){
        $data['USERNAME'] = $_POST['username'];
        $data['PASSWORD'] = md5($_POST['password']);
        $data['ROLE_VMT'] = 'Y';
        
        $get = $this->db->get_where('M_USERS', $data)->result_array();
        if(count($get) > 0){
            $result['STATUS'] = TRUE;
            $result['ID_USER'] = $get[0]['ID_USER'];
            $result['NAME'] = $get[0]['FULL_NAME'];
            $result['TERMINAL'] = $get[0]['ID_TERMINAL'];
        }else{
            $result['STATUS'] = FALSE;
        }
        echo json_encode($result);
        exit;
    }
    
    public function logout(){
		$this->vmt_models->updatemachinelogout($_POST['vmt']);
	foreach ($_SESSION as $key => $val):
            if (strpos($key, $this->config->item('sess_cookie_name')) > -1):
		unset($_SESSION[$key]);
            endif;
        endforeach;
	
	echo json_encode(TRUE);
        exit;
    }
    
    public function vmt_view(){
	$this->gtools->s_session('vmt', $_POST['vmt']);
	$this->gtools->s_session('vmt_name', $_POST['vmt_name']);
	$this->gtools->s_session('yard', $_POST['yard']);
	$this->gtools->s_session('yard_name', $_POST['yard_name']);
        $this->gtools->s_session('username', $_POST['username']);
        $this->gtools->s_session('name', $_POST['name']);
        $this->gtools->s_session('terminal', $_POST['terminal']);
        $this->gtools->s_session('user', $_POST['user']);
	
//	$data['jobs'] = $this->vmt_models->getJobType($_POST);
	$data['yard_block'] = $this->vmt_models->mYardBlock($_POST['yard']);
	$data['suspend'] = $this->vmt_models->get_suspend_status();
	
	$machine = $this->vmt_models->get_mch_detail($_POST['vmt']);
	$updatemachine = $this->vmt_models->update_login_vmt($_POST['vmt']);
	$this->gtools->s_session('pool', $machine['ID_POOL']);
        $this->load->view('vmt_view', $data);
    }
    
    public function getVMT(){
	$qryMch = "SELECT ID_MACHINE,MCH_NAME FROM M_MACHINE WHERE MCH_TYPE!='ITV' AND MCH_SUB_TYPE = '".$_GET['vmt_type']."'";
        $getMch = $this->db->query($qryMch)->result_array();
	echo json_encode($getMch);
	exit;
    }
    
    public function getYardManager(){
	
		$result = $this->vmt_models->getJobYardManager($_GET);
		
		echo json_encode($result);
		exit;
    }
    
    public function slotInfo(){
	$id_block = $_GET['block'];
	$slot = $_GET['slot'];
	$result = $this->vmt_models->slotInfo($id_block, $slot);
	
	echo json_encode($result);
	exit;
    }
    
    public function get_void_list(){
	echo json_encode($this->vmt_models->get_void_list($_GET['block'],$_GET['slot']));
	exit;
    }

    public function yard_placement_submit(){
//	echo '<pre>user : ';print_r($this->gtools->g_session('user'));echo '</pre>';
//	echo '<pre>post : ';print_r($_POST);echo '</pre>';
	$id_user = $this->gtools->g_session('user');
	$id_terminal = $this->gtools->g_session('terminal');
	$act = $_POST['act'];
	$no_container = $_POST['no_container'];
	$point = $_POST['point'];
	$id_op_status = $_POST['id_op_status'];
	$event = $_POST['event'];
	$id_machine = $_POST['id_machine'];
	$driver_id = $_POST['driver_id'];
	$yt = $_POST['yt'];
	$class_code = $_POST['class_code'];
	
	$yard_position = array(
	    'BLOCK_NAME'=>$_POST['block_name'],
	    'BLOCK'=>$_POST['id_block'],
	    'SLOT'=>$_POST['slot'],
	    'ROW'=>$_POST['row'],
	    'TIER'=>$_POST['tier']
	);
//		echo '<pre>yard_pos : ';print_r($yard_position);echo '</pre>';
	if($act == 'P'){
	    $retval = $this->vmt_models->yard_placement_submit($no_container, $point, $id_op_status, $event, $id_user, $yard_position, $id_machine, $driver_id,$yt,$class_code,$id_terminal);
	}elseif($act == 'R'){
	    $retval = $this->vmt_models->yard_relocation_submit($no_container, $point, $id_user, $yard_position,$id_machine,$id_terminal );
	}else{
	    $retval = 'Gak pake vmt gue ya?';
	}
	echo json_encode($retval);
    }

    public function get_suspend_list(){
	$result = $this->vmt_models->get_suspend_list();
	
	echo json_encode($result);
	exit;
    }

    public function get_suspend_status(){
	$result = $this->vmt_models->get_suspend_status();
	
	echo json_encode($result);
	exit;
    }
    
    public function start_suspend(){
	$id_activity = $_POST['id'];
	$act = $_POST['act'];
	
	$res = $this->vmt_models->start_suspend($id_activity,$act);
	
	echo json_encode($res);
	exit;
    }
    
    public function get_itv_list(){
	$res = $this->vmt_models->get_itv_list();
	echo json_encode($res);
	exit;
    }
    
    public function get_terminal(){
	echo json_encode($this->vmt_models->user_terminal($_POST['id_user']));
	exit;
    }
    
    public function search_container_form(){
	$this->load->view('vmt_search');
    }
    
    public function doSearch(){
	echo json_encode($this->vmt_models->searchContainer($_POST['search_value']));
	exit;
    }
}