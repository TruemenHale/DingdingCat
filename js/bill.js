/**
 * Created by truemenhale on 15/9/13.
 */
$(function () {
	$('.getAddress').on('tap',function(){
		var _data = {};
		_data.num = $('.ApplyNum').val();
		_data.address = $('.getAddress').val();
		$.post('url',_data,function(data){
			if(data>=0){
				alert('发票申请成功请等候收件！');
			}
			else{
				alert('发票申请失败，请正确填写申请金额！');
			}
		});
	})
});