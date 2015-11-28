var phone = "";



function orderList (phone,fn) {
    $.ajax({
        type: 'POST',
        url: './api/index.php?s=/Home/Order/orderList',
        data: 'phone=' + phone,
        dataType: 'json',
        error: function (request) {
            alert('获取失败')
        },
        success: function (response) {
            var status = response.status;
            var send   = jQuery.makeArray(response.send);
            var buy    = jQuery.makeArray(response.buy);
            var all    = jQuery.makeArray(response.all);
            var sendList = $("#sendList");
            var buyList  = $("#buyList");
            var allList  = $("#allList");
            if (status != 0) {
                alert("订单获取失败");
            } else {
                if (send) {
                    $("#send_list").tmpl(send).appendTo('#sendList');
                }
                if (buy) {
                    $("#buy_list").tmpl(buy).appendTo('#buyList');
                }
                if (all) {
                    $("#all_list").tmpl(all).appendTo('#allList');
                }
                fn();
            }
        }
    });
}

function commonAddr (phone) {
    $.ajax({
        type: 'POST',
        url: './api/index.php?s=/Home/Order/commonAddr',
        data: 'phone=' + phone,
        dataType: 'json',
        error: function (request) {
            alert('获取失败')
        },
        success: function (response) {
            var status = response.status;
            var list   = response.list;
            console.log(response);
            if (status != 0) {
                alert("订单常用地址失败");
            } else {
                $("#addr_list").tmpl(list).appendTo('#addrList');
            }
        }
    });
}

$(function () {
    function litap(){
        $(".orderClick").on('tap',function () {
            var orderNo = $(this).find('.orderNum').html();
            orderInfo(orderNo);
        });
    }
    $.mobile.loading('show');
    $.ajax({
        type: 'POST',
        url: './api/index.php?s=/Home/Account/openidToUser',
        data: 'openid=' + openid,
        dataType: 'json',
        error: function (request) {
            alert('获取失败')
        },
        success: function (response) {
            var status = response.status;
            var data = response.phone;
            if (status != 0) {
                alert("你还没有注册，将自动跳转到注册页面！");
                window.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa3363e46c74608f3&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2fregister.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            } else {
                phone = data;
                document.getElementById('name').innerText = response.name;
                document.getElementById('nickname').innerText = nickname;
                document.getElementById('score').innerText = response.score;
                document.getElementById('phone').innerText = response.phone;
                orderList(data,litap);
                commonAddr(data);
            }
            $.mobile.loading('hide');
        }
    });
});

function orderInfo (orderNo) {
    $.post('./api/index.php?s=/Home/Order/orderInfo','phone=' + phone + '&orderNo=' + orderNo,function(data){
        if(data.status == 0){
            $('#tHead').html("").append("<th>订单类型：</th>
                <th>订单号：</th>
            <th>下单时间：</th>
            <th>收件人姓名：</th>
            <th>收件人手机：</th>
            <th>取件地址：</th>
            <th>送达地址：</th>
            <th>全程距离：</th>
            <th>跑腿哥ID：</th>
            <th>抢单时间：</th>
            <th>取件时间：</th>
            <th>送达时间：</th>
            <th>支付方式：</th>
            <th>支付状态：</th>
            <th>订单状态：</th>");
            $('#type').html(data.data.type);
            $('#orderNo').html(data.data.orderNo);
            $('#orderTime').html(data.data.orderTime);
            $('#tel').html(data.data.tel);
            $('#orderInfo_name').html(data.data.name);
            $('#pickAddr').html(data.data.pickAddr);
            $('#sendAddr').html(data.data.sendAddr);
            $('#runner').html(data.data.runner);
            $('#getTime').html(data.data.getTime);
            $('#pickTime').html(data.data.pickTime);
            $('#planTime').html(data.data.planTime);
            $('#endTime').html(data.data.endTime);
            $('#status').html(data.data.status);
            $('#payType').html(data.data.payType);
            $('#payStatus').html(data.data.payStatus);
            if (data.data.isPay == 0) {
                document.getElementById('newPay').style.display= "";
                $('#newMoney').val(data.data.money);
                $('#newOrder').val(data.data.orderNo);
            } else {
                document.getElementById('newPay').style.display = "none";
            }
            $.mobile.changePage('#orderInfo',{
                transition:'none'
            });
        }else{
            alert(data.info);
        }
    });
}
