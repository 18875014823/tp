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
		<style type="text/css">
            ul,li{list-style:none;margin:0px;}
            .level1{width:24%;float:left;padding:0px;}
            #sure{clear:both;position:relative;top:20px;}
        </style>
		<script type="text/javascript">
    		$(function(){
    			$('#addJobWin').window('close');
    			$('#editEmployJobWin').window('close');
    			$('#editJobMenuWin').window('close');
    			$('#dg').datagrid({    
    			    url:'<?php echo URLROOT?>/index.php/Home/User/User/loadJobList?pageNo=1&pageSize=10',   
    			    striped:true,
    			    pagination:true,
    			    rownumbers:true,
    			    frozenColumns:[[
    					{field:'hfdhs',checkbox:true}
    			    ]],
    			    columns:[[    
    			        {field:'jid',title:'职位编号',width:100,align:'center'},    
    			        {field:'jname',title:'职位名称',width:200,align:'center'} 
    			    ]],
    			    toolbar: [{
    				    text   : '添加',
    					iconCls: 'icon-add',
    					handler: function(){
    		    			$('#addJobWin').window('open');
        				}
    				},'-',{
    					text   : '修改用户角色',
    					iconCls: 'icon-edit',
    					handler: function(){
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
    		        			$("#jId").val(rows[0][0]); 
        		        		$("#jName").val(rows[0][1]); 
    		        			$("#EmployList").empty();
    		        			var row = rows[0];
    		        			$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadAllEmployJob?jId="+row[0],function(data){
									for(var i = 0; i < data.length; i++){
										$("#EmployList").append('<input type="checkbox" name="eId[]" value="'+data[i][0]+'"'+(data[i][2]==1?' checked':' ')+'/>'+data[i][1]);
									}
    		                   	},"json");
    		        			$('#editEmployJobWin').window('open');
    				        }
        				}
    				},'-',{
    					text   : '修改角色菜单',
    					iconCls: 'icon-add2',
    					handler: function(){
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
    		        			$("#jId2").val(rows[0][0]); 
        		        		$("#jName2").val(rows[0][1]);
    		        			$("#menuList").empty();
    		        			var row = rows[0];
    		        			$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadAllJobMenu?jId="+row[0],function(data){
									for(var i = 0; i < data.length; i++){
										if(data[i][3]==1){
											$("#menuList").append('<ul class="level1"><li><input type="checkbox" onclick="selectAll(this)" name="mId[]" value = "'+data[i][0]+'"'+(data[i][5]==1?' checked':' ')+'/>'+data[i][1]+'<ul id="ul'+data[i][0]+'"></ul><li></ul>');
											for(var j=0;j<data.length;j++){
    											if(data[j][3]==2 && data[j][4]==data[i][0]){
    												$("#ul"+data[i][0]).append('<li><input class="secondLevel" type="checkbox" name="mId[]" value = "'+data[j][0]+'"'+(data[j][5]==1?' checked':' ')+'/>'+data[j][1]+'</li>');	
    											}
    										}
										}
									}
    		                   	},"json");
    		        			$('#editJobMenuWin').window('open');
    				        }
        				}
    				}]
    			});
    			var pager = $("#dg").datagrid("getPager");
    			pager.pagination({
    				onSelectPage:function(pageNumber, pageSize){
    					$("#dg").datagrid('loading');
    					$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadJobList?pageNo="+pageNumber+"&pageSize="+pageSize,function(data){
    						$("#dg").datagrid("loadData",{
    							rows:data.rows,
    							total:data.total
    						});
    						$("#dg").datagrid('loaded');
    					},"json");
    				}
    			});

    		});
    		function selectAll(obj){
        		if($(obj).parent().find(".secondLevel").prop("checked")){
    				$(obj).parent().find(".secondLevel").prop("checked",false);
        		}else{
        			$(obj).parent().find(".secondLevel").prop("checked",true);
            	}
        	}
			function editEmployeeJob(){
				$.post("<?php echo URLROOT?>/index.php/Home/User/User/editEmployeeJob",$("#employJob").serialize(),function(data){
        			if(data >= 1){
						alert("修改成功，正在重新加载")
            		}else{
						alert("修改失败");
                	}
                	window.location.href="";
               	},"json");
    		}
			function editJobMenu(){
				$.post("<?php echo URLROOT?>/index.php/Home/User/User/editJobMenu",$("#jobMenu").serialize(),function(data){
					if(data >= 1){
						alert("修改成功，正在重新加载")
            		}else{
						alert("修改失败");
                	}
                	window.location.href="";
               	},"json");
    		}
    		function addJob(){
    			$.post("<?php echo URLROOT?>/index.php/Home/User/User/addJob",$("#addJob").serialize(),function(data){
					if(data >= 1){
						alert("添加成功，正在重新加载")
            		}else{
						alert("添加失败");
                	}
                	window.location.href="";
               	},"json");
        	}
		</script>
	</head>
	<body>
		<table id="dg"></table>
		<div id="editEmployJobWin" class="easyui-window"  title="修改用户角色" style="width:500px;height:350px;position: relative;top:30px;"   
        	data-options="iconCls:'icon-add2',modal:true">  
        	<form id = "employJob">
        		<input type="hidden" id="jId" name="jId">
        		<br/>
                <div class="form-group">
                	<label for="jName">职位名称：</label>
                	<input id="jName" type = "text" readonly name = "jName"/>
                </div>
                <br/>
                <div class="form-group">
                	<label for="jName">该职位的人员：</label>
                	<span id="EmployList"></span>
                </div>
        		<br/>
        		<div style="width: 100%;text-align:center;">
            		<button type="button" onclick="editEmployeeJob()">确认修改</button>
                    <input type="reset" value="取消"/>
        		</div>
        	</form> 
        </div>  
        <div id="editJobMenuWin" class="easyui-window"  title="修改角色菜单" style="width:550px;height:380px;position: relative;top:30px;"   
        	data-options="iconCls:'icon-add2',modal:true">   
            <form id = "jobMenu">
        		<input type="hidden" id="jId2" name="jId">
        		<br/>
                <div class="form-group">
                	<label for="jName">职位名称：</label>
                	<input id="jName2" type = "text" readonly name = "jName"/>
                </div>
        		<br/>
                <div class="form-group">
                	<label for="jName">该职位的菜单：</label>
                	<div id="menuList"></div>
                </div>
        		<div id="sure" style="margin-top:20px;width: 100%;text-align:center;">
            		<button type="button" onclick="editJobMenu()">确认修改</button>
                    <input type="reset" value="取消"/>	
        		</div>
        	</form> 
        </div>  
        <div id="addJobWin" class="easyui-window"  title="增加职位" style="width:300px;height:200px;position: relative;top:30px;"   
        	data-options="iconCls:'icon-add2',modal:true">   
            <form id = "addJob">
        		<br/>
                <div style="width: 100%;text-align:center;" class="form-group">
                	<label for="jName">职位名称：</label>
                	<input id="jName2" type = "text" name = "jName"/>
                </div>
                <br/>
        		<div style="width: 100%;text-align:center;">
            		<button type="button" onclick="addJob()">确认添加</button>
                    <input type="reset" value="取消"/>	
        		</div>
        	</form> 
        </div>  
	</body>
</html>
	
