<style>
    .table.login tr td.label{
        text-align: right;
        color: #ffffff;
    }

    .button-number{
        background: #cccccc;
        width: 100%;
        height: 100%;
	font-size: 50px;
	font-weight: bold;
    }

    .div-button{
        height: calc(25% - 10px);
    }
    
    .btn-action label{
	font-size: 20px;
    }
    
    .input-form{
        font-size: 30px;
    }
</style>
<script>
    function onFocus_input(i){
        $('.input-form').removeClass('active');
        $(i).addClass('active');
    }

    function button_number_onclick(n){
        var text = $('.input-form.active').val();
        $('.input-form.active').val(text + n.toString());
        login();
    }

    function button_cancel_onclick(){
        $('.input-form').val('');
	$('#username').focus();
    }

    function login(){
        var username = $('#username').val();
        var password = $('#password').val();
        if(username != '' && password != ''){
            $.ajax({
                url:'<?= base_url()?>index.php/vmt_controller/auth',
                method: "POST",
                dataType : 'json',
                data:{
                    username : username,
                    password : password
                },
                success: function(result){
                    console.log(result);
                    if(result.STATUS){
                        $('#status_login').val(1);
                        $('#name').val(result.NAME);
                        $('#user').val(result.ID_USER);
			$.ajax({
			    url:'<?= base_url()?>index.php/vmt_controller/get_terminal',
			    method: "POST",
			    dataType : 'json',
			    data:{
				id_user : result.ID_USER
			    },
			    success: function(data){
				var opt = '';
				$.each(data, function(x,y){
				    opt += '<option value="' + y.ID_TERMINAL + '">' + y.TERMINAL_CODE + '-' + y.TERMINAL_NAME + '</option>';
				});
				$('#terminal').html(opt);
			    }
			});
                    }else{
                        $('#terminal').html('<option value="">-- Choose Terminal --</option>');
			$('#name').val('');
                        $('#status_login').val('');
                        $('#user').val('');
		    }
                },
                error: function (){
                    alert('Login Failed. Check your connection and try again or contact your administrator.');
                }
            });
        }
    }
    
    function getVMT(){
	var vmt_type = $('#vmt_type').val();
	var html = '<option value="">-- Choose VMT --</option>';
	if(vmt_type != ''){
	    var url = "<?= base_url()?>index.php/vmt_controller/getVMT?vmt_type=" + vmt_type;
	    $.getJSON(url, function(data, status){
		if(status == 'success'){
		    
		    $.each(data, function(i,k){
			html += '<option value="' + k.ID_MACHINE + '">' + k.MCH_NAME + '</option>';
		    });
		    $('#vmt').html(html);
		}else{
		    alert('Load VMT list failed. Please check connection and try again or contact your administrator');
		}
	    });
	}
    }
</script>
<input type="hidden" id="status_login"/>
<input type="hidden" id="user"/>
<img style="height: 100%;width:100%;top: 0px; left: 0px; position: fixed;" src="<?php echo base_url().'assets/images/'; ?>bg_login.jpg" />
<div style="position: fixed; top: 7%; text-align: center; width: 100%;">
    <div style="margin: 10px auto; display: inline-flex; width: 80%;">
        <div class="col5 fleft" style="text-align: left">
	    <div class="col6 fleft" style="margin-bottom: 3%;">
		<input id="username" name="username" type="text" class="input-form" placeholder="Username" onfocus="onFocus_input(this)" />
	    </div>
	    <div class="col6 fleft" style="margin-bottom: 3%;">
		<input id="password" name="password" type="password" class="input-form" placeholder="Password" onfocus="onFocus_input(this)" onchange="login()" />
	    </div>
	    <div class="col12" style="margin-bottom: 3%;">
		<select id="terminal" name="terminal" class="input-form" onchange="getVMT()" >
		    <option value="">-- Choose Terminal --</option>
		</select>
	    </div>
	    <div class="col12" style="margin-bottom: 3%;">
		<input id="name" name="name" type="text" class="input-form" placeholder="Name" disabled />
	    </div>
	    <div class="col12" style="margin-bottom: 3%;">
		<select id="vmt_type" name="vmt_type" class="input-form" onchange="getVMT()" >
		    <option value="">-- Choose VMT Type --</option>
<?php
	    foreach ($machine_type as $val){
?>
		    <option value="<?=$val['MCH_SUB_TYPE']?>"><?=$val['MCH_SUB_TYPE']?></option>
<?php
	    }
?>
		</select>
	    </div>
	    <div class="col12" style="margin-bottom: 3%;">
		<select id="vmt" name="vmt" class="input-form" >
		    <option value="">-- Choose VMT --</option>
		</select>
	    </div>
	    <div class="col12" style="margin-bottom: 3%;">
		<select id="yard" name="yard" class="input-form" >
		    <option value="">-- Choose Yard --</option>
<?php
	    foreach ($yard as $val){
?>
		    <option value="<?=$val['ID_YARD']?>"><?=$val['YARD_NAME']?></option>
<?php
	    }
?>
		</select>
	    </div>
        </div>
        <div class="col7 fleft" style="text-align: left">
	    <div class="col4 fleft div-button">
                <button class="button-number" onclick="button_number_onclick(1)">
                    <label>1</label>
                </button>
            </div>
            <div class="col4 fleft div-button">
                <button class="button-number" onclick="button_number_onclick(2)">
                    <label>2</label>
                </button>
            </div>
            <div class="col4 fleft div-button">
                <button class="button-number" onclick="button_number_onclick(3)">
                    <label>3</label>
                </button>
            </div>
            <div class="col4 fleft div-button">
                <button class="button-number" onclick="button_number_onclick(4)">
                    <label>4</label>
                </button>
            </div>
            <div class="col4 fleft div-button">
                <button class="button-number" onclick="button_number_onclick(5)">
                    <label>5</label>
                </button>
            </div>
            <div class="col4 fleft div-button">
                <button class="button-number" onclick="button_number_onclick(6)">
                    <label>6</label>
                </button>
            </div>
            <div class="col4 fleft div-button">
                <button class="button-number" onclick="button_number_onclick(7)">
                    <label>7</label>
                </button>
            </div>
            <div class="col4 fleft div-button">
                <button class="button-number" onclick="button_number_onclick(8)">
                    <label>8</label>
                </button>
            </div>
            <div class="col4 fleft div-button">
                <button class="button-number" onclick="button_number_onclick(9)">
                    <label>9</label>
                </button>
            </div>
            <div class="col4 fleft div-button">
                <button class="button-number" onclick="button_number_onclick(0)">
                    <label>0</label>
                </button>
            </div>
            <div class="col8 fleft div-button">
                <button class="button-number" onclick="button_cancel_onclick()">
                    <label>Clear</label>
                </button>
            </div>
	</div>
    </div>
    <div style="margin: 0px auto; display: inline-flex; width: 80%; text-align: center;">
        <div class="col6">
            <button class="btn btn-default btn-large btn-action" onclick="connect()">
                <label>CONNECT</label>
            </button>
        </div>
        <div class="col6">
            <button class="btn btn-default btn-large btn-action">
                <label>CLOSE</label>
            </button>
        </div>
    </div>
</div>