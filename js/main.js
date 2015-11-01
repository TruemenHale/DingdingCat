
var phone = "";

var From = "";
setTimeout(function(){
	var tel = "";
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
				alert("你还没有注册，将自动跳转到注册页面！");
				window.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcb5b14c964fadb27&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2fregister.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
			} else {
				phone = data;
				tel = data;
			}
		}
	});
},100);

setTimeout(function () {
	$.ajax({
		type : 'POST',
		url  : './api/index.php?s=/Home/Order/orderInfo',
		data : 'phone=' + phone,
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
				console.log(data);
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
	});
	/*$.post('url',"pay",function(data){
	 //content是我编的，反正你看给我一个什么我判断他是不是支付了就行.
	 if(data.content == 1){
	 //如果支付了就让支付按钮消失.
	 $('#pay').css('display','none');
	 }else{
	 $('#pay').on('tap',function(){
	 //填跳支付的接口.

	 })
	 }
	 });*/
},1000);

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
	//获取最新订单是否被付款.
	var KgNum = $('.KgNum');
	$('.getAddress').on('tap',function(){
		From = '.'+$(this).attr('class');
		$.mobile.changePage('#AddressGet',{
			transition:'none'
		});
	});
	$('.endAddress').on('tap',function(){
		From = '.'+$(this).attr('class');
		$.mobile.changePage('#AddressGet',{
			transition:'none'
		});
	});
	$('.cancel').on('tap',function(){
		$.mobile.changePage('#daisong',{
			transition:'none'
		});
	});
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
	$('.AddressInput').on('tap',function(){
		var x = "";
		var oInput = $('.AddressInput');
		var oList = $('.addressList');
		var data = "";
		var timer = setInterval(function(){
			if(x == $('.AddressInput').val()){
				return false;
			}else{
				x = oInput.val();
				var keyword = {};
				keyword.keyword = x;
				$.post('http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/placeSuggestion',keyword,function(data){
					if(data.status == 0){
						oList.html("");
						$('#place_list').tmpl(data.list).appendTo(".addressList");
						oList.find('li').on('click',function(){
							var y = $(this).find('.add-name').html();
								$(From).val(y);
								oList.html("");
								oInput.val("");
								$.mobile.changePage('#daisong',{
									transition:'none'
								});
						});
						$('.addressList').listview('refresh');
					}else{
						alert(data.info);
					}
				});
			}
		},1000);
	});
	$('.dgApply').on('tap',function(){
		$.mobile.loading('show');
		$(this).button('option','disabled',true);
		var _data = {};
		_data.sendAddr = $('.dgAddress').val();
		_data.goodsDesc = $('.dgDescribe').val();
		_data.priceLimit = $('.dgCost').val();
		_data.runnerFee = $('.dgPay').val();
		_data.phone = phone;
		$.post('http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/buyAccept',_data,function(data){
			if (data) {
				var status = data.status;
				var orderNo = data.orderNo;
				var money = data.money;
				if (status != 0) {
					alert('下单失败，可能是服务器出故障了');
				} else {
					alert('下单成功，请确认支付支付');
					document.getElementById('daigouPay').style.display= "";
					document.getElementById('wxpayMoney').setAttribute('value',"0.02");
					document.getElementById('wxpayOrder').setAttribute('value',orderNo);
				}

			} else {
				alert("下单失败!");
			}
			$.mobile.loading('hide');
		});
	});
	$('#apply').on('tap',function(){
		$(this).button('option','disabled',true);
		$.mobile.loading('show');
		_data = null;
		var _data = {};
		_data.pickupAddr = $(".getAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.GdetAddr = $(".GetdetAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.sendAddr = $(".endAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.EdetAddr = $(".EnddetAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
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
		$.post('http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/shipAccept',_data,function(data){
			if(data.status!= 0){
				var orderNo = data.orderNo;
				var payType = data.payType;
				var money = data.money;
				alert('下单成功，请确认支付支付');
				document.getElementById('daisongPay').style.display= "";
				document.getElementById('wxpayMoney').setAttribute('value',"0.02");
				document.getElementById('wxpayOrder').setAttribute('value',orderNo);
			}
			else{
				alert(data.info);
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
	_data.pickupAddr = $(".getAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
	_data.sendAddr = $(".endAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
	_data.weight = parseFloat(KgNum.val());
	$.post('http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/getMoney',_data,function(data){
		if(data.status == 0){
			var money = data.money;
			document.getElementById("moneyDisplay").setAttribute("value",money+"元");
		} else{
			alert(data.info);
		}
	});
}
