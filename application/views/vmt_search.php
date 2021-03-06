<style>
    .button-number{
	font-weight: bold;
	font-size: 20px;
    }
    
    .table {
	background: #f2f2f2;
	width: 100%;
    }
    
    .table thead tr {
	background: linear-gradient(#fff, #5da4f5);
	font-weight: bolder;
	text-align: center;
	height: 30px;
	font-size: 20px;
    }
    
    .table tbody tr.odd{
	background: #fff;
    }
    
    .table tbody tr.even{
	background: #aed4f5;
    }
    
    .table tbody tr.selected{
	background: #FFFF66;
    }
    
    #div-search-content{
	height: 330px;
	overflow: auto;
    }
</style>

<script>
    function button_number_onclick(n){
        var text = $('#input-search').val();
	if(text.length < 5){
	    $('#input-search').val(text + n.toString());
	}else{
	    alert('Cannot search more than 5 digits');
	}
    }
    
    function clear_search(){
	$('#input-search').val("");
    }
    
    function doSearch(){
	var search = $('#input-search').val();
	if(search.length == 5){
	    $.ajax({
		url:'<?= base_url()?>index.php/vmt_controller/doSearch/',
		method: "POST",
		dataType : 'json',
		async: false,
		data:{
		    cont : search
		},
		success: function(data){
		    var htmlListYard = '';
		    var row_class = 'odd';
		    $.each(data, function(i,k){
			htmlListYard += '<tr class="' + row_class + '" onclick="choose_search(this)" data-container="' + k.NO_CONTAINER + '"\n\
					     data-point="' + k.POINT + '"  data-ves-voyage="' + k.ID_VES_VOYAGE + '" data-yd-block="' + k.YD_BLOCK + '" data-yd-slot="' + k.YD_SLOT + '">';
			htmlListYard += '<td>' + k.NO_CONTAINER + '</td>';
			htmlListYard += '<td>' + k.ID_ISO_CODE + '</td>';
			htmlListYard += '<td>' + k.CONT_STATUS + '</td>';
			htmlListYard += '<td>' + (k.TID != null ? k.TID : '') + '</td>';
			htmlListYard += '<td>' + (k.WAIT_JOB != null ? k.WAIT_JOB : '') + '</td>';
			htmlListYard += '<td>' + (k.POSITION_ != null ? k.POSITION_ : '') + '</td>';
			htmlListYard += '<td>' + (k.EXPECTED_POSITION != null ? k.EXPECTED_POSITION : '') + '</td>';
			htmlListYard += '</tr>';
			row_class = row_class == 'odd' ? 'even' : 'odd';
		    });
		    $('#div-search-content table tbody').html(htmlListYard);
		},
		error: function (){
		    alert('Get Job Yard Failed. Check your connection and try again or contact your administrator.');
		}
	    });
	}else{
	    alert('Must be 5 characters');
	}
    }
    
    function choose_search(e){
    	if($(e).hasClass('selected')){
	    var cont = $(e).attr('data-container');
	    var point = $(e).attr('data-point');
	    var ves_voyage = $(e).attr('data-ves-voyage');
	    if($('#box-' + cont + '-' + point + '-' + ves_voyage).length > 0){
		$('#box-' + cont + '-' + point + '-' + ves_voyage).parent().click();
	    }else{
		var block = $(e).attr('data-yd-block');
		var slot = $(e).attr('data-yd-slot');
		
		$('.block-text').removeClass('active');
		$('#block-' + block).addClass('active');
		$('.slot-text').removeClass('active');
		$('#slot-' + slot).addClass('active');
		setBoxYardPosition();
	    }
	    closePopUp();
	}else{
	    $('#div-search-content table tbody tr').removeClass('selected');
	    $(e).addClass('selected');
	}
    }
</script>
<div id="div-search-form">
    <div id="div-search-header" style="display: flex;">
	<div class="col3">
	    <div class="col12">
		<input id="input-search" type="text" class="input-form" style="font-size: 60px;" maxlength="5"/>
	    </div>
	</div>
	<div class="col7">
	    <div class="col2 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(1)">
                    <label>1</label>
                </button>
            </div>
	    <div class="col2 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(2)">
                    <label>2</label>
                </button>
            </div>
	    <div class="col2 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(3)">
                    <label>3</label>
                </button>
            </div>
	    <div class="col2 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(4)">
                    <label>4</label>
                </button>
            </div>
	    <div class="col2 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(5)">
                    <label>5</label>
                </button>
            </div>
	    <div class="col2 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(6)">
                    <label>6</label>
                </button>
            </div>
	    <div class="col2 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(7)">
                    <label>7</label>
                </button>
            </div>
	    <div class="col2 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(8)">
                    <label>8</label>
                </button>
            </div>
	    <div class="col2 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(9)">
                    <label>9</label>
                </button>
            </div>
	    <div class="col2 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(0)">
                    <label>0</label>
                </button>
            </div>
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="clear_search()">
                    <label>Clear</label>
                </button>
            </div>
	</div>
	<div class="col2">
	    <div class="col12">
		<button class="btn button-number" onclick="doSearch()">
		    <label>Search</label>
		</button>
	    </div>
	    <div class="col12">
		<button class="btn button-number" onclick="">
		    <label>Close</label>
		</button>
	    </div>
	</div>
    </div>
    <div id="div-search-content">
	<table class="table">
	    <thead>
		<tr>
		    <td>Container</td>
		    <td>ISO</td>
		    <td>FM</td>
		    <td>Truck No</td>
		    <td>Wait Job</td>
		    <td>Position</td>
		    <td>Expected Pos.</td>
		</tr>
	    </thead>
	    <tbody></tbody>
	</table>
    </div>
</div>