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
        ```
+ ### 发票信息
    - #### url: Account/billInfo
        - #### Request : 
            * ##### openid 微信openid(同上)
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    info : success,
                    data : {
                        total : 总额
                        used  : 已用
                        unuse : 未用
                        phone : xxxxxxx
                    }
                }
            ```
+ ### 申请发票
    - #### url: Account/applyBille
        - #### Request : 
            * ##### phone 手机号
            * ##### money 金额
            * ##### addr  邮寄地址
            * ##### head  发票抬头
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    info : success
                }
            ```
+ ### 通过openid获取用户信息
    - #### url: Account/openidToUser
        - #### Request : 
            * ##### openid 微信openid(同上)
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    phone: xxx,
                    name : xxx,
                    nickname : xxx,
                    score : 积分
                }
            ```

+ ### 意见建议
    - #### url: Account/suggestionApply
        - #### Request : 
            * ##### phone 手机号
            * ##### content 内容
            * ##### type 类型
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    info : success
                }
            ```
+ ### 接单短信发送
    - #### url: Api/accessMsgSend
        - #### Request : 
            * ##### openid 微信openid
            * ##### order 订单号
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    info : success
                }
            ```
+ ### 送达短信发送
    - #### url: Api/finishMsgSend
        - #### Request : 
            * ##### openid 微信openid
            * ##### order 订单号
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    info : success
                }
            ```
+ ### 代送
    - #### url: Order/shipAccept
        - #### Request : 
            * ##### phone 手机号
            * ##### pickupAddr 取件地址
            * ##### sendAddr 收件地址
            * ##### pickupTime 取件时间
            * ##### weight 重量
            * ##### recipientName 收件人姓名
            * ##### recipientTel 收件人电话
            * ##### goodsDesc 货物描述
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    info : success，
                    orderNO: xxx(订单号)，
                    money: 订单金额
                }
            ```
+ ### 代购
    - #### url: Order/buyAccept
        - #### Request : 
            * ##### phone 手机号
            * ##### sendAddr 收件地址
            * ##### priceLimit 价格上线
            * ##### runnerFee 跑腿哥小费
            * ##### goodsDesc 货物描述
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    info : success，
                    orderNO: xxx(订单号)，
                    money: 订单金额
                }
            ```
+ ### 经纬度转地址
    - #### url: Order/locationTrans
        - #### Request : 
            * ##### lng 经度
            * ##### lat 纬度
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    info : success，
                    location:地址
                }
            ```
+ ### 订单信息
    - #### url: Order/orderInfo
        - #### Request : 
            * ##### phone 
            * ##### orderNo 订单号(如无订单号，则自动查询最新订单)
        - #### Response:
            * ##### success：
            ```
                {
                     'status' => '0',
                     'data'  => [
                        'type'      => 类型
                        'orderNo'   => 订单号
                        'orderTime' => 订单时间
                        'name'      => 姓名
                        'tel'       => 电话
                        'pickAddr'  => 取件地址
                        'sendAddr'  => 送达地址
                        'distance'  => 距离
                        'runner'    => 跑腿哥Id
                        'getTime'   => 抢单时间
                        'pickTime'  => 取件时间
                        'endTime'   => 送达时间
                        'money'     => 价格
                        'status'    => 状态
                        'payStatus' => 支付状态
                     ]
                }
            ```
+ ### 计算代送价格
    - #### url: Order/getMoney
        - #### Request : 
            * ##### pickupAddr 起始地址
            * ##### sendAddr 送达地址
            * ##### weight 重量
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    info : success，
                    money : 价格
                    distance : 距离
                }
            ```
+ ### 订单列表
    - #### url: Order/orderList
        - #### Request : 
            * ##### phone 
        - #### Response:
            * ##### success：
            ```
                {
                    'status' => '0',
                    'all'  => [
                        'type'      => "送",
                        'orderNo'   => 订单号
                        'orderTime' => 订单时间,
                        'money'     => 价格,
                        'status'    => 状态,
                        'pickupAddr'=> 取件地址,
                        'sendAddr'  => 送达地址
                    ],
                    'send'  => [
                        'type'      => "送",
                        'orderNo'   => 订单号,
                        'orderTime' => 订单时间,
                        'money'     => 价格,
                        'status'    => 状态,
                        'pickupAddr'=> 取件地址,
                        'sendAddr'  => 送达地址
                    ],
                    'all'  => [
                        'type'      => "购",
                        'orderNo'   => 订单号,
                        'orderTime' => 订单时间,
                        'money'     => 价格,
                        'status'    => 状态,
                        'sendAddr'  => 送达地址
                    ]
                }
            ```
+ ### 历史地址
    - #### url: Order/getMoney
        - #### Request : 
            * ##### phone
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    list :[
                        0:[
                            'addr':地址
                        ]，
                        1:[
                            'addr':地址
                        ]
                    ]
                }
            ``` 
+ ### 支付返回确认
    - #### url: Order/payStatus
        - #### Request : 
            * ##### orderNo 订单号
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                }
            ```
+ ### 建议地址
    - #### url: Order/placeSuggestion
        - #### Request : 
            * ##### phone
        - #### Response:
            * ##### success：
            ```
                {
                    status: 0,
                    list :[
                        0:[
                            'name':地址,
                            'area':区域
                        ]，
                        1:[
                            'name':地址,
                            'area':区域
                        ]
                    ]
                }
            ```
