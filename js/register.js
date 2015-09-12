/**
 * Created by truemenhale on 15/8/23.
 */
$(function(){
	$('.ApplyBtn').on('tap',function(){
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
			if(data>=0){
				alert('注册成功！');
			}
			else{
				alert('注册失败！');
			}
		});
	});
});