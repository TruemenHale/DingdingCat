var phone = "";

setTimeout(function () {
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
                window.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcb5b14c964fadb27&redirect_uri=http%3a%2f%2fwx.tyll.net.cn%2fDingdingCat%2fregister.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            } else {
                phone = data;
                document.getElementById('name').innerText = response.name;
                document.getElementById('nickname').innerText = nickname;
                document.getElementById('score').innerText = response.score;
                document.getElementById('phone').innerText = response.phone;
                orderList(data);
                commonAddr(data)
            }
            $.mobile.loading('hide');
        }
    });
},100);

function orderList (phone) {
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
            var send   = response.send;
            var buy    = response.buy;

            if (status != 0) {
                alert("订单获取失败");
            } else {
                $("#all_list").tmpl(response.all).appendTo('#allList');
                $("#send_list").tmpl(send).appendTo('#sendList');
                $("#buy_list").tmpl(buy).appendTo('#buyList');
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
    var order = $('.orderTap');
    var No    = "";
    order.find('li').on('click',function () {
        No = (this).find('.orderNum').html();

    });
});

function orderInfo (orderNo) {
    $.ajax({
        type : 'POST',
        url  : './api/index.php?s=/Home/Order/orderInfo',
        data : 'phone=' + phone + '&orderNo=' + orderNo,
        dataType : 'json',
        error: function (request) {
            alert('获取失败')
        } ,
        success : function (response) {
            var status = response.status;
            var data = response.data;
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
                document.getElementById('planTime').innerText = data.planTime;
                document.getElementById('endTime').innerText = data.endTime;
                document.getElementById('status').innerText = data.status;
                document.getElementById('pay').innerText = data.pay;

            }
        }
    });
}
