<style>
    .button-number{
	font-weight: bold;
	font-size: 30px;
	height: 70px;
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
	if(text.length < 7){
	    var new_text = text + n.toString();
	    $('#input-search').val(new_text);
//	    var len = new_text.length;
//	    console.log('len : ' + len);
//	    if(len == 6){
//		$('#input-search').css('font-size','50px');
//	    }else if(len == 7){
//		$('#input-search').css('font-size','40px');
//	    }
	}else{
	    alert('Cannot search more than 7 digits');
	}
    }
    
    function clear_search(){
	$('#input-search').val("");
	$('#input-search').css('font-size','60px');
    }
    
    function backspace_click(){
	var text = $('#input-search').val();
	var res = text.substring(0,text.length - 1);
	$('#input-search').val(res);
    }
    
    function doSearch(){
	$('#search-job').val($('#input-search').val());
	getYardManager();
	closePopUp();
    }
    
    
</script>
<div id="div-search-form">
    <!--<div id="div-search-header" style="display: flex;">-->
	<div class="col12">
	    <input id="input-search" type="text" class="input-form" style="font-size: 60px; text-align: right;" maxlength="7"/>
	</div>
	<div class="col9 fleft">
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(1)">
                    <label>1</label>
                </button>
            </div>
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(2)">
                    <label>2</label>
                </button>
            </div>
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(3)">
                    <label>3</label>
                </button>
            </div>
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(4)">
                    <label>4</label>
                </button>
            </div>
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(5)">
                    <label>5</label>
                </button>
            </div>
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(6)">
                    <label>6</label>
                </button>
            </div>
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(7)">
                    <label>7</label>
                </button>
            </div>
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(8)">
                    <label>8</label>
                </button>
            </div>
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(9)">
                    <label>9</label>
                </button>
            </div>
	    <div class="col4 fleft div-button">
                <button class="btn button-number" onclick="button_number_onclick(0)">
                    <label>0</label>
                </button>
            </div>
	    <div class="col8 fleft div-button">
                <button class="btn button-number" onclick="clear_search()">
                    <label>Clear</label>
                </button>
            </div>
	</div>
	<div class="col3 fright">
	    <div class="col12">
		<button class="btn button-number" onclick="backspace_click()">
		    <label>Backspace</label>
		</button>
	    </div>
	    <div class="col12">
		<button class="btn button-number" onclick="doSearch()" style="height: 230px;">
		    <label>Search</label>
		</button>
	    </div>
	</div>
    <!--</div>-->
    
</div>