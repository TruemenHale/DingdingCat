/**
 * Created by truemenhale on 15/8/20.
 */

var phone = "";
var _url = self.location.href;
if(_url.indexOf("?")>0){
	var openid = _url.substr(_url.indexOf("?")+1,_url.length-_url.indexOf("?"));
}

setTimeout(function(){
	$.ajax({
		type : 'POST',
		url  : './api/index.php?s=/Home/Account/openidToUser',
		data : 'openid='+openid,
		dataType : 'json',
		error: function (request) {
			alert('获取失败')
		} ,
		success : function (response) {
			var status = response.status;
			var data = response.phone;
			if (status != 0) {
				alert('账户不存在！！！');
			} else {
				phone = data;
			}
		}
	})
},100);
$(document).on("pagebeforeshow","#daisong",function(){
	$('#daisong').find('.daisong').addClass('ui-link ui-btn ui-btn-active');
});
$(document).on("pagebeforeshow","#daigou",function(){
	$('#daigou').find('.daigou').addClass('ui-link ui-btn ui-btn-active');
});
$(document).on("pagebeforeshow","#xiangqing",function(){
	$('#xiangqing').find('.xiangqing').addClass('ui-link ui-btn ui-btn-active');
});
$(function(){
	$.mobile.loading('show');
	$('.clearAddress').on('tap',function(){
		$(".getAdress").val('');
	});
	var KgNum = $('.KgNum');
	$('.minus').on('tap',function(){
		var a = parseFloat(KgNum.val())-0.5;
		if(a<0){
			return false;
		}
		else{
			KgNum.val(a);
		}
	});
	$('.plus').on('tap',function(){
		var a = parseFloat(KgNum.val())+0.5;
		KgNum.val(a);
	});
	$('#apply').on('tap',function(){
		$(this).button('option','disabled',true);
		$.mobile.loading('show');
		_data = null;
		var _data = {};
		_data.pickupAddr = $(".getAdress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.sendAddr = $(".endAdress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.pickupTime = $(".getTime option:selected").text();
		_data.weight = parseFloat(KgNum.val());
		_data.recipientName = $('.geterName').val();
		_data.recipientTel = $('.geterPhone').val();
		_data.goodsDesc = $('.goodsNote').val();
		_data.remark = $('.note').val();
		_data.trandsportType = $(".transport option:selected").text();
		_data.payType = $(".payWays option:selected").text();
		_data.phone = phone;
		JSON.stringify(_data);
		console.log(_data);
		$.ajax({
			type : 'POST',
			url  : './api/index.php?s=/Home/Order/shipAccept',
			data : _data,
			dataType : 'json',
			error: function (request) {
				alert('获取失败')
			} ,
			success : function (response) {
				var status = response.status;
				var orderNo = response.orderNo;
				var payType = response.payType;
				if (status != 0) {
					alert('下单失败，可能是服务器出故障了');
				} else {
					$.mobile.loading('hide');
					$(this).button('option','disabled',false);
					if (_data.payType == 0) {
						alert('下单成功，确认后跳转支付');
						window.location.href='./wxpay/example/jsapi.php?orderNo='+orderNo;
					} else {
						alert('下单成功!');
					}

				}
			}
		})
	})
});