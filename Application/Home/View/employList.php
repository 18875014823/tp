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
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/easyui/locale/easyui-lang-zh_CN.js"></script>
		<script type="text/javascript">
    		$(function(){
    			$("#addEmployeeWin").window("close");

    			$('#dg').datagrid({    
    			    url:'<?php echo URLROOT?>/index.php/Home/User/User/loadEmployeeList?pageNo=1&pageSize=10',   
    			    striped:true,
    			    pagination:true,
    			    rownumbers:true,
    			    frozenColumns:[[
    					{field:'hfdhs',checkbox:true}
    			    ]],
    			    columns:[[    
    			        {field:'eid',title:'编号',width:100,align:'center'},    
    			        {field:'eno',title:'工号',width:200,align:'center'},  
    			        {field:'ename',title:'姓名',width:200,align:'center',formatter:function(value,row,index){
    						return "<b style='color:red;'>"+value+"</b>";
    				    }},  
    			        {field:'pwd',title:'密码',width:90,align:'center',hidden:true},     
    			        {field:'esex',title:'性别',width:90,align:'center'},   
    			        {field:'phone',title:'电话',width:90,align:'center'}, 
    			        {field:'estates',title:'状态',width:90,align:'center'}, 
    			    ]],
    			    toolbar: "#tb"
    			});
    			var pager = $("#dg").datagrid("getPager");
    			pager.pagination({
    				onSelectPage:function(pageNumber, pageSize){
    					$("#dg").datagrid('loading');
    					$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadEmployeeList?pageNo="+pageNumber+"&pageSize="+pageSize,$("#searchForm").serialize(),function(data){
    						$("#dg").datagrid("loadData",{
    							rows:data.rows,
    							total:data.total
    						});
    						$("#dg").datagrid('loaded');
    					},"json");
    				}
    			});
				$("#forStates").change(function(){
					$("#dg").datagrid('loading');
					$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadEmployeeList?pageNo="+1+"&pageSize="+10,$("#searchForm").serialize(),function(data){
						$("#dg").datagrid("loadData",{
							rows:data.rows,
							total:data.total
						});
						$("#dg").datagrid('loaded');
					},"json");
				});
    			$("#forName").blur(function (){
    				$("#dg").datagrid('loading');
					$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadEmployeeList?pageNo="+1+"&pageSize="+10,$("#searchForm").serialize(),function(data){
						$("#dg").datagrid("loadData",{
							rows:data.rows,
							total:data.total
						});
						$("#dg").datagrid('loaded');
					},"json");
        		});
    			$("#forPhone").blur(function (){
    				$("#dg").datagrid('loading');
					$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadEmployeeList?pageNo="+1+"&pageSize="+10,$("#searchForm").serialize(),function(data){
						$("#dg").datagrid("loadData",{
							rows:data.rows,
							total:data.total
						});
						$("#dg").datagrid('loaded');
					},"json");
        		});
    		});
    		function addEmployee(){
    			$.post("index.php?c=User&a=addEmployee",$("#employeeForm").serialize(),function(data){
					if(data >= 1){
						alert("操作成功，正在重新加载!!!");
					}else{
						alert("操作失败");
					}
					window.location.href="";
				},"json");
        	}
    		function btn_add_click(){
        		$("#employeeForm")[0].reset();
				$("#deal").val(1);
    			$("#addEmployeeWin").window("open");
			}
    		function btn_edit_click(){
        		$("#deal").val(0);
				var rows = $("#dg").datagrid("getChecked");
        		if(rows.length == 0){
    				alert("对不起，请先选中一行进行编辑！");
					return;
        		}
        		if(rows.length > 1){
					alert("对不起，你只能选中一行进行编辑！");
					return;
				}
        		if (confirm("你确定修改吗？")) {  
        			var row = rows[0];
        			$("#eId").val(row[0]);
        			$.post("index.php?c=User&a=loadEmployee&eId="+row[0],function(data){
						$("#eName").val(data[0][2]);
						$("#eNo").val(data[0][1]);
						$("#pwd").val(data[0][3]);
						if(data[0][4] == "男"){
							$("#man").attr("checked","checked");
    					}
						if(data[0][4] == "女"){
							$("#woman").attr("checked","checked");
    					}
						$("#ePhone").val(data[0][5]);
						$("#eStates").val(data[0][6]);
					},"json");
        			$("#addEmployeeWin").window("open");
		        }
			}
		</script>
	</head>
	<body>
		<table id="dg"></table>
		<div id="tb">
			<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" href="javascript:btn_add_click();">添加</a>
			<a class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" href="javascript:btn_edit_click();">修改</a>
			<form id = "searchForm">
    			<label>按姓名查找:&nbsp;</label>
    			<input id="forName" name="forName" style="width:80px;" type="text"/>
    			<label>按电话查找:&nbsp;</label>
    			<input id="forPhone" name="forPhone" style="width:80px;" type="text"/>
    			<label>按状态查找:&nbsp;</label>
    			<select id="forStates" name="forStates">
        			<option value="">请选择状态</option>
        			<option value="在职">在职</option>
        			<option value="离职">离职</option>
    			</select>
    		</form>
		</div>
		<div id="addEmployeeWin" class="easyui-window"  title="员工" style="width:400px;height:400px;position: relative;top:30px;"   
        	data-options="iconCls:'icon-add2',modal:true">   
            <form id = "employeeForm">
            	<input type="hidden" name="deal" id ="deal" value=""/>
            	<input type="hidden" name=eId id ="eId" value=""/>
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
            		<input type="radio" id="man" value=0 checked name="eSex"/>男     	
            		<input type="radio" id="woman" value=1 name="eSex"/>女	
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label>员工电话：</label>
            		<input style="width:200px;" type="text" name="ePhone" id="ePhone">          		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label>员工状态：</label>
            		<select id="eStates" name="eStates">
            			<option value="在职">在职</option>
            			<option value="离职">离职</option>
            		</select>         		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;">
            		<button type="button" onclick="addEmployee()">确认</button>
            		<input type="reset" value="取消"/>
            	</div>
        	</form> 
        </div>  
	</body>
</html>
	
