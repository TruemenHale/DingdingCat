<?php
return array(
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'kdj.tyll.net.cn', // 服务器地址
    'DB_NAME'   => 'dingdingmao', // 数据库名
    'DB_USER'   => 'ddm', // 用户名
    'DB_PWD'    => 'ddm', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_FIELDS_CACHE' => false,
    'DB_DEBUG'  =>  FALSE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'DB_PARAMS' =>  array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),

    'URL_CASE_INSENSITIVE' => true,
    'URL_MODEL' => 3,
    'LOG_RECORD' => true, // 开启日志记录
    'SHOW_PAGE_TRACE' =>false,
);