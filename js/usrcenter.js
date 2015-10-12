var phone = "";
var _url = self.location.href;
if(_url.indexOf("?")>0){
    var openid = _url.substr(_url.indexOf("?")+1,_url.length-_url.indexOf("?"));
}

$(function () {
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
                alert('账户不存在！！！');
            } else {
                phone = data;
                document.getElementById('name').innerText = response.name;
                document.getElementById('nickname').innerText = response.nickname;
                document.getElementById('score').innerText = response.score;
                document.getElementById('phone').innerText = response.phone;
                orderList(data);
                commonAddr(data)
            }
            $.mobile.loading('hide');
        }
    });
});

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
            if (status != 0) {
                alert("订单常用地址失败");
            } else {
                $("#addr_list").tmpl(list).appendTo('#addrList');
            }
        }
    });
}
