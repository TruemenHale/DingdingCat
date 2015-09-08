/**
 * Created by truemenhale on 15/8/23.
 */
$(function(){
	$('.ApplyBtn').on('tap',function(){
		$.post('./index.php',parseInt($('.phoneNum').val()),function(data){

		});
	});
	$('.registerBtn').on('tap',function(){
		var _data = {};
		_data.usrName = $('.usrName').val();
		_data.Phone = parseInt($('.phoneNum').val());
		_data.token = $('.ConfirmCode').val();
		$.post('./index.php',_data,function(data){
			if(data == true){
				alert('注册成功！');
			}
			else{
				alert('注册失败！');
			}
		});
	});
});