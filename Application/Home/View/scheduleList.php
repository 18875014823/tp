<?php 
    define("URLROOT", "http://localhost:8080/tp");
    session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>我的日程</title>
		<link rel="stylesheet" href="<?php echo URLROOT?>/Application/Common/easyui/themes/default/easyui.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo URLROOT?>/Application/Common/easyui/themes/icon.css" type="text/css"/>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/jQuery/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/easyui/locale/easyui-lang-zh_CN.js"></script>
		<script type="text/javascript">
    		$(function(){
    			$.fn.datebox.defaults.formatter = function(date){
        			var y = date.getFullYear();
        			var m = date.getMonth()+1;
        			var d = date.getDate();
        			return y+'-'+m+'-'+d;
        		}
        		$("#addScheduleWin").window("close");
    			$('#dg').datagrid({    
    			    url:'<?php echo URLROOT?>/index.php/Home/User/User/loadSchedule?pageNo=1&pageSize=10<?php 
    			         if (isset($_REQUEST["date"])){
    			             echo "&date=".$_REQUEST["date"];
    			         }
    			    ?>',   
    			    striped:true,
    			    pagination:true,
    			    rownumbers:true,
    			    frozenColumns:[[
    					{field:'hfdhs',checkbox:true}
    			    ]],
    			    columns:[[    
    			        {field:'sid',title:'编号',width:100,align:'center'},    
    			        {field:'eid',title:'用户Id',align:'center',hidden:'true'},  
    			        {field:'scontent',title:'日程内容',width:400,align:'center'},  
    			        {field:'screattime',title:'创建时间',width:150,align:'center'},     
    			        {field:'stargettime',title:'指定时间',width:150,align:'center'},  
    			    ]],
    			    toolbar: [{
    				    text   : '添加',
    					iconCls: 'icon-add',
    					handler: function(){
   							var mydate = new Date();
							var str = mydate.getFullYear()+"-"+(mydate.getMonth()+1)+"-"+mydate.getDate();
							$("#sCreatTime").val(str);
							$("#sTargetTime").val(str);
    		        		$("#addScheduleWin").window("open");
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
    		        			$.post("<?php echo URLROOT?>/index.php/Home/User/User/deleteSchedule",{row:rows},function(data){
    	    						if(data >= 1){
        	    						alert("删除成功，正在重新加载！！");
        	    					}else{
										alert("删除失败！！");
            	    				}
            	    				window.location.href = "";
    	    					},"json");
    				        }
        				}
    				}]
    			});
    			var pager = $("#dg").datagrid("getPager");
    			pager.pagination({
    				onSelectPage:function(pageNumber, pageSize){
    					$("#dg").datagrid('loading');
    					$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadSchedule?pageNo="+pageNumber+"&pageSize="+pageSize,function(data){
    						$("#dg").datagrid("loadData",{
    							rows:data.rows,
    							total:data.total
    						});
    						$("#dg").datagrid('loaded');
    					},"json");
    				}
    			});
    		});
    		function addSchedule(){
    			$.post("<?php echo URLROOT?>/index.php/Home/User/User/addSchedule",$("#scheduleForm").serialize(),function(data){
					if(data >= 1){
						alert("添加日程成功，正在重新加载!!!");
					}else{
						alert("添加日程失败");
					}
					window.location.href="";
				},"json");
        	}
		</script>
	</head>
	<body>
		<table id="dg"></table>
		<div id="addScheduleWin" class="easyui-window"  title="添加日程" style="width:400px;height:300px;position: relative;top:30px;"   
        	data-options="iconCls:'icon-add2',modal:true">   
            <form id = "scheduleForm">
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label for="sCreatTime">创建日期：</label>
            		<input style="width:200px;" class="easyui-datetimebox" required="required" type="text" id="sCreatTime" name="sCreatTime"/>     		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label for="sTargetTime">指定日期：</label>
            		<input style="width:200px;" class="easyui-datetimebox" required="required" type="text" id="sTargetTime" name="sTargetTime"/>     		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;" class="form-group">
            		<label for="sContent">日程内容：</label>
					<textarea style="width:200px;" name="sContent"></textarea>            		
            	</div>
            	<br/>
            	<div style="width:100%; text-align:center;">
            		<button type="button" onclick="addSchedule()">确认提交</button>
            		<input type="reset" value="取消"/>
            	</div>
        	</form> 
        </div>  
	</body>
</html>
	
