jQuery(function($){
	//返回上一级
	$("#js-pre-step").click(function(){
		window.location.href = "index.php?_m=frontpage&_a=check";
	});
	//用户名 密码 数据库验证
	$("#js-install-at-once").click( function () {
		function obj(){
			var _this = this;
			var step = 1;
			var step_a ="checkconnection";
			this.resend = function(){
				if(step==2){
					step_a ="create";
				}else if(step==3){
					step_a ="createadmin";
				}
				$.ajax({
					type: "POST",
					url: "index.php",
					data: "db_host="+$("#db-host").val()+"&db_port="+$("#db-port").val()+"&db_user="+$("#db-user").val()+"&db_pwd="+$("#db-pwd").val()+"&db_name="+$("#db-name").val()+"&db_prefix="+$("#db-prefix").val()+"&admin_name="+$("#js-admin-name").val()+"&admin_pwd="+$("#js-admin-password").val()+"&_a="+step_a+"&step="+step+"&demo=1",
					success: function(msg){
						if(msg=="1001"){
							$("#install_result").html("连接数据库失败");
							return;
						}else if(msg=="1002"){
							$("#install_result").html("该数据库不存在");
							return;
						}else if(msg=="1006"){
							$("#install_result").html("数据写入失败！");
							return;
						}else{
							$("#install_result").html("正在创建数据表...");
							if(msg=="1005"){
								$("#install_result").html("创建表失败！");
								return;
							}
							if(msg=="1003"){
								$("#install_result").html("正在创建管理账户...");
							}
							if(msg=="1004"){
								$("#install_result").html("管理员用户创建成功！...");
								window.location.href = "index.php?_m=frontpage&_a=result";

							}
						}
						if(step < 4){
							step++;
							_this.resend();
						}
					}
				}); 
			}
		}
		var obj = new obj();
		obj.resend();
});

});
