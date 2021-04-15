<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>VMT</title>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery-3.1.1.js'); ?>"></script>
        <link href="<?= base_url('assets/css/main-styles.css'); ?>" rel="stylesheet">
        <link href="<?= base_url('assets/css/table-styles.css'); ?>" rel="stylesheet">
        <script>
	    var setTO;
            $(document).ready(function(){
<?php
	if($this->gtools->isLogin()){
?>
		$.ajax({
                        url:'<?= base_url()?>index.php/vmt_controller/vmt_view',
                        method: "POST",
                        data:{
                            username:'<?=$this->gtools->g_session('username')?>',
                            name:'<?=$this->gtools->g_session('name')?>',
                            vmt:'<?=$this->gtools->g_session('vmt')?>',
                            vmt_name:'<?=$this->gtools->g_session('vmt_name')?>',
                            yard:'<?=$this->gtools->g_session('yard')?>',
                            yard_name:'<?=$this->gtools->g_session('yard_name')?>',
                            terminal:'<?=$this->gtools->g_session('terminal')?>',
                            user:'<?=$this->gtools->g_session('user')?>'
                        },
                        success: function(html){
                            $('#contents').html(html);
                        },
                        error: function (result){
                            alert('Fail to connect. Check your connection and try again or contact your administrator.');
                            console.log(result);
                        }
                    });
<?php
	}else{
?>
                var url = '<?= base_url()?>index.php/vmt_controller/vmt_login';
                $.get(url, function(html){
                    $('#contents').html(html);
                });
<?php
	}
?>
            });
            
            function connect(){
		if($('#status_login').length == 1 && $('#status_login').val() == 1){
		    if($('#vmt_type').val() == ''){
			alert('Choose VMT Type!');
		    }else if($('#vmt').val() == ''){
			alert('Choose VMT');
		    }else if($('#yard').val() == ''){
			alert('Choose Yard');
		    }else if($('#terminal').val() == ''){
			alert('Choose Terminal');
		    }else{
			$.ajax({
			    url:'<?= base_url()?>index.php/vmt_controller/vmt_view',
			    method: "POST",
			    data:{
				username:$('#username').val(),
				name:$('#name').val(),
				vmt:$('#vmt').val(),
				vmt_name:$("#vmt option[value='" + $('#vmt').val() + "']").text(),
				yard:$('#yard').val(),
				yard_name:$("#yard option[value='" + $('#yard').val() + "']").text(),
				terminal:$("#terminal").val(),
				user:$("#user").val()
			    },
			    success: function(html){
				$('#contents').html(html);
//				refresh();
			    },
			    error: function (result){
				alert('Fail to connect. Check your connection and try again or contact your administrator.');
				console.log(result);
			    }
			});
		    }
		}else{
		    alert('Incorrect username and password');
		}
            }
	    
	    function logout(){
		$.ajax({
		    url:'<?= base_url()?>index.php/vmt_controller/logout',
		    method: "POST",
		    success: function(html){
			clearTimeout(setTO);
			var url = '<?= base_url()?>index.php/vmt_controller/vmt_login';
			$.get(url, function(html){
			    $('#contents').html(html);
			});
		    },
		    error: function (result){
			alert('Fail to connect. Check your connection and try again or contact your administrator.');
			console.log(result);
		    }
		});
	    }
            
	    function openPopUp(label = ''){
		$('.popup-background').show();
		$('.popup').show();
		if(label == ''){
		    $('.popup .panel .panel-header').hide();
		}else{
		    $('.popup .panel .panel-header').show();
		    $('.popup .panel .panel-header label').text(label);
		}
	    }
	    
	    function closePopUp(){
		$('.popup-background').hide();
		$('.popup').hide();
	    }
        </script>
    </head>
    <body>
	<div id="contents"></div>
	<div class="popup-background" style="display: none"></div>
	<div class="popup" style="display: none">
	    <div class="panel">
		<div class="panel-header">
		    <label></label>
		    <a href="javascript:;" onclick="closePopUp()" class="text-danger fright">X</a>
		</div>
		<div class="panel-body">
		    Choose Block
		</div>
	    </div>
	</div>
    </body>
</html>