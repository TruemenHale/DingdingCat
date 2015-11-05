/**
 * Created by truemenhale on 15/8/23.
 */
function getCookie(name)
{
	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr = document.cookie.match(reg))
		return unescape(arr[2]);
	else
		return null;
}
$(function(){
	if(getCookie("flag") == 500){
		time = parseInt(getCookie('time'));
		_this = $('.ApplyBtn');
		_this.html("等待"+time+"秒后重新发送");
		_this.css({"color":"#ccc","text-shadow":"none"});
		var timer = setInterval(function(){
			time--;
			document.cookie = "time = "+time;
			if(time <= 0){
				_this.html("获取验证码");
				_this.css({"color":"#fff","text-shadow":"inset"});
				document.cookie = "flag = true";
				clearInterval(timer);
				return;
			}
			_this.html("等待"+time+"秒后重新发送");
		},1000);

	}
	$('.ApplyBtn').on('tap',function(){
		var _this = $(this);
		if(getCookie("flag") == "true"||!getCookie("flag")){
			document.cookie = "flag = true";
			document.cookie = "time = "+120;
			if($('.phoneNum').val().length != 11){
				alert('请输入正确的手机号！');
				return;
			}
			$.post('./api/index.php?s=/Home/Account/codeSend','phone='+$('.phoneNum').val(),function(data){

			});
			time = parseInt(getCookie('time'));
			document.cookie = "flag = false";
		}
		if(getCookie("flag") == "false"){
			_this.html("等待"+time+"秒后重新发送");
			_this.css({"color":"#ccc","text-shadow":"none"});
			var timer = setInterval(function(){
							time--;
				document.cookie = "time = "+time;
							if(time == 0){
								_this.html("获取验证码");
								_this.css({"color":"#fff","text-shadow":"inset"});
								document.cookie = "flag = true";
								clearInterval(timer);
								return;
							}
							_this.html("等待"+time+"秒后重新发送");
						},1000);
			document.cookie = "flag = "+500;
		}
	});
	$('.registerBtn').on('tap',function(){
		var _data = {};
		_data.name = $('.usrName').val();
		_data.phone = parseInt($('.phoneNum').val());
		_data.code = $('.ConfirmCode').val();
		_data.invite = $('.invite').val();
		_data.openid = openid;
		_data.nickname = nickname;
		$.post('./api/index.php?s=/Home/Account/register',_data,function(data){
			if(data.status == 0){
				alert('注册成功！将跳转至下单页面！');
				window.location.href ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcb5b14c964fadb27&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2findex.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect"
;			}
			else{
				if (data.status == -3) {
					alert(data.info);
					window.location.href ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcb5b14c964fadb27&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2findex.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect"
				} else {
					alert(data.info);
				}
			}
		});
	});
});