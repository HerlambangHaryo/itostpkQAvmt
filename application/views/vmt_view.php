
<style>
div.boxlist {
    background-color: lightblue;
    width: 100%;
    height: 100%;
    overflow: auto;
}

#div-boxyard {
    width: calc(100% - 20px);
    height: calc(100% - 20px);
    overflow: auto;
}

#div-rownum{
    overflow: hidden;
    width: 100%;
}
#div-rownum-boxyard{
    display: inline-flex;
    height: 20px;
}

.box{
    /*border-top: 1px solid #ffffff;*/
    border-bottom: 1px solid #cccccc;
    color: #222;
    font-weight: bold;
}

.button {
  padding: 15px 25px;
  text-align: center;
  font-size: 20px;
}

/*###################################################################################*/
.no-gutter > [class*='col-'] {
    padding-right: 1px;
    padding-left: 1px;
    margin-bottom: 2px;
}

img {
  width: 100%;
}

.padnol{
    padding : 0px;
    background-color: #fff;
    border-color: #fff;
}
.right-area{
    padding: 40px;
}
.sinput-form{
        font-size: 50px;
        width: 100%;
    }
.binput-form{
    font-size: 40px;
    width: 100%;
    /*height:65px;*/
}
.binput-formv{
    font-size: 24px;
    width: 100%;
    height:50px;
}
.finput-form{
    font-size: 40px;
    width:100%;
    /*height:55px;*/
}
.mepet{
    padding:0px;
}

.block-text{
    display: none;
    font-size: 35px; 
    width: calc(100% - 124px); 
    text-align: center;
}

.block-text.active{
    display: block;
}

#div-slot{
    display: inline-block;
    width: calc(100% - 124px); 
}

.slot-text{
    display: none;
    font-size: 35px; 
    width: 100%; 
    text-align: center;
}

.slot-text.active{
    display: block;
}

.block-list, .slot-list{
    font-size: 15px;
    font-weight: bold;
    height: 50px;
    margin: 5px;
    width: 100px;
}
.box-rownum{
    margin: 5px;
    text-align: center;
    float: left;
    width: 100px;
}

#div-tiernum{
    float: left;
    height: calc(100% - 40px);
    padding: 5px 0px;
    width: 20px;
    overflow: hidden;
}

.box-tiernum{
    display: inline-flex;
    height: 100px;
    margin: 5px;
    width: 100%;
}

.box-tiernum label{
    margin: auto 0px;
}

.box-position{
    background: #ffffff;
    /*border: 1px solid #cccccc;*/
    margin: 5px;
    height: 100px;
    width: 100px;
    word-break: break-all;
}

.box-position.plan-filled{
    background: #FFFF99;
}

.box-position.choosed{
    background: #66FF00;
}

.box-position.job-plan-choosed{
    background: greenyellow;
}

.div-tier{
    display: inline-flex;
}

.btn-suspend{
    height:60px; 
    font-size:30px;
}

.btn-suspend.choosed{
    background: red;
}

.btn-suspend.choosed:hover{
    background: yellow;
}
</style>
<script>
$(document).ready(function(){
//    getYardManager();
    loadSlot();
    setBoxYardPosition();
<?php
    if($this->gtools->isLogin()){
?>
    refresh();
<?php
    }
?>
});

function refresh(){
    getYardManager();
    slotInfo();
//    setTO = setTimeout(refresh, 5000);
}

function setActiveBtnJobFilter(e){
//    $('.job-filter').removeClass('btn-primary');
    if($(e).hasClass('job-filter')){
	if($(e).hasClass('btn-primary')){
	    $(e).removeClass('btn-primary');
	}else{
	    $(e).addClass('btn-primary');
	}
    }else if($(e).hasClass('job-filter-all')){
	$('.job-filter').removeClass('btn-primary');
    }
    getYardManager();
}

function btnFilterBlock_click(e){
    $('.filter-block').removeClass('btn-primary');
    $(e).addClass('btn-primary');
    getYardManager();
}

function getYardManager(){
    var job = '';
    var block = '';
    
    var box_active = $('.box.btn-primary').first().attr('id');
    if($('.job-filter.btn-primary').length > 0){
	$('.job-filter.btn-primary').each(function (){
	    if(job != '') job += ',';
	    job += $(this).text();
	});
    }
    
    if($('#btn-filter-block').hasClass('btn-primary')){
	block = $('.block-text.active').first().val();
    }
    
    $.ajax({
	url:'<?= base_url()?>index.php/vmt_controller/getYardManager/',
	method: "GET",
	dataType : 'json',
	async: false,
	data:{
	    job : job,
	    block : block
	},
	success: function(data){
//	    yang perlu di tampilin itu :
//		    job, cont_type, cont_size, cont_status, id_class_code, no_container, VES_VOYAGE.ID_VESSEL,
//		    con_listcont.id_pod, pa, id_machine_itv
	    var htmlListYard = '';
	    $.each(data, function(i,k){
		var commodity = k.ID_COMMODITY != null ? k.ID_COMMODITY : '';
		var id_itv = k.ID_MACHINE_ITV != null ? k.ID_MACHINE_ITV : '';
		var itv = k.ITV != null ? k.ITV : '';
		var tid = k.TID != null ? k.TID : '';
		var id_truck = k.ID_TRUCK != null ? k.ID_TRUCK : '';
		var pri_class = '';
		if(box_active == 'box-' +k.NO_CONTAINER+ '-' + k.POINT + '-' + k.ID_VES_VOYAGE){
		    pri_class = 'btn-primary';
		}
		htmlListYard += '<a href="#" onclick="jobYard_onClick(this)" data-block="' + k.BLOCK_NAME + '" data-slot="' + k.SLOT_ + '" data-tier="' + k.TIER_ + '" data-row="' + k.ROW_ + '" data-container="' + k.NO_CONTAINER + '" data-id-itv="' + id_itv + '" data-itv="' + itv + '" data-id-truck="' + id_truck+ '" data-tid="' + tid + '" data-job="' + k.JOB + '" data-cont-status="' + k.CONT_STATUS + '" data-iso-code="' + k.ID_ISO_CODE + '">';
		htmlListYard += '<div id="box-' +k.NO_CONTAINER+ '-' + k.POINT + '-' + k.ID_VES_VOYAGE + '" class="box col12 ' + pri_class + '" data-job="' + k.JOB + '" data-id-block="' + k.BLOCK_ + '" data-block-name="' + k.BLOCK_NAME + '" data-no-container="' + k.NO_CONTAINER + '"' 
				+ 'data-point="' + k.POINT + '" data-event="' + k.EVENT + '" data-op-status="' + k.ID_OP_STATUS + '" data-slot="' + k.SLOT_ + '" data-row="' + k.ROW_ + '" data-tier="' + k.TIER_ + '" data-class-code="' + k.ID_CLASS_CODE + '">';
		htmlListYard += k.JOB + ' ' + k.CONT_TYPE + ' ' + commodity + ' ' + k.CONT_SIZE + ' ' + k.ID_CLASS_CODE + ' ' + k.ID_OPERATOR + ' ' + k.NO_CONTAINER + ' <br>';
		htmlListYard += k.ID_VES_VOYAGE + ' ' + k.ID_POD + ' ' + k.PA + ' ' + itv;
		htmlListYard += '</div>';
		htmlListYard += '</a>';
	    });
//		$('#box-position-' + k.JS_TIER + k.JS_ROW).attr('data-id-block', k.GT_JS_BLOCK);
//		$('#box-position-' + k.JS_TIER + k.JS_ROW).attr('data-block-name', k.BLOCK_NAME);
//		$('#box-position-' + k.JS_TIER + k.JS_ROW).attr('data-no-container', k.NO_CONTAINER);
//		$('#box-position-' + k.JS_TIER + k.JS_ROW).attr('data-point', k.POINT);
//		$('#box-position-' + k.JS_TIER + k.JS_ROW).attr('data-event', k.EVENT);
//		$('#box-position-' + k.JS_TIER + k.JS_ROW).attr('data-op-status', k.ID_OP_STATUS);
//		$('#box-position-' + k.JS_TIER + k.JS_ROW).attr('data-slot', k.SLOT);
	    $('#job-list').html(htmlListYard);
	},
	error: function (){
	    alert('Get Job Yard Failed. Check your connection and try again or contact your administrator.');
	}
    });
}

function jobYard_onClick(elm){
    if(!$(elm).children('.box').hasClass('btn-primary')){
	var block = $(elm).attr('data-block');
	var slot = $(elm).attr('data-slot');
	var container = $(elm).attr('data-container');
	var id_itv = $(elm).attr('data-id-itv');
	var itv = $(elm).attr('data-itv');
	var id_truck = $(elm).attr('data-id-truck');
	var tid = $(elm).attr('data-tid');
	var job = $(elm).attr('data-job');
    
	$('.box.btn-primary').removeClass('btn-primary');
	$(elm).children('.box').addClass('btn-primary');
    //    console.log('block : ' + block);
	$('.block-text').removeClass('active');
	$('.block-text').filter(function(){return this.value == block }).addClass('active');
    //    console.log('slot : ' + slot);
	loadSlot();
	$('.slot-text').removeClass('active');
	$('#slot-' + slot).addClass('active');

	if(job == 'GO' || job == 'GI'){
	    $('#info-id-itv').val(id_truck);
	    $('#info-itv').val(tid);
	}else{
	    $('#info-id-itv').val(id_itv);
	    $('#info-itv').val(itv);
	}
	$('#info-container').val(container);
	$('.box-position').removeClass('choosed');
	setBoxYardPosition();
    }else{
	$('.box.btn-primary').removeClass('btn-primary');
    }
}

function jobYard_inactive(){
    $('.box').removeClass('btn-primary');
    $('#btn-jobset').removeClass('btn-primary');
    $('#info-id-itv').val('');
    $('#info-itv').val('');
    $('#info-container').val('');
}

function block_onFocus(e){
    var html = '';
    var className = '';
    $('.block-text').each(function (){
	className = '';
	if($(this).hasClass('active')){
	    className = 'btn-primary';
	}
	html += '<button class="fleft block-list ' + className + '" onclick="choose_block(\'' + $(this).attr('id') + '\')">';
	html += $(this).val();
	html += '</button>';
    });
    $('.popup .panel .panel-body').html(html);
    openPopUp('Choose Block');
}

function choose_block(id){
    $('.block-text').removeClass('active');
    $('#' + id).addClass('active');
    closePopUp();
    loadSlot();
    setBoxYardPosition();
}

function block_scroll(act){
    var c_idx_block = $('.block-text.active').first().attr('data-index');
    var n_idx_block = 0;
    var n2_idx_block = 0;
    
    if(act == 'up'){
	n_idx_block = parseInt(c_idx_block) - 1;
	n2_idx_block = parseInt(c_idx_block) - 2;
    }else{
	n_idx_block = parseInt(c_idx_block) + 1;
	n2_idx_block = parseInt(c_idx_block) + 2;
    }
    
    if($('.block-text.block-' + n_idx_block).length > 0){
	$('.block-text').removeClass('active');
	$('.block-text.block-' + n_idx_block).addClass('active');
	loadSlot();
	setBoxYardPosition();
    }
	
}

function loadSlot(){
    var total_slot = $('.block-text.active').first().attr('data-slot');
    var html = '';
    var className = '';
    for(var i = 1; i <= total_slot; i++){
	className = '';
	if(i == 1) className = 'active';
	html += '<input type="text" id="slot-' + i + '" class="fleft slot-text ' + className + '" onfocus="slot_onFocus()" ';
	html += 'value="' + i + '" ';
	html += 'readonly/>';
    }
    if(html != ''){
	$('#div-slot').html(html);
    }
}

function slot_onFocus(){
    var html = '';
    var className = '';
    $('.slot-text').each(function(){
	className = '';
	if($(this).hasClass('active')){
	    className = 'btn-primary';
	}
	html += '<button class="fleft slot-list ' + className + '" onclick="choose_slot(\'' + $(this).attr('id') + '\')">';
	html += $(this).val();
	html += '</button>';
    });
    $('.popup .panel .panel-body').html(html);
    openPopUp('Choose Slot');
}

function choose_slot(id){
    $('.slot-text').removeClass('active');
    $('#' + id).addClass('active');
    closePopUp();
    setBoxYardPosition();
}

function slot_scroll(act){
    var c_idx = $('.slot-text.active').first().val();
    var n_idx = 0;
    var n2_idx = 0;
    
    if(act == 'up'){
	n_idx = parseInt(c_idx) - 1;
	n2_idx = parseInt(c_idx) - 2;
    }else{
	n_idx = parseInt(c_idx) + 1;
	n2_idx = parseInt(c_idx) + 2;
    }
    
    console.log('#slot-' + n_idx + ' : ' + $('#slot-' + n_idx).length);
    if($('#slot-' + n_idx).length > 0){
//	alert('masuk sini');
	$('.slot-text').removeClass('active');
	$('#slot-' + n_idx).addClass('active');
	setBoxYardPosition();
    }
	
}

function setBoxYardPosition(){
    var row = $('.block-text.active').attr('data-row');
    var tier = $('.block-text.active').attr('data-tier');
    var rowHtml = '';
    var rowNumHtml = '<div style="width: 20px;">&nbsp</div>';
    var tierHtml = '';
    var tierNumHtml = '';
    var blockActive = $('.block-text.active').first().val();
    var slotActive = $('.slot-text.active').first().val();
    
    $('#div-rownum-boxyard').html('');
    
    $('#div-boxyard').html('');
    $('#div-tiernum').html('');
    for(var t = tier; t >= 1; t--){
	tierHtml = '<div class="div-tier">';
	tierNumHtml = '<div class="box-tiernum"><label>' + t + '</label></div>';
	rowHtml = '';
	for(var r = 1; r <= row; r++){
	    if(t == 1){
		rowNumHtml += '<div class="box-rownum">' + r + '</div>';
	    }
	    rowHtml += '<a onclick="chooseBoxPosition(this)">';
	    rowHtml += '<div id="box-position-' + t + r + '" class="fleft box-position" data-row="' + r + '" data-tier="' + t + '">&nbsp</div>';
	    rowHtml += '</a>';
	}
	tierHtml += rowHtml + '</div><br>';
	$('#div-rownum-boxyard').html(rowNumHtml);
	$('#div-boxyard').append(tierHtml);
	$('#div-tiernum').append(tierNumHtml);
    }
    
    $('#job-list a').each(function(){
	var jblock = $(this).attr('data-block');
	var jslot = $(this).attr('data-slot');
	var jtier = $(this).attr('data-tier');
	var jrow = $(this).attr('data-row');
	var jcontainer = $(this).attr('data-container');
	var jitv = $(this).attr('data-itv');
	
	if(jblock == blockActive && jslot == slotActive){
	    if($(this).children('.box').hasClass('btn-primary')){
		$('#box-position-' + jtier + jrow).addClass('job-plan-choosed');
	    }else{
		$('#box-position-' + jtier + jrow).addClass('plan-filled');
	    }
	}
    });
    slotInfo();
}

function slotInfo(){
    var blockActive = $('.block-text.active').first().attr('data-value');
    var slotActive = $('.slot-text.active').first().val();
    
    $.ajax({
	url:'<?= base_url()?>index.php/vmt_controller/slotInfo/',
	method: "GET",
	dataType : 'json',
	async: false,
	data:{
	    block : blockActive,
	    slot : slotActive
	},
	success: function(data){
	    $.each(data, function(i,k){
//		if(k.ID_MACHINE == '<?= $this->gtools->g_session('vmt') ?>'){
		    var commodity = k.ID_COMMODITY != null ? k.ID_COMMODITY : '';
		    if(k.NO_CONTAINER != null){
			var htmlComplete = '';
//			console.log('IS_HKP : ' + k.IS_HKP);
			htmlComplete = '<label>' + k.NO_CONTAINER + '</label><br>';
			if(k.IS_HKP == 0){
			    htmlComplete += '<label>' + k.CONT_SIZE + '</label>&nbsp';
			    htmlComplete += '<label style="color:red">' + commodity + '</label>&nbsp';
			    htmlComplete += '<label style="color:blue">' + k.ID_CLASS_CODE + '</label><br>';
			    htmlComplete += '<label style="color: #337ab7">' + k.CONT_TYPE + '</label><br>';
			    htmlComplete += '<label>' + k.ID_OPERATOR + '</label>&nbsp';
			    htmlComplete += '<div style="text-align:right">' + k.WEIGHT + 'T</div>&nbsp';
    //			console.log('#box-position-' + k.TIER_ + k.ROW_ + ' : ' + k.NO_CONTAINER);
			    $('#box-position-' + k.TIER_ + k.ROW_).attr('data-no-container', k.NO_CONTAINER);
			    $('#box-position-' + k.TIER_ + k.ROW_).attr('data-point', k.POINT);
			    $('#box-position-' + k.TIER_ + k.ROW_).attr('data-event', k.EVENT);
			    $('#box-position-' + k.TIER_ + k.ROW_).attr('data-op-status', k.ID_OP_STATUS);
			    $('#box-position-' + k.TIER_ + k.ROW_).attr('data-class-code', k.ID_CLASS_CODE);
			    $('#box-position-' + k.TIER_ + k.ROW_).addClass('complete');
			    if(k.EVENT == 'O' && $('#box-position-' + k.TIER_ + k.ROW_).hasClass('job-plan-choosed')){
				    $('#box-position-' + k.TIER_ + k.ROW_).addClass('choosed');
			    }
			}else{
			    htmlComplete += '<img style="height: 50%;width:50%;margin: 5px 20px;" src="<?php echo base_url().'assets/images/'; ?>inspect4.png" />';
			}
			$('#box-position-' + k.TIER_ + k.ROW_).html(htmlComplete);
			$('#box-position-' + k.TIER_ + k.ROW_).attr('data-id-block', k.ID_BLOCK);
			$('#box-position-' + k.TIER_ + k.ROW_).attr('data-block-name', k.BLOCK_);
			$('#box-position-' + k.TIER_ + k.ROW_).attr('data-slot', k.SLOT_);
		    }else{
			$('#box-position-' + k.TIER_ + k.ROW_).html('');
			$('#box-position-' + k.TIER_ + k.ROW_).removeAttr('data-id-block');
			$('#box-position-' + k.TIER_ + k.ROW_).removeAttr('data-block-name');
			$('#box-position-' + k.TIER_ + k.ROW_).removeAttr('data-no-container');
			$('#box-position-' + k.TIER_ + k.ROW_).removeAttr('data-point');
			$('#box-position-' + k.TIER_ + k.ROW_).removeAttr('data-event');
			$('#box-position-' + k.TIER_ + k.ROW_).removeAttr('data-op-status');
			$('#box-position-' + k.TIER_ + k.ROW_).removeAttr('data-slot');
			$('#box-position-' + k.TIER_ + k.ROW_).removeClass('complete');
		    }
//		}else{
//		    $('#box-position-' + k.YD_TIER + k.YD_ROW).html('XXXXXXXXXXXXXXXXXXXXXX');
//		    $('#box-position-' + k.YD_TIER + k.YD_ROW).attr('data-machine', k.ID_MACHINE);
//		    $('#box-position-' + k.YD_TIER + k.YD_ROW).attr('data-no-container', k.NO_CONTAINER);
//		}
	    });
	},
	error: function (){
	    alert('Get Job Yard Failed. Check your connection and try again or contact your administrator.');
	}
    });
}

function active_jobset(elem){
    if($('.box.btn-primary').length > 0){
	if($(elem).hasClass('btn-primary')){
	    $(elem).removeClass('btn-primary');
	}else{
	    $(elem).addClass('btn-primary');
	}
    }else{
	alert('Select job order!');
    }
}

function chooseBoxPosition(elem){
    var htmlOri = '';
    var htmlCopy = '';
    if($(elem).children('.box-position').hasClass('complete')){
	if($('.box-position.choosed').length == 0){
	    if($(elem).children('.box-position').hasClass('choosed')){
		$(elem).children('.box-position').removeClass('choosed');
	    }else{
		if($('.box.btn-primary').length > 0 && $('#btn-jobset').hasClass('btn-primary')){
		    alert('Container cannot be set down. Since position already occupied by another container');
		}else{
		    jobYard_inactive();
		    $(elem).children('.box-position').addClass('choosed');
		}
	    }
	}else{
	    alert('Container cannot be set down. Since position already occupied by another container');
	}
    }else{
	if($('.box-position.complete.choosed').length > 0){
	    
	    if(updateLocation(elem,'R')){
//		setBoxYardPosition();
		slotInfo();
	    }
	    $('.box-position.choosed').removeClass('choosed');
	}else{
	    if($('.box.btn-primary').length > 0 && $('#btn-jobset').hasClass('btn-primary')){
		if(updateLocation(elem,'P')){
		    refresh();
		}
	    }else{
		if($('.box.btn-primary').length > 0){
		    jobYard_inactive();
		}
		alert('Empty area!');
		
	    }
	}
    }
}

function updateLocation(elem,act){
//    url: 'yard_job_manager/yard_placement_submit',
//    params: {
//	    no_container: '',
//	    point: '',
//	    id_op_status: '',
//	    event: '',
//	    block_name: '',
//	    id_block: '',
//	    slot: '',
//	    row: '',
//	    tier: '',
//	    yard_placement: '',
//	    id_machine: id_machine,
//	    driver_id: driver_id
//    }
    var no_container = '';
    var point = '';
    var op_status = '';
    var data_event = '';
    var block_name = '';
    var id_block = '';
    var slot = '';
    var row = '';
    var tier = '';
    var class_code = '';
    var result = false;
    var id_machine = act == 'P' ? '<?=$this->gtools->g_session('vmt')?>' : '';
    if($('.box-position.complete.choosed').length > 0){
	var elemBoxChoosed = $('.box-position.complete.choosed').first();
	no_container = $(elemBoxChoosed).attr('data-no-container');
	point = $(elemBoxChoosed).attr('data-point');
	op_status = $(elemBoxChoosed).attr('data-op-status');
	data_event = $(elemBoxChoosed).attr('data-event');
	block_name = $(elemBoxChoosed).attr('data-block-name');
	id_block = $(elemBoxChoosed).attr('data-id-block');
	class_code = $(elemBoxChoosed).attr('data-class-code');
    }else{
	var elemBoxChoosed = $('#job-list').children('a').children('.box.btn-primary').first();
//	console.log(elemBoxChoosed);
	no_container = $(elemBoxChoosed).attr('data-no-container');
	point = $(elemBoxChoosed).attr('data-point');
	op_status = $(elemBoxChoosed).attr('data-op-status');
	data_event = $(elemBoxChoosed).attr('data-event');
	block_name = $(elemBoxChoosed).attr('data-block-name');
	id_block = $(elemBoxChoosed).attr('data-id-block');
	class_code = $(elemBoxChoosed).attr('data-class-code');
    }
    if(data_event == 'P'){
	slot = $('.slot-text.active').first().val();
    }else{
	slot = $(elemBoxChoosed).attr('data-slot');
    }
    row = $(elem).children('.box-position').attr('data-row');
    tier = $(elem).children('.box-position').attr('data-tier');
    
    $.ajax({
	url:'<?= base_url()?>index.php/vmt_controller/yard_placement_submit',
	method: "POST",
	dataType : 'json',
	async: false,
	data:{
	    act: act,
	    no_container: no_container,
	    point: point,
	    id_op_status: op_status,
	    event: data_event,
	    block_name: block_name,
	    id_block: id_block,
	    slot: slot,
	    row: row,
	    tier: tier,
	    id_machine: id_machine,
	    driver_id: <?=$this->gtools->g_session('user')?>,
	    yt: '',
	    class_code: class_code
	},
	success: function(data){
	    if(data[0] == 'F'){
		alert(data[1]);
//		alert('Update Job Yard Failed. Please try again or contact your administrator.');
		result = false;
	    }else{
		result = true;
	    }
	    
	},
	error: function (){
	    alert('Get Job Yard Failed. Check your connection and try again or contact your administrator.');
	    result = false;
	}
    });
    
    return result;
}

function openPopSuspend(elm){
    var html = '';
    var id = $(elm).attr('data-id-suspend');
    var className = '';
    var url = '<?= base_url()?>index.php/vmt_controller/get_suspend_list';
    $.getJSON(url, function(data){
	$.each(data, function(i,j){
	    var choosed = '';
	    var act = 'START';
//	    console.log(j.ID_SUSPEND + ' == ' + id);
	    if(j.ID_SUSPEND == id){ 
		choosed = 'choosed';
		act = 'STOP';
	    }
	    html += '<div class="col6 fleft">';
	    html += '<button class="btn btn-suspend ' + choosed + '" onclick="choose_suspend(' + j.ID_SUSPEND + ',\'' + j.ACTIVITY + '\',\'' + act + '\')">' + j.ACTIVITY + '</button>';
	    html += '</div>';
	});
	$('.popup .panel .panel-body').html(html);
	openPopUp('Choose Suspend');
    });
}

function choose_suspend(id,activity,act){
    $.ajax({
	url:'<?= base_url()?>index.php/vmt_controller/start_suspend',
	method: "POST",
	dataType : 'json',
	async: false,
	data:{
	    id: id,
	    act: act
	},
	success: function(data){
	    if(data != '1'){
		alert(data);
	    }else{
		if(act == 'START'){
		    $('#btn_suspend').attr('data-id-suspend',id);
		    $('#btn_suspend').text(activity);
		}else{
		    $('#btn_suspend').attr('data-id-suspend','');
		    $('#btn_suspend').text('AVAILABLE');
		}
		closePopUp();
	    }
	    
	},
	error: function (){
	    alert('Update Suspend failed. Check your connection and try again or contact your administrator.');
	}
    });
}

function show_itv(){
//    alert('fuck');
    var elm = $('.box.btn-primary').first();
    var job = $(elm).attr('data-job');
    var event = $(elm).attr('data-event');
    console.log(job + ' : ' + event);
    if(job == 'LD' || job == 'MO'){
	var url = '<?= base_url()?>index.php/vmt_controller/get_itv_list';
	$.getJSON(url, function(data){
	    var html = '';
	    if(!data){
		alert('Machine not assign to Pool yet');
	    }else{
		$.each(data, function(i,j){
		    html += '<div class="col6 fleft">';
		    html += '<button class="btn btn-suspend" onclick="choose_itv(' + j.ID_MACHINE + ',\'' + j.MCH_NAME + '\')">' + j.MCH_NAME + '</button>';
		    html += '</div>';
		});
	    }
	    $('.popup .panel .panel-body').html(html);
	    openPopUp('Choose ITV');
	});
    }
}

function choose_itv(id,name){
    $('#info-id-itv').val(id);
    $('#info-itv').val(name);
    closePopUp();
}

function on_chassis(){
    var elemBoxChoosed = $('.box.btn-primary').first();
    var job = $(elemBoxChoosed).attr('data-job');
    if(!$('#btn-jobset').hasClass('btn-primary')){
	alert('Activated Job Set First');
    }else if(job == 'LD' && $('#info-id-itv').val() == ''){
	alert('Choose ITV First');
    }else{
	var no_container = '';
	var point = '';
	var op_status = '';
	var data_event = '';
	var block_name = '';
	var id_block = '';
	var slot = '';
	var row = '';
	var tier = '';
	var class_code = '';

	no_container = $(elemBoxChoosed).attr('data-no-container');
	point = $(elemBoxChoosed).attr('data-point');
	op_status = $(elemBoxChoosed).attr('data-op-status');
	data_event = $(elemBoxChoosed).attr('data-event');
	block_name = $(elemBoxChoosed).attr('data-block-name');
	id_block = $(elemBoxChoosed).attr('data-id-block');
	slot = $(elemBoxChoosed).attr('data-slot');
	row = $(elemBoxChoosed).attr('data-row');
	tier = $(elemBoxChoosed).attr('data-tier');
	class_code = $(elemBoxChoosed).attr('data-class-code');

	$.ajax({
	    url:'<?= base_url()?>index.php/vmt_controller/yard_placement_submit',
	    method: "POST",
	    dataType : 'json',
	    async: false,
	    data:{
		act: 'P',
		no_container: no_container,
		point: point,
		id_op_status: op_status,
		event: data_event,
		block_name: block_name,
		id_block: id_block,
		slot: slot,
		row: row,
		tier: tier,
		id_machine: <?=$this->gtools->g_session('vmt')?>,
		driver_id: <?=$this->gtools->g_session('user')?>,
		yt: $('#info-id-itv').val(),
		class_code: class_code
	    },
	    success: function(data){
		if(data[0] == 'F'){
		    alert(data[1]);
		}else if(data[0] == 'S'){
		    alert('Chassis Success');
		    refresh();
		}else{
		    refresh();
		}

	    },
	    error: function (){
		alert('Get Job Yard Failed. Check your connection and try again or contact your administrator.');
	    }
	});
    }
}

(function() {
  var target = $("#div-rownum")[0];
  $("#div-boxyard").scroll(function() {
//      console.log('scrollLeft : ' + this.scrollLeft);
//    target.prop("scrollLeft", this.scrollLeft);
    target.scrollLeft = this.scrollLeft;
  });
})();

(function() {
  var target = $("#div-tiernum")[0];
  $("#div-boxyard").scroll(function() {
//      console.log('scrollTop : ' + this.scrollTop);
//    target.prop("scrollLeft", this.scrollLeft);
    target.scrollTop = this.scrollTop;
  });
})();

function open_search(){
    var url = '<?= base_url()?>index.php/vmt_controller/search_container_form';
    var html = '';
    var row_class = 'odd';
    $.get(url, function(data){
	$('.popup .panel .panel-body').html(data);
	openPopUp('Search');
    });
    
}
</script>
<img style="height: 100%;width:100%;top: 0px; left: 0px; position: fixed;" src="<?php echo base_url().'assets/images/'; ?>bg_login.jpg" />
<div style="display: inline-flex; height: 100%; position: fixed; width: 100%;">
    <div class="col5 fleft">
	<div class="col12">
	    <button class="btn" onclick="logout()" style="width: calc(50% - 5px)">
		Logout
	    </button>
	    <button id="btn_suspend" class="btn fright" style="width: calc(50% - 5px); " onclick="openPopSuspend(this)" data-id-suspend="<?= $suspend['ID'];?>">
		<?= $suspend['ACTIVITY'];?>
	    </button>
	</div>
	<div class="col12">
	    <input type="text" class="input-form" readonly="" value="<?=$this->gtools->g_session('vmt_name')?>" style="width: calc(30% - 5px)"/>
	    <input type="text" class="input-form" readonly="" value="<?=$this->gtools->g_session('name')?>" style="width: calc(40% - 5px)"/>
	    <button class="btn fright" style="width: calc(25% - 5px)" onclick="open_search()">
		Search
	    </button>
	</div>
        <div class="col12" style="margin-bottom:10px; display: inline-flex; ">
	    <div style="background: lightblue;width: 100%; padding: 5px;">
		<button class="btn job-filter-all" style="width: calc(100% - 270px)" onclick="setActiveBtnJobFilter(this)">
		    ALL
		</button>
		
<?php
//    foreach ($jobs as $job){
?>
		<button class="btn job-filter" style="width: 40px;" onclick="setActiveBtnJobFilter(this)">
		    GI
		</button>
		<button class="btn job-filter" style="width: 40px;" onclick="setActiveBtnJobFilter(this)">
		    GO
		</button>
		<button class="btn job-filter" style="width: 40px;" onclick="setActiveBtnJobFilter(this)">
		    DS
		</button>
		<button class="btn job-filter" style="width: 40px;" onclick="setActiveBtnJobFilter(this)">
		    LD
		</button>
		<button class="btn job-filter" style="width: 40px;" onclick="setActiveBtnJobFilter(this)">
		    MO
		</button>
		<button class="btn job-filter" style="width: 40px;" onclick="setActiveBtnJobFilter(this)">
		    MI
		</button>
<?php
//    }
?>
	    </div>
	</div>
	<div class="col12" style="height: calc(100% - 220px)">
	    <div id="job-list" class="col12" style="background: #fff;overflow: auto;height: 100%"></div>
	</div>

	<div class="col4 fleft">
	    <button id="btn-filter-block" class="btn button filter-block" onclick="btnFilterBlock_click(this)" >
		BLOCK
	    </button>
	</div>
	<div class="col4 fleft">
	    <button id="btn-filter-block-all"  class="btn button filter-block btn-primary" onclick="btnFilterBlock_click(this)" >
		ALL
	    </button>
	</div>
	<div class="col4 fleft" >
	    <button id="btn-jobset" class="btn button" onclick="active_jobset(this)" >
		JOB SET
	    </button>
	</div>
    </div>

    <div class="col7 fright">    
	<div class="col12 fleft">
	    <div class="fleft" style="width: 45%; margin-right: 10px;">
		<button id="block-scroll-up" class="fleft" style="height: 48px;width: 60px" onclick="block_scroll('up')">
		    <img src="<?php echo base_url().'assets/images/'; ?>up_arrow.png" />
		</button>
<?php
	if(count($yard_block) > 0){
	    $i = 0;
	    foreach ($yard_block as $val){
?>
		<input type="text" id="block-<?=$val['ID_BLOCK']?>" class="fleft block-<?=$i?> block-text <?php if($i == 0){ ?>active<?php } ?>" onfocus="block_onFocus(this)" 
		       value="<?=$val['BLOCK_NAME']?>"
		       data-index="<?=$i?>"
		       data-value="<?=$val['ID_BLOCK']?>" 
		       data-slot="<?=$val['SLOT_']?>" 
		       data-row="<?=$val['ROW_']?>" 
		       data-tier="<?=$val['TIER_']?>" 
		       readonly/>
<?php
		$i++;
	    }
	}else{
?>
		<input type="text" id="" class="fleft block-text active" onfocus="" value="" data-index="" data-value="" data-slot="" data-row="" data-tier="" readonly/>
<?php
	}
?>
		<button id="block-scroll-down" class="fleft" style="height: 48px;width: 60px" onclick="block_scroll('down')">
		    <img src="<?php echo base_url().'assets/images/'; ?>down_arrow.png" />
		</button>
	    </div>
	    <div class="fleft" style="width: 30%; margin-right: 10px;">
		<button class="fleft" style="height: 48px;width: 60px" onclick="slot_scroll('up')">
		    <img src="<?php echo base_url().'assets/images/'; ?>up_arrow.png"  />
		</button>
		<div id="div-slot" class="fleft">
		    <input type="text" id="" class="fleft slot-text active" onfocus="" value="" readonly/>
		</div>
		<button class="fleft" style="height: 48px;width: 60px" onclick="slot_scroll('down')">
		    <img src="<?php echo base_url().'assets/images/'; ?>down_arrow.png" />
		</button>
	    </div>
	    <button class="fright" style="width: calc(25% - 20px);height: 47.07px;font-weight: bold; font-size: 20px;" onclick="refresh()">
		REFRESH
	    </button>
	</div>
	<div class="col12 fleft">
	    <div class="col5 fleft">
		<input id="info-id-itv" type="hidden" class="input-form" onfocus="show_itv()" readonly=""/>
		<input id="info-itv" type="text" class="input-form" onfocus="show_itv()" readonly=""/>
	    </div>
	    <div class="col7 fleft">
		<input id="info-container" type="text" class="input-form" readonly=""/>
	    </div>
<!--	    <div class="col4 fright">
		<button class="btn">Virtual Block</button>
	    </div>-->
	</div>
        <!-- BOX -->
        <div class="col12 fleft" style="background-color: lightblue; height: calc(100% - 180px);">
	    <div id="div-rownum" >
		<div id="div-rownum-boxyard"></div>
	    </div>
	    <div id="div-tiernum"></div>
	    <div id="div-boxyard"></div>
        </div>
	<div class="col12 fleft" style="height: 80px;">
<!--	    <div class="col6 fleft">
		<button class="btn button">Change Loc</button>
	    </div>-->
	    <div class="col12 fleft">
		<button class="btn button" onclick="on_chassis()">Chassis</button>
	    </div>
	</div>
    </div>
</div>
      