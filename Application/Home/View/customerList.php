<?php 
    define("URLROOT", "http://localhost:8080/tp");
    session_start();
?>
<html>
	<head>
		<title></title>
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
        		};
    			$('#win').window('close');
        		$("#mytable tr:lt(4)").css({height:"35px"});
    			$("#tree").tree({
    				url:'<?php echo URLROOT;?>/index.php/Home/User/User/testTree',
    				lines:true,
    				animate:true,
    				checkbox:true,
    				onSelect:function(node){
    					$("#customerInformation").attr("src","customerInformation.php?cId="+node.id+"&cName="+node.text);
    				}
    			});
    		});
    		function edit(){
               	$("#deal").val(1);
    			var rows = $("#tree").tree("getChecked");
    			var cId = "";
    			var len = 0;
        		for(var i=0; i<rows.length; i++){
            		if(rows[i].id >= 0){
						len++;
                    }
                }
        		if(len == 0){
    				alert("对不起，请先选中一行进行编辑！");
					return;
        		}
    			if(len > 1){
					alert("对不起，你只能选中一行进行编辑！");
					return;
				}
                if($("#tree").tree("getChildren").length == 2){
                	cId = rows[1].id;
                }
                if($("#tree").tree("getChildren").length > 2){
                	cId = rows[0].id;
                }
    			if (confirm("你确定修改吗？")) {
    				$.post("<?php echo URLROOT?>index.php/Home/User/customerInformation&cId="+cId,function(data){
    					var customer = data[0];
    					$("#customerList #cName").val(customer[1]);
    					if(customer[2] == '女'){
							$("#woman").attr("checked","checked");
        				}else{
        					$("#man").attr("checked","checked");
            			};
    					$("#customerList #cPhone").val(customer[3]);
    					$("#customerList #cAddress").val(customer[4]);
						if(customer[5] == "潜在客户"){
        					$("#cLevel option[value = 1]").attr("selected","selected");
    			        }
    			        if(customer[5] == "普通客户"){
    			        	$("#cLevel option[value = 2]").attr("selected","selected");
    			        }
    			        if (customer[5] == "VIP客户"){
    			        	$("#cLevel option[value = 3]").attr("selected","selected");
    			        }
    			        if (customer[5] == "失效客户"){
    			        	$("#cLevel option[value = 4]").attr("selected","selected");
    			        }
    			        if (customer[5] == "代理商"){
    			        	$("#cLevel option[value = 5]").attr("selected","selected");
    			        }
    			        if (customer[5] == "合作伙伴"){
    			        	$("#cLevel option[value = 6]").attr("selected","selected");
    			        }

    					$("#customerList #cCreatTime").val(customer[6]);
    					
    					if (customer[7] == "售前跟踪"){
    			        	$("#cMoment option[value = 1]").attr("selected","selected");
    			        }
    					if (customer[7] == "合同执行"){
    			        	$("#cMoment option[value = 2]").attr("selected","selected");
    			        }
    					if (customer[7] == "售后服务"){
    			        	$("#cMoment option[value = 3]").attr("selected","selected");
    			        }
    			        
    					$("#customerList #cTopic").val(customer[8]);
                   	},"json");
        			$('#win').window('open');
		        }
        	}

        	function deleteCustomer(){
        		var rows = $("#tree").tree("getChecked");
        		if(rows.length == 0){
    				alert("对不起，请先选中一行进行删除！");
					return;
        		}
        		if (confirm("你确定删除吗？")) {  
            		var str = new Array();
            		for(var i=0; i<rows.length; i++){
                		if(rows[i].id >= 0){
							str[i] = rows[i].id;
                        }
                    }
            		$.post("<?php echo URLROOT?>index.php/Home/User/dealDeleteCustomer",{cIds:str},function(data){
    					if(data >= 1){
    						alert("操作成功，正在重新载入！");
    					}else{
    						alert("操作失败");
    					}
    					window.location.href="";
                   	},"json");
                	$('#win').window('close');
		        }
           	}
           	function addCustomer(){
               	$("#deal").val(0);
               	$('#customerList')[0].reset();
           		$('#win').window('open');
            }
			
            function saveOrUpdateCustomer(){
            	$.post("<?php echo URLROOT?>index.php?c=User&a=dealCustomer",$("#customerList").serialize(),function(data){
					if(data == 1){
						alert("操作成功，正在重新载入！");
					}else{
						alert("操作失败");
					}
					window.location.href="";
               	},"json");
            	$('#win').window('close');
            }
		</script>
	</head>
	<body class="easyui-layout">     
        <div data-options="region:'west',title:'我的客户',split:true" style="width:200px;">
        	<a href="javascript:addCustomer();" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true">增加</a>  
        	<a href="javascript:deleteCustomer();" class="easyui-linkbutton" data-options="iconCls:'icon-delete',plain:true">删除</a>
        	<a href="javascript:edit();" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true">修改</a>
        	<ul id="tree"></ul>
        </div>   
        
		<div data-options="region:'center',title:'客户信息'" style="background:#eee;">
			<iframe id="customerInformation" style="border: 0px;" width="99%" height="99%"></iframe>
		</div> 
		
		<div id="win" class="easyui-window" title="新增/编辑客户" style="width:500px;height:350px;position: relative;top:30px;"   
        	data-options="iconCls:'icon-add2',modal:true">   
			<form id="customerList" action="">
				<input id="deal" type="hidden" name="deal" value="">
				<table id="mytable" style="width:98%;margin:auto;" >
					<tr>
						<td>客户名：</td>
						<td>
							<input id="cName" name="cName" type="text" placeholder="请输入客户姓名">
						</td>
						<td>性别：</td>
						<td>
							<input id="man" name="cSex" value="0" type="radio">男
							<input id="woman" name="cSex" value="1" type="radio">女
						</td>
					</tr>
					<tr>
						<td>电话：</td>
						<td>
							<input id="cPhone" name="cPhone" type="text" placeholder="请输入客户电话">
						</td>
						<td>等级：</td>
						<td>
							<select id="cLevel" name="cLevel">
								<option value="0">请选择客户等级</option>
								<option value="1">潜在客户</option>
								<option value="2">普通客户</option>
								<option value="3">VIP客户</option>
								<option value="4">失效客户</option>
								<option value="5">代理商</option>
								<option value="6">合作伙伴</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>阶段：</td>
						<td>
							<select id="cMoment" name="cMoment">
								<option value="0">请选择客户目前阶段</option>
								<option value="1">售前跟踪</option>
								<option value="2">合同执行</option>
								<option value="3">售后服务</option>
							</select>
						</td>
						<td>创建时间：</td>
						<td>
							<input id="cCreatTime" name="cCreatTime" class="easyui-datebox" required="required" type="text">
						</td>
					</tr>
					<tr>
						<td>地址：</td>
						<td colspan=3>
							<input id="cAddress" name="cAddress" style="width:400px;" type="text" placeholder="请输入客户地址">
						</td>
					</tr>
					<tr>
						<td>主题：</td>
						<td style="height:100px;" colspan=3>
							<textarea id="cTopic" name="cTopic" style="width:400px;"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan=4 style="text-align: center;">
							<button type="button" onclick="saveOrUpdateCustomer();">提交</button>
							<input type="reset" value="取消"/>
						</td>
					</tr>
				</table>
			</form>
		</div>
    </body> 
</html>