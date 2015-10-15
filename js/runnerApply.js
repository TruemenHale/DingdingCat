
var _url = self.location.href;
if(_url.indexOf("?")>0){
    var openid = _url.substr(_url.indexOf("?")+1,_url.length-_url.indexOf("?"));
}
var pic1 = "";
var pic2 = "";

$(function () {
    $('#idCardPic1').change(function (){
        var f = $(this).val();
        var reader = new FileReader();
        reader.readAsDataURL(this.files[0]);
        reader.onload = function (e) {
            pic1 = e.target.result;
        }
    });
    $('#idCardPic2').change(function (){
        var f = $(this).val();
        var reader = new FileReader();
        reader.readAsDataURL(this.files[0]);
        reader.onload = function (e) {
            pic2 = e.target.result;
        }
    });

    $('.registerBtn').on('tap',function(){
        $.mobile.loading('show');
        $(this).button('option','disabled',true);
        var _data = {};
        _data.name = $('.usrName').val();
        _data.phone = $('.phoneNum').val();
        _data.idCardNo = $('.idCard').val();
        _data.code = $('.ConfirmCode').val();
        _data.trandsportType = $(".transport option:selected").val();
        _data.idCardPic1 = pic1;
        _data.idCardPic2 = pic2;
        $.post('./api/index.php?s=/Home/Order/buyAccept',_data,function(data){
            if (data) {
                var status = data.status;
                if (status != 0) {
                    alert('报名失败，可能是服务器出故障了');
                } else {
                    alert('报名成功，请等待审批');
                }
            } else {
                alert("报名失败!未连接到服务器！");
            }
            $.mobile.loading('hide');
        });
    });
});