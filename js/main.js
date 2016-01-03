
var phone = "";
var pAddress = "";
var sAddress = "";
var zhong = "1";
var From = "";
var getToken = false;
var endToken = false;
var sendToken = false;
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
				window.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa3363e46c74608f3&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2fregister.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
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
			var url  = Date.parse(new Date());
			if (status != 0) {
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
				document.getElementById('endTime').innerText = data.endTime;
				document.getElementById('status').innerText = data.status;
				document.getElementById('payType').innerText = data.payType;
				document.getElementById('payStatus').innerText = data.payStatus;
				if (data.isPay == 0) {
					document.getElementById('newPay').style.display= "";
					document.getElementById('newForm').action="./wxpay/example/jsapi.php#time="+url;
					$('#newMoney').val(data.money);
					$('#newOrder').val(data.orderNo);
				}
			}
		}
	});
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
	var getAdd = $('.getAddress');
	var endAdd = $('.endAddress');
	var sendAdd = $('.sendAddress');
	oTitle = $('.selectTitle');
	getAdd.on('tap',function(){
		From = '.'+$(this).attr('class');
		var oList = $('.addressList');
		var _data = {};
		_data.phone = phone;
		oTitle.html('取件区域');
		$.mobile.changePage('#AddressGet',{
			transition:'none'
		});
		setTimeout(function () {
			$.post('http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/historyAddr',_data,function(data){
				if(data.status == 0){
					if (!data.list) {
						return 0;
					}
					oList.html("");
					$('#history_list').tmpl(data.list).appendTo(".addressList");
					oList.find('li').on('click',function(){
						if(From == '.getAddress'){
							getToken = true;
							sendToken = false;
						}else if(From == '.endAddress'){
							endToken = true;
							sendToken = false;
						}else{
							sendToken = true;
						}
						var y = $(this).find('.add-name').html();
						$(From).val(y);
						oList.html("");
						if(endToken && getToken && !sendToken){
							money();
						}
						$.mobile.changePage('#daisong',{
							transition:'none'
						});
					});
					$('.addressList').listview('refresh');
				}else{
					alert(data.info);
				}
			});
		},500);

	});
	endAdd.on('tap',function(){
		From = '.'+$(this).attr('class');
		var oList = $('.addressList');
		var _data = {};
		_data.phone = phone;
		oTitle.html('收件区域');
		$.mobile.changePage('#AddressGet',{
			transition:'none'
		});
		setTimeout(function () {
			$.post('http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/historyAddr',_data,function(data){
				if(data.status == 0){
					if (!data.list) {
						return 0;
					}
					oList.html("");
					$('#history_list').tmpl(data.list).appendTo(".addressList");
					oList.find('li').on('click',function(){
						if(From == '.getAddress'){
							getToken = true;
							sendToken = false;
						}else if(From == '.endAddress'){
							endToken = true;
							sendToken = false;
						}else{
							sendToken = true;
						}
						var y = $(this).find('.add-name').html();
						$(From).val(y);
						oList.html("");
						if(endToken && getToken && !sendToken){
							money();
						}
						$.mobile.changePage('#daisong',{
							transition:'none'
						});
					});
					$('.addressList').listview('refresh');
				}else{
					alert(data.info);
				}
			});
		},500);

	});
	sendAdd.on('tap',function(){
		From = '.'+$(this).attr('class');
		var oList = $('.addressList');
		var _data = {};
		_data.phone = phone;
		oTitle.html('送达区域');
		$.mobile.changePage('#AddressGet',{
			transition:'none'
		});
		setTimeout(function () {
			$.post('http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/historyAddr',_data,function(data){
				if(data.status == 0){
					if (!data.list) {
						return 0;
					}
					oList.html("");
					$('#history_list').tmpl(data.list).appendTo(".addressList");
					oList.find('li').on('click',function(){
						if(From == '.getAddress'){
							getToken = true;
							sendToken = false;
						}else if(From == '.endAddress'){
							endToken = true;
							sendToken = false;
						}else{
							sendToken = true;
						}
						var y = $(this).find('.add-name').html();
						$(From).val(y);
						oList.html("");
						if(endToken && getToken && !sendToken){
							money();
						}
						$.mobile.changePage('#daigou',{
							transition:'none'
						});
					});
					$('.addressList').listview('refresh');
				}else{
					alert(data.info);
				}
			});
		},500);

	});
	$('.cancel').on('tap',function(){
		if(From == '.getAddress' || From == '.endAddress'){
			$.mobile.changePage('#daisong',{
				transition:'none'
			});
		}else{
			console.log(1);
			$.mobile.changePage('#daigou',{
				transition:'none'
			});
		}
	});
	$('.minus').on('tap',function(){
		var a = parseFloat(KgNum.val())-1;
		if(a<0){
			return false;
		}
		else{
			KgNum.val(a);
			money();
		}
	});
	$('.plus').on('tap',function(){
		var a = parseFloat(KgNum.val())+1;
		KgNum.val(a);
		money();
	});
	$('.clearAdd').on('tap',function(){
		getAdd.val("");
		sendAdd.val("");
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
							if(From == '.getAddress'){
								getToken = true;
								sendToken = false;
							}else if(From == '.endAddress'){
								endToken = true;
								sendToken = false;
							}else{
								sendToken = true;
							}
							var y = $(this).find('.add-area').html() + $(this).find('.add-name').html();
								$(From).val(y);
								oList.html("");
								oInput.val("");
								if(endToken && getToken && !sendToken){
									money();
								}
								if(From == '.getAddress' || From == '.endAddress'){
									$.mobile.changePage('#daisong',{
										transition:'none'
									});
								}else{
									console.log(1);
									$.mobile.changePage('#daigou',{
										transition:'none'
									});
								}
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
		var _data = {};
		_data.sendAddr = $('.sendAddress').val();
		_data.sendDet = $('sendDet').val();
		_data.goodsDesc = $('.dgDescribe').val();
		_data.priceLimit = $('.dgCost').val();
		_data.runnerFee = $('.dgPay').val();
		_data.phone = phone;
		$.post('http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/buyAccept',_data,function(data){
			if (data) {
				var status = data.status;
				var orderNo = data.orderNo;
				var money = data.money;
				var url = Date.parse(new Date());
				if (status != 0) {
					alert('下单失败，可能是服务器出故障了');
				} else {
					alert('下单成功，请确认支付支付');
					document.getElementById('daigouPay').style.display= "";
					$('#buyMoney').val(money);
					$('#buyOrder').val(orderNo);
					$('.dgApply').button('option','disabled',true);
				}
			} else {
				alert("下单失败!");
			}
			$.mobile.loading('hide');
		});
	});
	$('#apply').on('tap',function(){
		_data = null;
		var url = Date.parse(new Date());
		var _data = {};
		_data.pickupAddr = $(".getAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.GdetAddr = $(".GetdetAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.sendAddr = $(".endAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.EdetAddr = $(".EnddetAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.pickupTime = "0";
		_data.weight = parseFloat(KgNum.val());
		_data.recipientName = $('.geterName').val();
		_data.recipientTel = $('.geterPhone').val();
		_data.goodsDesc = $('.goodsNote').val();
		_data.transportType = $(".transport option:selected").val();
		_data.payType = "0";
		_data.phone = phone;
		JSON.stringify(_data);
		$.post('http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/shipAccept',_data,function(data){
			if(data.status == 0){
				var orderNo = data.orderNo;
				var money = data.money;
				alert('下单成功，请确认支付支付');
				document.getElementById('daisongPay').style.display= "";
				$('#sendMoney').val(money);
				$('#sendOrder').val(orderNo);
				$('#apply').button('option','disabled',true);
			} else{
				if (data.status == -100) {
					alert(data.info);
				}
			}
		});
	});
});

function money () {
	_data = null;
	var KgNum = $('.KgNum');
	var _data = {};
	_data.pickupAddr = $(".getAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
	_data.sendAddr = $(".endAddress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
	_data.weight = parseFloat(KgNum.val());
	if (_data.pickupAddr != "" && _data.sendAddr != "") {

		if (pAddress != _data.pickupAddr || sAddress != _data.sendAddr || zhong != _data.weight) {
			$.post('http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/Order/getMoney',_data,function(data){
				if(data.status == 0){
					var money = data.money;
					var distance = data.distance;
					$('.money').html(money);
					$('.distance').html(distance);
					$('.money_num').css('visibility','visible');
					pAddress = _data.pickupAddr;
					sAddress = _data.sendAddr;
					zhong    = _data.weight;
				} else{
					alert(data.info);
				}
			});
		}
		return 0;
	} else {
		return 0;
	}
}

