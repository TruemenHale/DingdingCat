/**
 * Created by truemenhale on 15/9/13.
 */
var billInfoUrl = "./api/index.php?s=/Home/Account/billInfo";

$(function () {
	$('.getAddress').on('tap',function(){
		var _data = {};
		_data.phone = ""; //TODO
		_data.money = $('.ApplyNum').val();
		_data.addr = $('.getAddress').val();
		$.post('./api/index.php?s=/Home/Account/applyBill',_data,function(data){
			if(data>=0){
				alert('发票申请成功请等候收件！');
			}
			else{
				alert('发票申请失败，请正确填写申请金额！');
			}
		});
	})
});