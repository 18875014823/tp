<?php 
    define("ROOT", "http://localhost:8080/tp");
    session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>用户登录</title>
		<link rel="stylesheet" href="<?php echo ROOT?>/Application/Common/easyui/themes/default/easyui.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo ROOT?>/Application/Common/easyui/themes/icon.css" type="text/css"/>
		<script type="text/javascript" src="<?php echo ROOT?>/Application/Common/jQuery/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="<?php echo ROOT?>/Application/Common/easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript">
    		function addEmployee(){
    			$.post("<?php echo ROOT?>/index.php?c=User&a=addEmployee",$("#employeeForm").serialize(),function(data){
					alert(data);
					window.location.href="";
				},"json");
        	}
		</script>
	</head>
	<body>
		<div id="addEmployeeWin" class="easyui-window"  title="添加员工" style="width:400px;height:320px;position: relative;top:30px;"   
        	data-options="iconCls:'icon-add2',modal:true">   
            <form id = "employeeForm" method="post" action="<?php echo ROOT?>/index.php?c=User&a=addEmployee">
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label for="sCreatTime">员工姓名：</label>
            		<input style="width:200px;" type="text" id="eName" name="eName"/>     		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label for="sCreatTime">员工工号：</label>
            		<input style="width:200px;" type="text" id="eNo" name="eNo"/>     		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label for="sCreatTime">登录密码：</label>
            		<input style="width:200px;" type="text" value="123456" id="pwd" name="pwd"/>     		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label for="sTargetTime">员工性别：</label>
            		<input type="radio" value=0 id="eSex" checked name="eSex"/>男     	
            		<input type="radio" value=1 id="eSex" name="eSex"/>女	
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label>员工电话：</label>
            		<input style="width:200px;" type="text" name="ePhone" id="ePhone">          		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;">
            		<button type="submit" >确认添加</button>
            		<input type="reset" value="取消"/>
            	</div>
        	</form> 
        </div>  
	</body>
</html>
	
