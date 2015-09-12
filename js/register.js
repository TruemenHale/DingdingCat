/**
 * Created by truemenhale on 15/8/23.
 */
function changeBtn(a){
	sume = 40;
	$('.mask').css('display','block');
	$('.ApplyBtn').css({'position':'absolute','z-index':-100});
	var timer = setInterval(function(){
		if(sume == 1){
			sume+=40;
			a.html(sume);
			$('.mask').css('display','none');
			$('.ApplyBtn').css({'position':'relative','z-index':1});
			clearInterval(timer);
		}
		sume--;
		a.html(sume);
	},1000)
}
$(function(){
	$('.ApplyBtn').on('tap',function(){
		var a = $('.timer');
		changeBtn(a);
		$.post('./api/index.php?s=/Home/Account/codeSend','phone='+$('.phoneNum').val(),function(data){

		});
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