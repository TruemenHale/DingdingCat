/**
 * Created by truemenhale on 15/8/20.
 */
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
	$.get("./index.php","GPS",function(data){
		$(".getAdress").val(data);
		$.mobile.loading('hide');
	});
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
		_data.getAdress = $(".getAdress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.endAdress = $(".endAdress").val().replace(/[^\u4e00-\u9fa5]/gi,"");
		_data.getTime = $(".getTime option:selected").text();
		_data.kgNum = parseFloat(KgNum.val());
		_data.Name = $('.geterName').val();
		_data.Phone = $('.geterPhone').val();
		_data.goodNote = $('.goodNote').val();
		_data.Note = $('.note').val();
		_data.getTime = $(".transport option:selected").text();
		_data.payWay = $(".payWays option:selected").text();
		JSON.stringify(_data);
		console.log(_data);
			$.post('./index.php',_data,function(data){
			if(data){
				alert("下单成功！");
			}
			else{
				alert("下单失败！");
			}
				$.mobile.loading('hide');
				$(this).button('option','disabled',false);
		});
	})
});