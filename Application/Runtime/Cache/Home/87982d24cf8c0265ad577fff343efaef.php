<?php if (!defined('THINK_PATH')) exit(); define("URLROOT", "http://localhost:8080/tp"); session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>用户登录</title>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/jQuery/jquery-1.9.1.min.js"></script>
		<link rel="stylesheet" href="<?php echo URLROOT?>/Application/Common/dist/css/bootstrap.min.css" type="text/css"/>
		<script type="text/javascript" src="<?php echo URLROOT?>/Application/Common/dist/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			//跳转页面，type=0表示按值跳转，type=1表示向前跳转，type=2表示向后跳转
			function turnPage(pageNo,pageSize,type=0){
				if(type == 2){
					window.location.href="<?php echo URLROOT?>/index.php/Home/User/User/loadHouseByPage?pageNo="+(pageNo+1);
				}else if(type == 1){
					if(pageNo == 1){
						alert("当前已经是第一页了");
					}else{
						window.location.href="<?php echo URLROOT?>/index.php/Home/User/User/loadHouseByPage?pageNo="+(pageNo-1);
					}
				}else{
					window.location.href="<?php echo URLROOT?>/index.php/Home/User/User/loadHouseByPage?pageNo="+pageNo;
				}
			}
			function addOrUpdateHouse(type){
				$("#cancel").click();
				if(type == 0){
					$("#hId").val(-1);
					$("#openWin").click();
				}else{
					hid = $("#mytable input:checked");
					if(hid.length == 0){
						alert("对不起，请先选择一行进行编辑！！！");
						return;
					}
					if(hid.length > 1){
						alert("对不起，您只能选择一行进行编辑！！！");
						return;
					}
					if (confirm("你确定修改吗？")) {
						var hid = hid.eq(0).attr("value");
						$("#hId").val(hid);
	        			$.post("<?php echo URLROOT?>/index.php/Home/User/User/loadPickHouse?hId="+hid,function(data){
	        				$("#hArea").val(data["harea"]);
		        			$("#hDevelop").val(data["hdevelop"]);
		        			$("#hPrice").val(data["hprice"]);
		        			$("#hSort").val(data["hsort"]);
		        			$("#hMore").val(data["hmore"]);
	                   	},"json");
						$("#openWin").click();
			        }
				}
			}
			$(function(){
				var str = "<?php echo ($result); ?>";
				if(str != -1)alert(str);
			});
		</script>
	</head>
	<body>
		<div style="margin:1%;">
			<button style="display:none;" "button" data-toggle="modal" data-target="#myModal" id="openWin">
			<button type="button" onclick="addOrUpdateHouse(0)" class="btn btn-sm-info"><span class="glyphicon glyphicon-plus"></span>新增</button>
			<button type="button" onclick="addOrUpdateHouse(1)" class="btn btn-sm-info"><span class="glyphicon glyphicon-pencil"></span>修改</button>
			<button type="button" class="btn btn-sm-info"><span class="glyphicon glyphicon-trash"></span>删除</button>
		</div>
		<table id="mytable" class ="table table-hover table-bordered table-striped" style = "width: 98%;margin:1%">
			<tr>
				<th><input type="checkbox"/>多选框</th>
				<th>编号</th>
				<th>地区</th>
				<th>开发商</th>
				<th>价格</th>
				<th>户型</th>
				<th>更多详情</th>
			</tr>
			<?php if(is_array($page["rows"])): $i = 0; $__LIST__ = $page["rows"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m1): $mod = ($i % 2 );++$i;?><tr>
					<td><input type="checkbox" value="<?php echo ($m1["hid"]); ?>"/></td>
					<td><?php echo ($m1["hid"]); ?></td>
					<td><?php echo ($m1["harea"]); ?></td>
					<td><?php echo ($m1["hdevelop"]); ?></td>
					<td><?php echo ($m1["hprice"]); ?></td>
					<td><?php echo ($m1["hsort"]); ?></td>
					<td><?php echo ($m1["hmore"]); ?></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
		<div style="margin-top:20px;" class="text-center">
			<ul class="pagination" style="margin-top: -10px;">
				<li><a href="javascript:void(0);">当前第<span style="color:red;"><?php echo ($page["pageNo"]); ?></span>页</a></li>
                <li><a href="javascript:turnPage(<?php echo ($page["pageNo"]); ?>,2,1);" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                <li><a href="javascript:turnPage(1,2);">1</a></li>
                <li><a href="javascript:turnPage(2,2);">2</a></li>
                <li><a href="javascript:turnPage(3,2);">3</a></li>
                <li><a href="javascript:turnPage(4,2);">4</a></li>
                <li><a href="javascript:turnPage(5,2);">5</a></li>
                <li><a href="javascript:turnPage(<?php echo ($page["pageNo"]); ?>,2,2);" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                <li><a href="javascript:void(0);">共<span style="color:red;"><?php echo ($page["total"]); ?></span>条记录</a></li>
           	</ul>
		</div>
		<div class="modal fade" tabindex="-1" id="myModal" role="dialog">
			<div class="modal-dialog" role="document">
			    <div class="modal-content">
			    	<div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title">增加/修改楼盘信息</h4>
			    	</div>
			        <form id="myform" method="post" action="<?php echo URLROOT?>/index.php/Home/User/User/addOrUpdateHouse">
				    	<div class="modal-body">
				    		<input type="hidden" id = "hId" name = "hId" value=""/>
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
				    	</div>
				    	<div class="modal-footer">
					        <button type="submit" class="btn btn-primary">确认</button>
					        <button type="reset" id="cancel" class="btn btn-default" data-dismiss="modal">取消</button>
				    	</div>
				    </form>
			    </div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</body>
</html>