#叮叮猫项目Api
### 前置说明

* ### 请求Url：http://wx.tyll.net.cn/DingdingCat/api/index.php?s=/Home/方法名/函数名
* ### 请求格式 x-www-form-urlencoded
* ### 返回格式 json
---

+ ### 验证码发送
    - #### url: Account/codeSend
    - #### Request : 
        * ##### phone 手机号
    - #### Response:
        * ##### success：
        ```
            {
                status: 0            
            }
        ```
+ ### 注册
    - #### url: Account/register
    - #### Request : 
        * ##### phone 手机号
        * ##### name 姓名
        * ##### code 验证码
        * ##### nickname 昵称(OAuth2.0获取微信昵称)
        * ##### openid 微信openid(同上)
        * ##### headimg 微信头像(同上)
        * ##### invite 推荐人
    - #### Response:
        * ##### success：
        ```
            {
                status: 0,
                info : register success      
            }
+ ### 