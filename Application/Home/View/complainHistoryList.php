<?php 
    define("URLROOT", "http://localhost:8080/tp");
    session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>投诉信息</title>
		<link rel="stylesheet" href="<?php echo URLROOT?>/Application/Common/easyui/themes/default/easyui.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo URLROOT?>/Application/Common/easyui/themes/icon.css" type="text/css"/>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/jQuery/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/easyui/locale/easyui-lang-zh_CN.js"></script>
		<script type="text/javascript">
    		$(function(){
    			$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadAllEmployJob",function(data){
        			$("#eId").empty;
        			for(var i=0; i<data.length; i++){
                        $("#eId").append('<option value="'+data[i][0]+'">'+data[i][1]+'</option>');
            		}
               	},"json");

               	$.post("<?php echo URLROOT?>/index.php/Home/User/User/customerList",function(data){
        			$("#cId").empty;
        			for(var i=0; i<data.length; i++){
                        $("#cId").append('<option value="'+data[i][0]+'">'+data[i][1]+'</option>');
            		}
               	},"json");
        		
    			$.fn.datebox.defaults.formatter = function(date){
        			var y = date.getFullYear();
        			var m = date.getMonth()+1;
        			var d = date.getDate();
        			return y+'-'+m+'-'+d;
        		}
    			$('#addComplainWin').window('close');
    			$('#editEmployJobWin').window('close');
    			$('#editJobMenuWin').window('close');
    			$('#dg').datagrid({    
    			    url:'<?php echo URLROOT?>/index.php/Home/User/User/loadComplainList?pageNo=1&pageSize=10&comDealResult=处理完成',   
    			    striped:true,
    			    pagination:true,
    			    rownumbers:true,
    			    frozenColumns:[[
    					{field:'hfdhs',checkbox:true}
    			    ]],
    			    columns:[[    
    			        {field:'comid',title:'编号',width:100,align:'center'},    
    			        {field:'comtheme',title:'投诉主题',width:200,align:'center'} ,
    			        {field:'comsort',title:'分类',width:100,align:'center'},  
    			        {field:'cname',title:'对应客户',width:100,align:'center'},  
    			        {field:'comdate',title:'日期',width:200,align:'center'},  
    			        {field:'comlevel',title:'紧急程度',width:100,align:'center'},  
    			        {field:'ename',title:'接待人',width:100,align:'center'},  
    			        {field:'comdealresult',title:'处理结果',width:100,align:'center'}
    			    ]],
    			    toolbar: [{
    				    text   : '添加',
    					iconCls: 'icon-add',
    					handler: function(){
    						$("#deal").val(1);
    		    			$('#addComplainWin').window('open');
        				}
    				},'-',{
    					text   : '编辑',
    					iconCls: 'icon-edit',
    					handler: function(){
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
    		        			$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadComplain?comId="+row[0],function(data){
									var complain = data[0];
									$("#cId").val(complain[3]);
									$("#eId").val(complain[6]);
									$("#comTheme").val(complain[1]);
									if(complain[2] == "服务投诉"){
										$("#comSort").val(0);
									}
									if(complain[2] == "产品投诉"){
										$("#comSort").val(1);
									}
									$("#comDescibe").val(complain[13]);
									$("#comDate").val(complain[4]);
									$("#comCostTime").val(complain[9]);
									
									if(complain[5] == "非常紧急"){
                                        $("#level1").attr("checked","checked");
									}
									if(complain[5] == "紧急"){
                                        $("#level2").attr("checked","checked");
									}
									if(complain[5] == "普通"){
                                        $("#level3").attr("checked","checked");
									}
									$("#comDealProcess").val(complain[7]);
									if(complain[8] == "未处理"){
										$("#result1").attr("checked","checked");
									}
									if(complain[8] == "处理中"){
										$("#result2").attr("checked","checked");
									}
									if(complain[8] == "处理完成"){
										$("#result3").attr("checked","checked");
									}
									$("#comBack").val(complain[10]);
									$("#comSure").val(complain[11]);
									$("#comMore").val(complain[12]);
									
    		                   	},"json");
    		        			$('#addComplainWin').window('open');
    				        }
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
    		        			$.post("<?php echo URLROOT?>/index.php/Home/User/User/deleteComplain",{row:rows},function(data){
    		        				if(data >= 1){
    		    						alert("删除成功，正在重新加载")
    		                		}else{
    		    						alert("删除失败");
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
        	function addComplain(){
        		$.post("<?php echo URLROOT?>/index.php/Home/User/User/addComplain",$("#addComplain").serialize(),function(data){
					if(data >= 1){
						alert("操作成功，正在重新加载")
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
        <div id="addComplainWin" class="easyui-window"  title="录入投诉" style="width:450px;height:550px;position: relative;top:30px;"   
        	data-options="iconCls:'icon-add2',modal:true">   
            <form id = "addComplain">
            	<input type="hidden" name="deal" id ="deal" value=""/>
        		<br/>
                <div style="width: 100%;text-align:center;" class="form-group">
                	<label for="cId">对应客户：</label>
                	<select id="cId" style="width: 30%" name = "cId">
                	</select>
                	<label for="eId">接待人：</label>
                	<select id="eId" style="width: 30%" name = "eId">
                	</select>
                </div>
                <div style="margin-top:5px;width: 100%;text-align:center;" class="form-group">
                	<label for="comTheme">投诉主题：</label>
                	<input id="comTheme" style="width: 30%" type = "text" name = "comTheme"/>
                	<label for="comSort">分类：</label>
                	<select name="comSort" style="width: 30%">
                		<option value = "0">服务投诉</option>
                		<option value = "1">产品投诉</option>
                	</select>
                </div>
                <div style="margin-top:5px;width: 100%;text-align:center;" class="form-group">
                	<label for="comDescibe">描述：</label>
                	<textarea name="comDescibe" style="width: 70%"></textarea>
                </div>
                <div style="margin-top:5px;width: 100%;text-align:center;" class="form-group">
                	<label for="comDate">日期：</label>
                	<input id="comDate" style="width: 30%" class="easyui-datetimebox" required="required" type="text" name = "comDate"/>
                	<label for="comCostTime">花费时间：</label>
                	<input id="comCostTime" style="width: 30%" type = "text" name = "comCostTime"/>
                </div>
                <div style="margin-top:5px;width: 100%;text-align:center;" class="form-group">
                	<label for="comLevel">紧急程度：</label>
                	<input id="level1" type = "radio" value="0" checked name = "comLevel"/>非常紧急
                	<input id="level2" type = "radio" value="1" name = "comLevel"/>紧急
                	<input id="level3" type = "radio" value="2" name = "comLevel"/>普通
                </div>
                <div style="margin-top:5px;width: 100%;text-align:center;" class="form-group">
                	<label for="comDealProcess">处理过程：</label>
                	<textarea name="comDealProcess" style="width: 70%"></textarea>
                </div>
                <div style="margin-top:5px;width: 100%;text-align:center;" class="form-group">
                	<label for="comDealResult">处理结果：</label>
                	<input id="result1" type = "radio" value="0" checked name = "comDealResult"/>未处理
                	<input id="result2" type = "radio" value="1" name = "comDealResult"/>处理中
                	<input id="result3" type = "radio" value="2" name = "comDealResult"/>处理完成
                </div>
                <div style="margin-top:5px;width: 100%;text-align:center;" class="form-group">
                	<label for="comBack">客户反馈：</label>
                	<textarea name="comBack" style="width: 30%"></textarea>
                	<label for="comSure">回访确认：</label>
                	<textarea name="comSure" style="width: 30%"></textarea>
                </div>
                <div style="margin-top:5px;width: 100%;text-align:center;" class="form-group">
                	<label for="comMore">备注：</label>
                	<textarea name="comMore" style="width: 70%"></textarea>
                </div>
        		<div style="margin-top:5px;width: 100%;text-align:center;">
            		<button type="button" onclick="addComplain()">确认添加</button>
                    <input type="reset" value="取消"/>	
        		</div>
        	</form> 
        </div>  
	</body>
</html>
	
