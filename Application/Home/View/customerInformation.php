<?php 
    define("URLROOT", "http://localhost:8080/tp");
    session_start();
    $_SESSION["cName"] = $_REQUEST["cName"]; 
    $_SESSION["cId"] = $_REQUEST["cId"];
?>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="<?php echo URLROOT?>/Application/Common/easyui/themes/default/easyui.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo URLROOT?>/Application/Common/easyui/themes/icon.css" type="text/css"/>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/jQuery/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/easyui/locale/easyui-lang-zh_CN.js"></script>
		<style type="text/css">
            .information{border:1px solid green;}
        </style>
		<script type="text/javascript">
    		$(function(){
    			$.fn.datebox.defaults.formatter = function(date){
        			var y = date.getFullYear();
        			var m = date.getMonth()+1;
        			var d = date.getDate();
        			return y+'-'+m+'-'+d;
        		}
    			$.post('<?php echo URLROOT?>/index.php/Home/User/User/customerInformation?cId=<?php echo $_REQUEST["cId"]?>',function(data){
        			var customer = data[0];
        			$("#tb_customer").append('<tr><td>客户名：</td><td class="information">'+customer["cname"]+'</td><td>性别：</td><td class="information">'+customer["csex"]+'</td><td>电话：</td><td class="information">'+customer["cphone"]+'</td></tr>');
        			$("#tb_customer").append('<tr><td>等级：</td><td class="information">'+customer["clevel"]+'</td><td>阶段：</td><td class="information">'+customer["cmoment"]+'</td></tr>');
        			$("#tb_customer").append('<tr><td>创建时间：</td><td colspan =5 class="information">'+customer["ccreattime"]+'</td></tr>');
        			$("#tb_customer").append('<tr><td>地址：</td><td colspan =5 class="information">'+customer["caddress"]+'</td></tr>');
        			$("#tb_customer").append('<tr><td>主题：</td><td style="height:50px;" colspan =5 class="information">'+customer["ctopic"]+'</td></tr>');
        		},"json");
        		
    			$('#dg').datagrid({    
    			    url:'<?php echo URLROOT?>/index.php/Home/User/User/loadRecord?eId=<?php echo $_SESSION["eId"];?>&pageNo=1&pageSize=10',   
    			    striped:true,
    			    pageList:[5,10,15],
    			    pagination:true,
    			    rownumbers:true,
    			    frozenColumns:[[
    					{field:'hfdhs',checkbox:true}
    			    ]],
    			    columns:[[    
    			        {field:'rid',title:'编号',width:100,align:'center',hidden:true},    
    			        {field:'cname',title:'客户姓名',width:100,align:'center'},    
    			        {field:'rcontent',title:'联系内容',width:90,align:'center'},  
    			        {field:'rthistime',title:'本次联系时间',width:180,align:'center'},  
    			        {field:'rnexttime',title:'下次联系时间',width:180,align:'center'},  
    			        {field:'rpretime',title:'预约上门时间',width:180,align:'center'}, 
    			        {field:'cmoment',title:'客户当前状态',width:90,align:'center'} 
    			    ]],
    			    toolbar: [{
    					text   : '删除',
    					iconCls: 'icon-delete',
    					handler: function(){
    						var rows = $("#dg").datagrid("getChecked");
    		        		if(rows.length == 0){
    		    				alert("对不起，请先选中一行进行删除！");
    							return;
    		        		}
    		        		if (confirm("你确定删除吗？")) {  
    		        			$.post("<?php echo URLROOT?>/index.php/Home/User/User/dealDeleteRecord",{row:rows},function(data){
    		    					if(data >= 1){
    		    						alert("操作成功，正在重新载入！");
    		    					}else{
    		    						alert("操作失败");
    		    					}
    		    					window.location.href="";
    		                   	},"json");
    				        }
        				}
    				}]
    			});
    			var pager = $("#dg").datagrid("getPager");
    			pager.pagination({
    				onSelectPage:function(pageNumber, pageSize){
    					$("#dg").datagrid('loading');
    					$.post('<?php echo URLROOT?>/index.php/Home/User/User/loadRecord?cId=<?php echo $_SESSION["cId"];?>&eId=<?php echo $_SESSION["eId"];?>&pageNo='+pageNumber+"&pageSize="+pageSize,function(data){
    						$("#dg").datagrid("loadData",{
    							rows:data.rows,
    							total:data.total
    						});
    						$("#dg").datagrid('loaded');
    					},"json");
    				}
    			});
    		});

    		function dealAddRecord(){
    			$.ajax({  
    		        type:"POST",   
    		        data:$('#ff').serialize(),// 你的formid  
    		        url:"<?php echo URLROOT?>/index.php/Home/User/User/dealAddRecord",  
    		        success:function(data){  
    		        	if(data >= 1){
    						alert("添加成功，正在重新载入！");
    					}else{
    						alert("添加失败");
    					}
    		            window.location.href=""; 
    		        }
    		    }); 
        	}
		</script>
	</head>
	<body class="easyui-layout">
        <div data-options="region:'north',title:'客户详细信息',split:true" style="height:200px;">
        	<table id="tb_customer" style="width:95%;margin:auto;"></table>
        </div>   
		<div data-options="region:'center',title:'历史探访记录',split:true" style="background:#eee;">
			<table id="dg"></table>
		</div> 
		<div data-options="region:'south',title:'添加探访记录',split:true" style="background:#eee;height:150px;">
			<form id="ff">
				<input name = "eId" type = "hidden" value = '<?php echo $_SESSION["eId"] ;?>' />
				<input name = "cId" type = "hidden" value = '<?php echo $_SESSION["cId"] ;?>' />
				<span >
					<label>用户名：</label>
					<input type = "text" name = "cName" readonly value='<?php echo $_SESSION["cName"];?>' /> 
				</span>
				<span>
					<label>联系内容：</label>
					<input type = "text" name = "rContent" /> 
				</span>
				<span>
					<label>本次联系时间和日期：</label>
					<input id="rThisTime" class="easyui-datetimebox" required="required" type="text" name = "rThisTime" /> 
				</span>
				<br/>
				<span>
					<label>下次联系时间和日期：</label>
					<input id="rNextTime" class="easyui-datetimebox" required="required" type="text" name = "rNextTime" /> 
				</span>
				<span>
					<label>预约上门时间：</label>
					<input id="rPreTime" class="easyui-datetimebox" required="required" type="text" name = "rPreTime" /> 
				</span>
				<span>
					<label>客户当前状态：</label>
					<select name="cMoment">
						<option value="0">请选择客户目前阶段</option>
						<option value="1">售前跟踪</option>
						<option value="2">合同执行</option>
						<option value="3">售后服务</option>
					</select>
				</span>
				<div style="width:150px;margin:auto;">
    				<input type="button" value="确认提交" onclick="dealAddRecord()">  
    				<input type="reset" value="取消" />
				</div>
			</form>
		</div>
    </body> 
</html>