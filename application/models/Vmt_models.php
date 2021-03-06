<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vmt_models extends CI_Model {

    function __construct() {
	parent::__construct();
	$this->load->database();
    }
    
    public function getJobType($data){
	$qryJob = "SELECT * FROM (
		SELECT DISTINCT 
		CASE
			WHEN (A.ID_OP_STATUS = 'OYY' AND EVENT = 'O' AND STATUS_FLAG = 'P') THEN
				'MO'
			WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P') THEN
				'MI'
			WHEN (E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P')) THEN
				'DS'
			WHEN (E.ID_CLASS_CODE='I' AND A.EVENT='O') THEN
				CASE WHEN A.ID_OP_STATUS = 'OYY' THEN 'MI'
				ELSE 'GO' END
			WHEN (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN
				'LD'
			WHEN (E.ID_CLASS_CODE='E' AND A.EVENT='P') THEN
				'GI'
			ELSE
				''
		END AS JOB,
		CASE
			WHEN (A.ID_OP_STATUS = 'OYY' AND EVENT = 'O' AND STATUS_FLAG = 'P') THEN
				5
			WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P') THEN
				6
			WHEN (E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P')) THEN
				3
			WHEN (E.ID_CLASS_CODE='I' AND A.EVENT='O') THEN
				2
			WHEN (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN
				4
			WHEN (E.ID_CLASS_CODE='E' AND A.EVENT='P') THEN
				1
			ELSE
				NULL
		END AS SEQ
		FROM JOB_YARD_MANAGER A 
		INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT 
	 	WHERE A.ID_MACHINE='".$data['vmt']."') A ORDER BY SEQ";
	
	return $this->db->query($qryJob)->result_array();
    }
    
    public function mYardBlock($yard){
	return $this->db->get_where('M_YARDBLOCK', array('ID_YARD' => $yard))->result_array();
    }

    public function getJobYardManager($data){
//	echo '<pre>yard : '.$this->gtools->g_session('yard_name').'</pre>';
	$jobs = isset($data['job']) ? $data['job'] : '';
	$block = isset($data['block']) ? $data['block'] : '';
	$slot = isset($data['slot']) ? $data['slot'] : '';
	$status_flag = isset($data['status_flag']) && $data['status_flag'] != '' ? $data['status_flag'] : 'P';
	$arrJobs = explode(',', $jobs);
	
	$qryYardManager = "SELECT 
		    CASE
			WHEN (A.ID_OP_STATUS = 'OYY' AND EVENT = 'O' AND STATUS_FLAG = 'P') THEN
				'MO'
			WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P') THEN
				'MI'
			WHEN (E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P')) THEN
				'DS'
			WHEN (E.ID_CLASS_CODE='I' AND A.EVENT='O') THEN
				CASE WHEN A.ID_OP_STATUS = 'OYY' THEN 'MI'
				ELSE 'GO' END
			WHEN (E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O')) THEN
				'LD'
			WHEN (E.ID_CLASS_CODE='E' AND A.EVENT='P') THEN
				'GI'
			ELSE
				''
		    END AS JOB,
		    E.CONT_SIZE,
		    E.CONT_TYPE,
		    E.ID_CLASS_CODE,
		    E.ID_COMMODITY,
		    E.ID_OPERATOR,
		    A.NO_CONTAINER,
		    A.POINT,
		    A.EVENT,
		    A.ID_OP_STATUS,
		    A.ID_VES_VOYAGE,
		    F.ID_VESSEL,
		    E.ID_POD,
		    E.GT_JS_BLOCK,
		    E.GT_JS_BLOCK_NAME,
		    CASE WHEN (E.CONT_SIZE = 40 OR E.CONT_SIZE = 45) AND MOD(E.GT_JS_SLOT,2) = 1 THEN E.GT_JS_SLOT + 1 ELSE E.GT_JS_SLOT END AS SLOT,
		    E.GT_JS_ROW AS JS_ROW,
		    E.GT_JS_TIER AS JS_TIER,
		    CASE WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P')
	    		THEN E.GT_JS_BLOCK_NAME 
				    || '-' || CASE WHEN (E.CONT_SIZE = 40 OR E.CONT_SIZE = 45) AND MOD(E.GT_JS_SLOT,2) = 1 THEN E.GT_JS_SLOT + 1 ELSE E.GT_JS_SLOT END 
				    || '-' || E.GT_JS_ROW || '-' || E.GT_JS_TIER
		    ELSE
			    NVL(E.YD_BLOCK_NAME,E.GT_JS_BLOCK_NAME) 
			    || '-' || CASE WHEN (E.CONT_SIZE = 40 OR E.CONT_SIZE = 45) AND MOD(NVL(E.YD_SLOT,E.GT_JS_SLOT),2) = 1 THEN NVL(E.YD_SLOT,E.GT_JS_SLOT) + 1 ELSE NVL(E.YD_SLOT,E.GT_JS_SLOT) END 
			    || '-' || NVL(E.YD_ROW,E.GT_JS_ROW) || '-' || NVL(E.YD_TIER,E.GT_JS_TIER) 
		    END AS PA,
		    E.YD_BLOCK,
		    E.YD_BLOCK_NAME,
		    E.YD_SLOT,
		    E.YD_ROW,
		    E.YD_TIER,
		    CASE WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P') 
	    		THEN E.GT_JS_BLOCK
		    ELSE NVL(E.YD_BLOCK, E.GT_JS_BLOCK) END AS BLOCK_,
		    CASE WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P') 
	    		THEN E.GT_JS_BLOCK_NAME
		    ELSE NVL(E.YD_BLOCK_NAME, E.GT_JS_BLOCK_NAME) END AS BLOCK_NAME,
		    CASE WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P') 
	    		THEN CASE WHEN (E.CONT_SIZE = 40 OR E.CONT_SIZE = 45) AND MOD(E.GT_JS_SLOT,2) = 1 THEN E.GT_JS_SLOT + 1 ELSE E.GT_JS_SLOT END
		    ELSE
		    	CASE WHEN (E.CONT_SIZE = 40 OR E.CONT_SIZE = 45) AND MOD(NVL(E.YD_SLOT, E.GT_JS_SLOT),2) = 1 THEN NVL(E.YD_SLOT, E.GT_JS_SLOT) + 1 ELSE NVL(E.YD_SLOT, E.GT_JS_SLOT) END 
		    END AS SLOT_,
		    CASE WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P') 
	    		THEN E.GT_JS_ROW
		    ELSE NVL(E.YD_ROW, E.GT_JS_ROW) END AS ROW_,
		    CASE WHEN (A.ID_OP_STATUS = 'YYY' AND EVENT = 'P' AND STATUS_FLAG = 'P') 
	    		THEN E.GT_JS_TIER
		    ELSE NVL(E.YD_TIER, E.GT_JS_TIER) END AS TIER_,
		    NVL(A.ID_MACHINE, '') AS ID_MACHINE,
		    NVL(A.ID_MACHINE_ITV, '') AS ID_MACHINE_ITV,
		    NVL(D.MCH_NAME, '') AS ITV,
		    A.ID_TRUCK,
		    G.TID,
		    E.CONT_STATUS,
		    E.ID_ISO_CODE
		FROM JOB_YARD_MANAGER A 
		INNER JOIN CON_LISTCONT E ON A.NO_CONTAINER=E.NO_CONTAINER AND A.POINT=E.POINT 
		INNER JOIN M_MACHINE C ON A.ID_MACHINE=C.ID_MACHINE 
		INNER JOIN VES_VOYAGE F ON A.ID_VES_VOYAGE=F.ID_VES_VOYAGE
		LEFT JOIN M_MACHINE D ON A.ID_MACHINE_ITV=D.ID_MACHINE 
		LEFT JOIN M_TRUCK G ON A.ID_TRUCK = G.ID_TRUCK
		WHERE STATUS_FLAG='".$status_flag."'
		AND E.GT_JS_BLOCK IN(SELECT ID_BLOCK FROM M_YARDBLOCK WHERE ID_YARD = ".$this->gtools->g_session('yard').")";
	if($status_flag == 'P'){
	    $qryYardManager .= " AND A.ID_MACHINE = '".$this->gtools->g_session('vmt')."' ";
	    if($slot != ''){
		$qryYardManager .= " AND E.GT_JS_SLOT = '".$slot."' ";
	    }
	}else{
	    if($slot != ''){
		$qryYardManager .= " AND E.YD_SLOT = '".$slot."' ";
	    }
	}
	if($block != ''){
	    $qryYardManager .= " AND E.GT_JS_BLOCK_NAME = '".$block."' ";
	}
	
	
	if($jobs != ''){
	    $qryYardManager .= " AND (";
	    
	    $qryWhere = '';
	    foreach ($arrJobs as $job){
		if($qryWhere != '') $qryWhere .= ' OR ';
//		echo '<pre>job : '.trim($job).'</pre>';
		if(trim($job) == 'DS'){
		    $qryWhere .= "(E.ID_CLASS_CODE='TI' OR (E.ID_CLASS_CODE='I' AND A.EVENT='P'))";
		}elseif(trim($job) == 'GO'){
		    $qryWhere .= "(E.ID_CLASS_CODE='I' AND A.EVENT='O')";
		}elseif(trim($job) == 'LD'){
		    $qryWhere .= "(E.ID_CLASS_CODE='TE' OR (E.ID_CLASS_CODE='E' AND A.EVENT='O'))";
		}elseif(trim($job) == 'GI'){
		    $qryWhere .= "(E.ID_CLASS_CODE='E' AND A.EVENT='P')";
		}elseif(trim($job) == 'MO'){
		    $qryWhere .= "(A.ID_OP_STATUS='OYY' AND A.EVENT='O')";
		}elseif(trim($job) == 'MI'){
		    $qryWhere .= "(A.ID_OP_STATUS='YYY' AND A.EVENT='P')";
		}
	    }
	    
	    $qryYardManager .= $qryWhere.')';
	}
//	echo '<pre>'.$qryYardManager.'</pre>';exit;
	$result = $this->db->query($qryYardManager)->result_array();
	
	return $result;
    }
    
    public function slotInfo($id_block, $slot){
	$id_yard = $this->gtools->g_session('yard');
	$query_even1 = '';
	$query_even2 = '';
	if($slot%2==1){
		$query_even1 = " OR (D.SLOT_-1=$slot AND B.CONT_SIZE!='20')";
		$query_even2 = " OR A.SLOT_+1=STACK.SLOT_";
	}
	
	$qry = "SELECT
			A.ID_BLOCK,NVL(HKP_BLOCK_NAME,STACK.BLOCK_) BLOCK_,A.INDEX_CELL, A.ROW_,A.SLOT_, A.TIER_, NVL(HKP.NO_CONTAINER, STACK.NO_CONTAINER) NO_CONTAINER, STACK.POINT, STACK.CONT_SIZE, STACK.CONT_TYPE, STACK.ID_ISO_CODE, ROUND(STACK.WEIGHT,1) WEIGHT, STACK.ID_POD, STACK.ID_VES_VOYAGE, STACK.ID_COMMODITY, STACK.ID_OPERATOR,STACK.ID_SPEC_HAND,STACK.IMDG,
			fc_col_vssvc_port(STACK.ID_VES_VOYAGE, STACK.ID_POD) as COLORS,
			STACK.ID_CLASS_CODE,STACK.ID_OP_STATUS ,STACK.EVENT,
			CASE WHEN HKP.NO_CONTAINER IS NOT NULL THEN 1 ELSE 0 END AS IS_HKP
			FROM M_YARDBLOCK_CELL A
			LEFT JOIN
			(SELECT D.ID_YARD, D.ID_BLOCK, D.BLOCK_, D.SLOT_, D.ROW_, D.TIER, D.NO_CONTAINER, D.POINT, B.CONT_SIZE, B.CONT_TYPE, B.ID_ISO_CODE, (B.WEIGHT/1000) WEIGHT, B.ID_POD, B.ID_VES_VOYAGE, B.ID_COMMODITY, B.ID_OPERATOR, B.ID_CLASS_CODE, B.ID_SPEC_HAND, B.IMDG, B.ID_OP_STATUS,JYM.EVENT
				FROM
				JOB_PLACEMENT D
				INNER JOIN CON_LISTCONT B
				ON B.NO_CONTAINER=D.NO_CONTAINER AND B.POINT=D.POINT
				LEFT JOIN JOB_YARD_MANAGER JYM 
				ON D.NO_CONTAINER = JYM.NO_CONTAINER AND D.ID_VES_VOYAGE = JYM.ID_VES_VOYAGE AND D.POINT = JYM.POINT
				WHERE (D.SLOT_=$slot $query_even1)) STACK
			ON A.ID_YARD=STACK.ID_YARD AND A.ID_BLOCK=STACK.ID_BLOCK AND (A.SLOT_=STACK.SLOT_ $query_even2) AND A.ROW_=STACK.ROW_ AND A.TIER_=STACK.TIER
			LEFT JOIN CON_OUTBOUND_SEQUENCE C
				ON STACK.NO_CONTAINER=C.NO_CONTAINER AND STACK.POINT=C.POINT
			LEFT JOIN (
				SELECT D.NO_CONTAINER,D.GT_JS_YARD HKP_YARD,D.GT_JS_BLOCK HKP_BLOCK,D.GT_JS_BLOCK_NAME HKP_BLOCK_NAME,D.GT_JS_SLOT HKP_SLOT,D.GT_JS_ROW HKP_ROW,D.GT_JS_TIER HKP_TIER 
				FROM CON_HKP_PLAN_H H
				LEFT JOIN CON_HKP_PLAN_D D
				  ON H.HKP_ID = D.HKP_ID
				WHERE H.HKP_STATUS != 'C'
			) HKP ON A.ID_YARD = HKP.HKP_YARD AND A.ID_BLOCK = HKP.HKP_BLOCK AND A.SLOT_ = HKP.HKP_SLOT AND A.ROW_ = HKP.HKP_ROW AND A.TIER_ = HKP.HKP_TIER
			WHERE A.ID_YARD=$id_yard AND A.ID_BLOCK=$id_block AND A.SLOT_=$slot
			ORDER BY A.TIER_ DESC, A.ROW_ ASC";
//	echo '<pre>'.$qry.'</pre>';exit;
	$result = $this->db->query($qry)->result_array();
	
	return $result;
    }
//                                         ($no_container, $point, $id_op_status, $event, $id_user, $yard_position, $id_machine, $driver_id);
    public function yard_placement_submit($no_container, $point, $id_op_status, $event, $user_id, $yard_position, $id_machine, $driver_id,$yt,$class_code, $id_terminal) {
	$status_flag = 'F';
	$message = '';
	$isProcess = TRUE;

			if($event == 'O' && $class_code == 'E'){
			    $qry = "SELECT A.NO_CONTAINER,C.ID_CLASS_CODE,B.EVENT,
						    CASE WHEN (C.ID_CLASS_CODE = 'E' OR C.ID_CLASS_CODE = 'TE') AND B.EVENT = 'O' THEN C.YD_BLOCK_NAME || ' - ' || D.BAY_ || ' ' || D.DECK_HATCH || ' ' || ' LD'
						    WHEN (C.ID_CLASS_CODE = 'I' OR C.ID_CLASS_CODE = 'TI') AND B.EVENT = 'P' THEN C.GT_JS_BLOCK_NAME || ' - ' || E.BAY_ || ' ' || E.DECK_HATCH || ' ' || ' DS'
						    ELSE '' END AS LOCATION
				    FROM JOB_QUAY_MANAGER A
				    INNER JOIN JOB_YARD_MANAGER B
					    ON A.NO_CONTAINER = B.NO_CONTAINER AND a.STATUS_FLAG != B.STATUS_FLAG
					    AND CASE WHEN B.EVENT = 'O' THEN 'P' ELSE 'C' END = A.STATUS_FLAG 
					    AND CASE WHEN B.EVENT = 'O' THEN 'C' ELSE 'P' END = B.STATUS_FLAG
				    LEFT JOIN CON_LISTCONT C
					    ON A.NO_CONTAINER = C.NO_CONTAINER AND A.POINT = C.POINT
				    LEFT JOIN CON_OUTBOUND_SEQUENCE D
					    ON A.NO_CONTAINER = D.NO_CONTAINER AND A.POINT = D.POINT
				    LEFT JOIN CON_INBOUND_SEQUENCE E
					    ON A.NO_CONTAINER = E.NO_CONTAINER AND A.POINT = E.POINT
				    WHERE A.ID_MACHINE_ITV = '".$yt."'";
//			    echo '<pre>'.$qry.'</pre>';
			    $check_job_itv = $this->db->query($qry)->result_array();
//			    echo '<pre>'.$check_job_itv[0]['EVENT'].'</pre>';
			    
			    if(count($check_job_itv) > 1){
				$isProcess = FALSE;
				$status_flag = 'F';
				$message = 'Container full';
			    }else if(count($check_job_itv) == 1){
				if($check_job_itv[0]['EVENT'] == 'P'){
				    $isProcess = FALSE;
				    $status_flag = 'F';
				    $message = 'Container has job for placement';
				}
			    }
			}
	if($isProcess){
	    $param = array(
		array('name' => ':no_container', 'value' => $no_container, 'length' => 15),
		array('name' => ':point', 'value' => $point, 'length' => 10),
		array('name' => ':id_op_status', 'value' => $id_op_status, 'length' => 3),
		array('name' => ':event', 'value' => $event, 'length' => 1),
		array('name' => ':user_id', 'value' => $user_id, 'length' => 10),
		array('name' => ':driver_id', 'value' => $driver_id, 'length' => 10),
		array('name' => ':id_block', 'value' => $yard_position['BLOCK'], 'length' => 10),
		array('name' => ':block_', 'value' => $yard_position['BLOCK_NAME'], 'length' => 10),
		array('name' => ':slot_', 'value' => $yard_position['SLOT'], 'length' => 10),
		array('name' => ':row_', 'value' => $yard_position['ROW'], 'length' => 10),
		array('name' => ':tier_', 'value' => $yard_position['TIER'], 'length' => 10),
		array('name' => ':id_machine', 'value' => $id_machine, 'length' => 10),
		array('name' => ':v_terminal', 'value' => $id_terminal, 'length' => 10),
		array('name' => ':status_flag', 'value' => &$status_flag, 'length' => 1),
		array('name' => ':message', 'value' => &$message, 'length' => 1000)
	    );
//			 echo '<pre>';print_r($param);echo '</pre>';exit;

	    $sql = "BEGIN PROC_JOB_YARD_COMPLETE(:no_container, :point, :id_op_status, :event, :user_id, :driver_id, :id_block, :block_, :slot_, :row_, :tier_, :id_machine, :v_terminal, :status_flag, :message); END;";
	    $this->db->exec_bind_stored_procedure($sql, $param);
	    
	    if($status_flag == 'S' && $event == 'O' && $id_op_status == 'OYS'){
		$qryGetVes = "SELECT ID_VES_VOYAGE
			    FROM JOB_YARD_MANAGER
			    WHERE NO_CONTAINER='$no_container' AND POINT='$point' AND STATUS_FLAG='C'";
		$getVes = $this->db->query($qryGetVes)->result_array();
		$vesvoyage =  $getVes[0]['ID_VES_VOYAGE'];
//			echo '<pre>ID_VES_VOYAGE : '.$vesvoyage.'</pre>';
		if($class_code == 'E' || $class_code == 'TE'){
		    $qryUpJobPickup = "UPDATE JOB_PICKUP SET ID_MACHINE_ITV = '$yt' WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND ACTIVITY = 'E' AND ID_VES_VOYAGE = '$vesvoyage'";
//			    echo '<pre>qryUpJobPickup : '.$qryUpJobPickup.'</pre>';
		    $this->db->query($qryUpJobPickup);

		    $qryUpJobQuay = "UPDATE JOB_QUAY_MANAGER SET ID_MACHINE_ITV = '$yt' WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND STATUS_FLAG = 'P' AND ID_VES_VOYAGE = '$vesvoyage'";
//			    echo '<pre>qryUpJobQuay : '.$qryUpJobQuay.'</pre>';
		    $this->db->query($qryUpJobQuay);

		    $qryUpJobYard = "UPDATE JOB_YARD_MANAGER SET ID_MACHINE_ITV = '$yt' WHERE NO_CONTAINER = '$no_container' AND POINT = '$point' AND STATUS_FLAG = 'C' AND ID_VES_VOYAGE = '$vesvoyage'";
//			    echo '<pre>qryUpJobQuay : '.$qryUpJobQuay.'</pre>';
		    $this->db->query($qryUpJobYard);
		}
	    }
	}
	return array($status_flag, $message);
    }
    
    public function yard_relocation_submit($no_container, $point, $user_id, $yard_position, $machine, $id_terminal){
	    $status_flag = 'F';
	    $message = '';

	    $param = array(
		    array('name'=>':no_container', 'value'=>$no_container, 'length'=>15),
		    array('name'=>':point', 'value'=>$point, 'length'=>10),
		    array('name'=>':user_id', 'value'=>$user_id, 'length'=>10),
		    array('name'=>':id_block', 'value'=>$yard_position['BLOCK'], 'length'=>10),
		    array('name'=>':block_', 'value'=>$yard_position['BLOCK_NAME'], 'length'=>10),
		    array('name'=>':slot_', 'value'=>$yard_position['SLOT'], 'length'=>10),
		    array('name'=>':row_', 'value'=>$yard_position['ROW'], 'length'=>10),
		    array('name'=>':tier_', 'value'=>$yard_position['TIER'], 'length'=>10),
		    array('name'=>':status_flag', 'value'=>&$status_flag, 'length'=>1),
		    array('name'=>':message', 'value'=>&$message, 'length'=>1000),
		    array('name'=>':machine', 'value'=>$machine, 'length'=>10),
		    array('name'=>':terminal', 'value'=>$id_terminal, 'length'=>10)
	    );
	    //print_r($param);die;

	    $sql = "BEGIN PROC_RELOCATION_COMPLETE(:no_container, :point, :user_id, :id_block, :block_, :slot_, :row_, :tier_, :status_flag, :message, :machine,:terminal); END;";
	    $this->db->exec_bind_stored_procedure($sql, $param);
	    return array($status_flag, $message);
    }

    public function get_suspend_list(){
	$sql = "SELECT * FROM M_SUSPEND WHERE EQ_TYPE = 'YARD'";
	$result = $this->db->query($sql)->result_array();
	
	return $result;
	
    }
    
    public function get_suspend_status(){
	$query 	= "SELECT A.ID_SUSPEND,B.ACTIVITY,A.ID_DRIVER,A.START_SUSPEND,END_SUSPEND,ID_MACHINE 
		    FROM JOB_SUSPEND A 
		    LEFT JOIN M_SUSPEND B ON A.ID_SUSPEND = B.ID_SUSPEND
		    WHERE START_SUSPEND =   (SELECT MAX(START_SUSPEND) FROM JOB_SUSPEND WHERE ID_MACHINE=".$this->gtools->g_session('vmt')."
		    AND ID_DRIVER='".$this->gtools->g_session('user')."'
		    AND END_SUSPEND IS  NULL)
		    AND ID_MACHINE=".$this->gtools->g_session('vmt')."
		    AND ID_DRIVER='".$this->gtools->g_session('user')."'
		    AND END_SUSPEND IS  NULL
		    AND B.EQ_TYPE = 'ITV'";
//	echo '<pre>suspend : '.$query.'</pre>';exit;
	$rs = $this->db->query($query)->result_array();
	if(count($rs) > 0){
	    $result['ID'] = $rs[0]['ID_SUSPEND'];
	    $result['ACTIVITY'] = $rs[0]['ACTIVITY'];
	}else{
	    $result['ID'] = '';
	    $result['ACTIVITY'] = 'AVAILABLE';
	}
	return $result;
    }
    
    public function start_suspend($id_activity,$act){
	$id_machine = $this->gtools->g_session('vmt');
	$id_driver = $this->gtools->g_session('user');
	$query 	= "SELECT A.ID_SUSPEND,B.ACTIVITY,A.ID_DRIVER,A.START_SUSPEND,END_SUSPEND,ID_MACHINE 
		    FROM JOB_SUSPEND A 
		    LEFT JOIN M_SUSPEND B ON A.ID_SUSPEND = B.ID_SUSPEND
		    WHERE START_SUSPEND =   (SELECT MAX(START_SUSPEND) FROM JOB_SUSPEND WHERE ID_MACHINE=".$id_machine."
		    AND ID_DRIVER='".$id_driver."'
		    AND END_SUSPEND IS  NULL)
		    AND ID_MACHINE=".$id_machine."
		    AND ID_DRIVER='".$id_driver."'
		    AND END_SUSPEND IS  NULL";

	$rs = $this->db->query($query)->result_array();
	
	if($act == 'START' && count($rs) > 0){
	    return 'Machine Status : '.$rs[0]['ACTIVITY'];
	}else{
//	    $this->db->trans_start();
	    if($act == 'START'){
		$query1 	= "INSERT INTO JOB_SUSPEND (ID_SUSPEND, ID_MACHINE,ID_DRIVER, START_SUSPEND)
				VALUES ($id_activity, $id_machine, $id_driver, sysdate) ";
	    }else{
		$query1 	= "UPDATE JOB_SUSPEND
                        SET END_SUSPEND = sysdate
			WHERE START_SUSPEND = (SELECT MAX(START_SUSPEND) FROM JOB_SUSPEND WHERE  ID_MACHINE=$id_machine
					AND ID_DRIVER='$id_driver'
					AND END_SUSPEND IS  NULL)";
	    }
//	    $this->db->query($query1);
//	    if($this->db->trans_complete())
	    if($this->db->query($query1))
	    {
		    return '1';
	    }
	    else
	    {
		    return 'ERROR '.$act.' SUSPEND';
	    }
	}
    }
    
    public function get_mch_detail($id_machine){
	$qry = 'SELECT * FROM M_MACHINE WHERE ID_MACHINE='.$id_machine;
//	echo $qry;exit;
	$res = $this->db->query($qry)->result_array();
	
	return $res[0];
    }
    
    public function get_itv_list(){
	$pool = $this->gtools->g_session('pool');
//	echo $pool;exit;
//	if($pool != ''){
	    $qry = "SELECT * FROM M_MACHINE
			    WHERE MCH_TYPE = 'ITV' AND (ID_MACHINE NOT IN (SELECT DISTINCT ID_MACHINE_ITV FROM JOB_QUAY_MANAGER WHERE STATUS_FLAG != 'C' AND ID_MACHINE_ITV IS NOT NULL
			    UNION
			    SELECT DISTINCT ID_MACHINE_ITV FROM JOB_YARD_MANAGER WHERE STATUS_FLAG != 'C' AND ID_MACHINE_ITV IS NOT NULL))
			    ORDER BY MCH_NAME";
//	    $qry = "SELECT * FROM M_MACHINE WHERE MCH_TYPE='ITV' AND ID_POOL=".$pool;
//	    echo $qry;exit;
	    $res = $this->db->query($qry)->result_array();
//	}else{
//	    $res = false;
//	}
	return $res;
    }
    
    public function user_terminal($id_user){
	$qry = "SELECT A.ID_USER,A.ID_TERMINAL,B.TERMINAL_CODE,B.TERMINAL_NAME
		FROM M_USER_TERMINAL A
		LEFT JOIN M_TERMINAL B ON A.ID_TERMINAL = B.ID_TERMINAL
		WHERE ID_USER = $id_user AND ASSIGN = 1";
	return $this->db->query($qry)->result_array();
    }
    
    public function searchContainer($cont){
	$qry = "SELECT A.NO_CONTAINER,A.POINT,A.ID_VES_VOYAGE, A.ID_ISO_CODE,A.CONT_STATUS,B.TID,JYM.ID_MACHINE,A.ID_OP_STATUS,A.ACTIVE,JYM.EVENT,
				CASE WHEN JYM.EVENT = 'P' AND JYM.STATUS_FLAG = 'P' THEN 'Placement'
					 WHEN JYM.EVENT = 'O' AND A.ID_CLASS_CODE = 'I' AND JYM.STATUS_FLAG = 'P' THEN 'On Chassis (Gate Out)'
					 WHEN JYM.EVENT = 'O' AND A.ID_CLASS_CODE = 'E' AND JYM.STATUS_FLAG = 'P' THEN 'On Chassis (Loading)'
					 ELSE '' END AS WAIT_JOB
				 ,A.YD_BLOCK
			 	 ,CASE WHEN (A.CONT_SIZE = 40 OR A.CONT_SIZE = 45) AND MOD(A.YD_SLOT,2) = 1 THEN A.YD_SLOT + 1 ELSE A.YD_SLOT END AS YD_SLOT
			  	 ,CASE WHEN JYM.EVENT = 'O' AND JYM.STATUS_FLAG = 'P' OR JYM.EVENT = 'P' AND JYM.STATUS_FLAG = 'C' THEN 
					A.YD_BLOCK_NAME 
				 || '-' || CASE WHEN (A.CONT_SIZE = 40 OR A.CONT_SIZE = 45) AND MOD(A.YD_SLOT,2) = 1 THEN A.YD_SLOT + 1 ELSE A.YD_SLOT END 
				 || '-' || A.YD_ROW || '-' || A.GT_JS_TIER
					ELSE '' END AS POSITION_
				 ,CASE WHEN JYM.EVENT = 'P' AND JYM.STATUS_FLAG = 'P' THEN 
					A.GT_JS_BLOCK_NAME 
				 || '-' || CASE WHEN (A.CONT_SIZE = 40 OR A.CONT_SIZE = 45) AND MOD(A.GT_JS_SLOT,2) = 1 THEN A.GT_JS_SLOT + 1 ELSE A.GT_JS_SLOT END 
				 || '-' || A.GT_JS_ROW || '-' || A.GT_JS_TIER
					ELSE '' END AS EXPECTED_POSITION
		FROM CON_LISTCONT A
		INNER JOIN JOB_YARD_MANAGER JYM ON A.NO_CONTAINER = JYM.NO_CONTAINER AND A.POINT = JYM.POINT 
		INNER JOIN M_YARDBLOCK b ON NVL(A.YD_BLOCK,A.GT_JS_BLOCK) = B.ID_BLOCK
		LEFT JOIN M_TRUCK B ON A.ID_TRUCK = B.ID_TRUCK
		WHERE (A.YD_YARD = ".$this->gtools->g_session('yard')." OR A.GT_JS_YARD = ".$this->gtools->g_session('yard').") AND A.NO_CONTAINER LIKE '%$cont' AND A.ACTIVE = 'Y'
			AND CASE WHEN JYM.EVENT = 'P' AND JYM.STATUS_FLAG = 'P' THEN ID_MACHINE ELSE 1 END = CASE WHEN JYM.EVENT = 'P' AND JYM.STATUS_FLAG = 'P' THEN ".$this->gtools->g_session('vmt')." ELSE 1 END";
//	echo '<pre>'.$qry.'</pre>';exit;
	return $this->db->query($qry)->result_array();
    }
}
