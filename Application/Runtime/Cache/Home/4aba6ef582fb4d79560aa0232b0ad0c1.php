<?php if (!defined('THINK_PATH')) exit(); define("URLROOT", "http://localhost:8080/tp"); session_start(); ?>
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
				$('#cc').calendar({
					current: new Date(),
		            formatter: function (date) {
		                return "<br/>" + date.getDate()+'<div style="margin-left:35px; width:80px;">点击查看日程<b style="color:red;" id="number"></b></div>';

		            },
					onSelect: function(date){
			            var month = date.getMonth()+1;
			            if(month < 10){
							month = "0"+month;
				        }
						var title = date.getFullYear()+"-"+month+"-"+date.getDate();
						addTabs(title,"scheduleList.php?date="+title);
					}
				});
			});
    		function addTabs(title,url){
    			var tab = $('#mytab').tabs('exists',title);
        		if(tab){
    				$("#mytab").tabs("select", title);
    				tab = $("#mytab").tabs("getTab", title),
    				$('#mytab').tabs('update', {
    					tab: tab,
    					options: {}
    				});
    			}else{
            		$('#mytab').tabs('add',{
            		    title:title,
            		    selected: true,  
            		    closable:true,
            		    content:'<iframe style = "border:0;" src = "'+url+'" width = "99%" height = "99%"></iframe>'
            		});	
        		}	
            }
		</script>
	</head>
	<body class="easyui-layout">   
        <div data-options="region:'north',split:true" style="height:50px;">
        	<div style="color:gold;width:420px;text-algin:center;margin-left:-210px;font-size: 20px;position:absolute;left:50%;">
        		<b>重庆市×××房地产销售公司客户关系管理系统</b>
        	</div>
			<?php  if(isset($_SESSION["loginUser"])){ echo "<span style ='font-size:15px;'>欢迎您：<b style = 'color:red'>".$_SESSION["loginUser"]."</b></span>"; echo "<a href = 'http://localhost:8080/tp/login.php'>退出</a>"; } ?>
        </div>
        <div data-options="region:'west',title:'菜单',split:true" style="width:200px;">
			<ul animate="true" class="easyui-tree">
				<?php if(is_array($_SESSION['menus'])): $i = 0; $__LIST__ = $_SESSION['menus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m1): $mod = ($i % 2 );++$i; if($m1["mlevel"] == 1): ?><li>
							<span><?php echo ($m1["mname"]); ?></span>
							<ul>
								<?php $mid = $m1["mid"]; ?>
								<?php if(is_array($_SESSION['menus'])): $i = 0; $__LIST__ = $_SESSION['menus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$m2): $mod = ($i % 2 );++$i; if($m2["mlevel"] == 2 and $m2["mparentid"] == $mid): ?><li><a href='javascript:addTabs("<?php echo ($m2["mname"]); ?>","<?php echo URLROOT?>/<?php echo ($m2["murl"]); ?>");'><?php echo ($m2["mname"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
							</ul>
						</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
			</ul>
        </div>   
        <div data-options="region:'center'" style="background:#eee;">
        	<div id="mytab" class="easyui-tabs" data-options = "fit:true">   
                <div title="欢迎您" data-options="closable:true"> 
                	<div id="cc" class="easyui-calendar" data-options = "fit:true"></div> 
                </div>
            </div>  
        </div>   
    </body> 
</html>