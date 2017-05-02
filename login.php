<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>用户登录</title>  
		<meta charset="utf-8">
		<link type="text/css" rel="stylesheet" href="Application/Common/dist/css/bootstrap.min.css">
		<script type="text/javascript" src="Application/Common/jQuery/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="Application/Common/dist/js/bootstrap.min.js"></script>
		<style type="text/css">
            .panel{height:250px;box-shadow:15px 15px 15px gray;margin-top:120px;}
            .btn{width:100px;}
        </style>
        <script type="text/javascript">
            function isTrue(obj,regExp){
    			$(obj).parent().parent().removeClass("has-success has-error");
    			$(obj).parent().parent().find($(".control-label")).hide();
    			$(obj).parent().parent().find($(".glyphicon")).removeClass("glyphicon-ok glyphicon-remove");
    			if(regExp.test(obj.value)){
    				$(obj).parent().parent().addClass("has-success");
    				$(obj).parent().parent().find($(".glyphicon")).addClass("glyphicon-ok").css("color","darkgreen");
    				return true;
    			}else{
    				$(obj).parent().parent().addClass("has-error");
    				$(obj).parent().parent().find($(".glyphicon")).addClass("glyphicon-remove").css("color","#AC2925");
    				$(obj).parent().popover("show");
    				return false;
    			}
    		}

            function finalSubmit(){
				var pwd = $("#password")[0];
				var phone = $("#phone")[0];
				var RegExp2 = /^[a-zA-Z0-9_]{6,12}$/;
				var RegExp4 = /^1(3|4|5|7|8)[0-9]{9}$/;
				isTrue(password,RegExp2);
				isTrue(phone,RegExp4);
				if(isTrue(phone,RegExp4) && isTrue(password,RegExp2)){
					return true;
				}else{
					return false;
				}
			}
        </script>
	</head>
	<body>
		<div class="col-md-4 col-md-offset-4">
			<div class="panel panel-primary">
				<div class="panel-heading">用户登录</div>
				<div class="panel-body">
					<form action="index.php/Home/User/User/Login" method="post" onsubmit="return finalSubmit()">
						
						<div class = "form-group form-inline">
    						<span style="width:95%" class="input-group">
    							<span style="width:5%" class = "input-group-addon">
    								<span class = "glyphicon glyphicon-phone"></span>
    							</span>
    							<input class="form-control" type="text" onblur="isTrue(this,/^1(3|4|5|7|8)[0-9]{9}$/)" name="phone" id="phone" placeholder="请输入手机号">
    						</span>
    						<span class = "glyphicon"></span>
						</div>
						<div class = "form-group form-inline">
    						<span style="width:95%" class="input-group">
    							<span style="width:5%" class = "input-group-addon">
    								<span class = "glyphicon glyphicon-lock"></span>
    							</span>
    							<input class="form-control" type="password" onblur="isTrue(this,/[a-zA-Z0-9_]{4,12}/)" name="password" id="password" placeholder="请输入密码">
    						</span>
    						<span class = "glyphicon"></span>
						</div>
						<div class="form-group" style="text-align: center; margin-top:20px;">
							<input class="btn btn-primary" type="submit" value="登录">
							<input class="btn btn-default" type="reset" value="取消">
						</div>
						<div>
							<b style="color:red;">
            				<?php 
            				    if(isset($_SESSION["loginError"])){
            				        echo $_SESSION["loginError"];
            				        unset($_SESSION["loginError"]);
            				    }
            				?>
            				</b>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>