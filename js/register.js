/**
 * Created by truemenhale on 15/8/23.
 */
var flag = true;
$(function(){
	$('.ApplyBtn').on('tap',function(){
		var _this = $(this);
		if(flag == true){
			if($('.phoneNum').val().length != 11){
				alert('请输入正确的手机号！');
				return;
			}
			$.post('./api/index.php?s=/Home/Account/codeSend','phone='+$('.phoneNum').val(),function(data){

			});
			time = 60;
			flag = false;
		}
		if(flag == false){
			_this.html("等待"+time+"秒后重新发送");
			_this.css({"color":"#ccc","text-shadow":"none"});
			var timer = setInterval(function(){
							time--;
							if(time == 0){
								_this.html("获取验证码");
								_this.css({"color":"#fff","text-shadow":"inset"});
								flag = true;
								clearInterval(timer);
								return;
							}
							_this.html("等待"+time+"秒后重新发送");
						},1000);
			flag = 500;
		}
	});
	$('.registerBtn').on('tap',function(){
		var _data = {};
		_data.name = $('.usrName').val();
		_data.phone = parseInt($('.phoneNum').val());
		_data.code = $('.ConfirmCode').val();
		_data.invite = $('.invite').val();
		$.post('./api/index.php?s=/Home/Account/register',_data,function(data){
			if(data.status == 0){
				alert('注册成功！');
			}
			else{
				alert('注册失败！');
			}
		});
	});
});