<?php 
    define("URLROOT", "http://localhost:8080/tp");
    session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>用户登录</title>
		<link rel="stylesheet" href="<?php echo URLROOT?>/Application/Common/easyui/themes/default/easyui.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo URLROOT?>/Application/Common/easyui/themes/icon.css" type="text/css"/>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/jQuery/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript">
    		function addHouse(){
    			$.post("<?php echo URLROOT?>/index.php/Home/User/User/addHouse",$("#houseForm").serialize(),function(data){
					if(data >= 1){
						alert("操作成功，正在重新加载!!!");
					}else{
						alert("操作失败");
					}
					window.location.href="";
				},"json");
        	}
		</script>
	</head>
	<body>
		<div id="addHouseWin" class="easyui-window"  title="楼盘信息" style="width:400px;height:300px;position: relative;top:30px;"   
        	data-options="iconCls:'icon-add2',modal:true">   
            <form id = "houseForm">
            	<input type="hidden" id = "hId" name = "hId" value=""/>
            	<input type="hidden" id = "deal" name = "deal" value=""/>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label for="hArea">地区：</label>
            		<input style="width:30%;" type="text" id="hArea" name="hArea"/>  
            		<label for="hDevelop">开发商：</label>
            		<input style="width:30%;" type="text" id="hDevelop" name="hDevelop"/>     		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label for="hPrice">价格：</label>
            		<input style="width:30%;" type="text" id="hPrice" name="hPrice"/> 
            		<label for="hSort">户型：</label>
            		<input style="width:30%;" type="text" id="hSort" name="hSort"/>      		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label for="hMore">更多详情：</label>
            		<textarea id="hMore" name="hMore" style="width:70%;"></textarea>     		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;">
            		<button type="button" onclick="addHouse()">确认提交</button>
            		<input type="reset" value="取消"/>
            	</div>
        	</form> 
        </div>  
	</body>
</html>
	
