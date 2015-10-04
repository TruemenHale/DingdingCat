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
	});
	$.ajax({
		type : 'POST',
		url  : './api/index.php?s=/Home/Order/orderInfo',
		data : 'phone='+phone,
		dataType : 'json',
		error: function (request) {
			alert('获取失败')
		} ,
		success : function (response) {
			var status = response.status;
			var data = response.data;
			if (status != 0) {
				console.log("订单不存在");
			} else {
				document.getElementById('type').innerText = data.type;
				document.getElementById('orderNo').innerText = data.orderNo;
				document.getElementById('orderTime').innerText = data.orderTime;
				document.getElementById('name').innerText = data.name;
				document.getElementById('tel').innerText = data.tel;
				document.getElementById('pickAddr').innerText = data.pickAddr;
				document.getElementById('sendAddr').innerText = data.sendAddr;
				document.getElementById('distance').innerText = data.distance;
				document.getElementById('runner').innerText = data.runner;
				document.getElementById('getTime').innerText = data.getTime;
				document.getElementById('pickTime').innerText = data.pickTime;
				document.getElementById('planTime').innerText = data.planTime;
				document.getElementById('endTime').innerText = data.endTime;
				document.getElementById('status').innerText = data.status;
				document.getElementById('pay').innerText = data.pay;

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
	//获取最新订单是否被付款.
	$.post('url',"pay",function(data){
		//content是我编的，反正你看给我一个什么我判断他是不是支付了就行.
		if(data.content == 1){
			//如果支付了就让支付按钮消失.
			$('#pay').css('display','none');
		}else{
			$('#pay').on('tap',function(){
				//填跳支付的接口.

			})
		}
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
	$('.dgApply').on('tap',function(){
		$.mobile.loading('show');
		$(this).button('option','disabled',true);
		var _data = {};
		_data.dgAddress = $('.dgAddress').val();
		_data.dgDescribe = $('.dgDescribe').val();
		_data.dgCost = $('.dgCost').val();
		_data.dgPay = $('.dgPay').val();
		$.post('url',_data,function(data){
			$(this).button('option','disabled',false);
			$.mobile.loading('hide');
			alert(data.info);
		});
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
		_data.trandsportType = $(".transport option:selected").val();
		_data.payType = $(".payWays option:selected").val();
		_data.phone = phone;
		JSON.stringify(_data);
		console.log(_data);
		$.post('./api/index.php?s=/Home/Order/shipAccept',_data,function(data){
			if(data){
				var status = data.status;
				var orderNo = data.orderNo;
				var payType = data.payType;
				var money = data.money;
				if (status != 0) {
					alert('下单失败，可能是服务器出故障了');
				} else {
					alert('下单成功，请确认支付支付');
					document.getElementById('wxpay').style.display= "";
					document.getElementById('wxpayMoney').setAttribute('value',"0.02");
					document.getElementById('wxpayOrder').setAttribute('value',orderNo);
				}
			}
			else{
				alert("下单失败！");
			}
			$.mobile.loading('hide');
			$(this).button('option','disabled',false);
		});
	})
});

function money () {
	_data = null;
	var KgNum = $('.KgNum');
	var _data = {};
	_data.pickupAddr = $(".getAdress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
	_data.sendAddr = $(".endAdress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
	_data.weight = parseFloat(KgNum.val());
	$.post('./api/index.php?s=/Home/Order/getMoney',_data,function(data){
		if(data){
			var status = data.status;
			var money = data.money;
			if (status != 0) {

			} else {
				document.getElementById("moneyDisplay").setAttribute("value",money+"元");
			}
		}
	});
}