/**
 * Created by truemenhale on 15/9/13.
 */
var billInfoUrl = "./api/index.php?s=/Home/Account/billInfo";
var phone = "";
var _url = self.location.href;
if(_url.indexOf("?")>0){
	var openid = _url.substr(_url.indexOf("?")+1,_url.length-_url.indexOf("?"));
}

setTimeout(function(){
	$.ajax({
		type : 'POST',
		url  : billInfoUrl,
		data : 'openid='+openid,
		dataType : 'json',
		error: function (request) {
			alert('获取失败')
		} ,
		success : function (response) {
			var status = response.status;
			var data = response.data;
			if (status != 0) {
				alert('账户不存在！！！');
			} else {
				document.getElementById('billTotal').innerText=data.total+'元';
				document.getElementById('billUsed').innerText=data.used+'元';
				document.getElementById('billUnUse').innerText=data.unuse+'元';
				phone = data.phone;
			}

		}
	})
},100);

$(function () {
	$('.getAddress').on('tap',function(){
		var _data = {};
		_data.phone = phone;
		_data.money = $('.ApplyNum').val();
		_data.addr  = $('.getAddress').val();
		$.ajax({
			type : 'POST',
			url  : './api/index.php?s=/Home/Account/applyBill',
			data : _data,
			dataType : 'json',
			error : function (request) {
				alert('服务器连接失败');
			},
			success : function (response) {
				var status = response.status;
				if (status >= 0) {
					alert('发票申请成功请等候收件！');
				} else {
					alert('发票申请失败，请正确填写申请金额！');
				}
			}
		});

	})
});