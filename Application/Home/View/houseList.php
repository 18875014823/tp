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
    		$(function(){
    			$('#addHouseWin').window('close');
    			$('#dg').datagrid({    
    			    url:'<?php echo URLROOT?>/index.php/Home/User/User/loadHouseList?pageNo=1&pageSize=10',   
    			    striped:true,
    			    pagination:true,
    			    rownumbers:true,
    			    frozenColumns:[[
    					{field:'hfdhs',checkbox:true}
    			    ]],
    			    columns:[[    
    			        {field:'hid',title:'楼盘编号',width:30,align:'center'},    
    			        {field:'harea',title:'地区',width:200,align:'center'},     
    			        {field:'hdevelop',title:'开发商',width:200,align:'center'},      
    			        {field:'hprice',title:'价格',width:200,align:'center'},    
    			        {field:'hsort',title:'户型',width:200,align:'center'},     
    			        {field:'hmore',title:'更多详情',width:200,align:'center'}, 
    			    ]],
    			    toolbar: [{
    				    text   : '添加',
    					iconCls: 'icon-add',
    					handler: function(){
    						$("#deal").val(0);
    						$("#bt_reset").click();
    						$('#addHouseWin').window('open');
        				}
    				},'-',{
    					text   : '删除',
    					iconCls: 'icon-delete',
    					handler: function(){
    						var rows = $("#dg").datagrid("getChecked");
    		        		if(rows.length == 0){
    		    				alert("对不起，请先选中一行进行删除！");
    							return;
    		        		}
    		        		if (confirm("你确定删除吗？")) {
    		        			$.post("<?php echo URLROOT?>/index.php/Home/User/User/deleteHouse",{row:rows},function(data){
    		        				if(data >= 1){
    		    						alert("删除成功，正在重新加载!!!");
    		    					}else{
    		    						alert("删除失败");
    		    					}
    		    					window.location.href="";
    		                   	},"json");
    				        }
        				}
    				},'-',{
    					text   : '修改楼盘信息',
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
    		        			$("#deal").val(1); 
    		        			var row = rows[0];
    		        			$("#hId").val(row["hid"]); 
    		        			$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadPickHouse?hId="+row["hid"],function(data){
        		        			$("#hArea").val(data[0]["harea"]);
        		        			$("#hDevelop").val(data[0]["hdevelop"]);
        		        			$("#hPrice").val(data[0]["hprice"]);
        		        			$("#hSort").val(data[0]["hsort"]);
        		        			$("#hMore").val(data[0]["hmore"]);
    		                   	},"json");
    		        			$('#addHouseWin').window('open');
    				        }
        				}
    				}]
    			});
    			var pager = $("#dg").datagrid("getPager");
    			pager.pagination({
    				onSelectPage:function(pageNumber, pageSize){
    					$("#dg").datagrid('loading');
    					$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadMenuList?pageNo="+pageNumber+"&pageSize="+pageSize,function(data){
    						$("#dg").datagrid("loadData",{
    							rows:data.rows,
    							total:data.total
    						});
    						$("#dg").datagrid('loaded');
    					},"json");
    				}
    			});
    		});
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
		<table id="dg"></table>
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
            		<input type="reset" id="bt_reset" value="取消"/>
            	</div>
        	</form> 
        </div>  
	</body>
</html>
	
