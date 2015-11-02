
var phone = "";


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
                alert('账户不存在！无法进行建议提交！');
            } else {
                phone = data;
            }
        }
    });
},100);

$(function(){
    $('.sApply').on('tap',function(){
        $.mobile.loading('show');
        $(this).button('option','disabled',true);
        var _data = {};
        _data.content = $('.content').val();
        _data.type = $(".type option:selected").val()
        _data.phone = phone;
        $.post('./api/index.php?s=/Home/Account/suggestionApply',_data,function(data){
            if (data) {
                var status = data.status;
                if (status != 0) {
                    alert('提交失败，可能是服务器出故障了');
                } else {
                    alert('提交成功！');
                }
            } else {
                alert("提交失败!");
            }
            $.mobile.loading('hide');
        });
    });
});