
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr = document.cookie.match(reg))
        return unescape(arr[2]);
    else
        return null;
}
$(function () {
    if(getCookie("flag") == 500){
        time = parseInt(getCookie('time'));
        _this = $('.ApplyBtn');
        _this.html("等待"+time+"秒后重新发送");
        _this.css({"color":"#ccc","text-shadow":"none"});
        var timer = setInterval(function(){
            time--;
            document.cookie = "time = "+time;
            if(time <= 0){
                _this.html("获取验证码");
                _this.css({"color":"#fff","text-shadow":"inset"});
                document.cookie = "flag = true";
                clearInterval(timer);
                return;
            }
            _this.html("等待"+time+"秒后重新发送");
        },1000);

    }
    $('.ApplyBtn').on('tap',function(){
        var _this = $(this);
        if(getCookie("flag") == "true"||!getCookie("flag")){
            document.cookie = "flag = true";
            document.cookie = "time = "+120;
            if($('#phoneNum').val().length != 11){
                alert('请输入正确的手机号！');
                return;
            }
            $.post('./api/index.php?s=/Home/Account/codeSend','phone='+$('.phoneNum').val(),function(data){

            });
            time = parseInt(getCookie('time'));
            document.cookie = "flag = false";
        }
        if(getCookie("flag") == "false"){
            _this.html("等待"+time+"秒后重新发送");
            _this.css({"color":"#ccc","text-shadow":"none"});
            var timer = setInterval(function(){
                time--;
                document.cookie = "time = "+time;
                if(time == 0){
                    _this.html("获取验证码");
                    _this.css({"color":"#fff","text-shadow":"inset"});
                    document.cookie = "flag = true";
                    clearInterval(timer);
                    return;
                }
                _this.html("等待"+time+"秒后重新发送");
            },1000);
            document.cookie = "flag = "+500;
        }
    });
    $('.submit-form').on('tap',function () {
        $.mobile.loading('show', {
            text: '正在上传。。。', //加载器中显示的文字
            textVisible: true, //是否显示文字
            theme: 'a',        //加载器主题样式a-e
            textonly: false,   //是否只显示文字
            html: ""           //要显示的html内容，如图片等
        });
        $('.form').submit();
    });
    $('.refresh').on('tap',function () {
        window.location.href = "./zhaopian.php";
    })
});
