<?php
return array(

    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '122.114.13.141', // 服务器地址
    'DB_NAME'               =>  'zzszy',          // 数据库名
    'DB_USER'               =>  'zzszy',      // 用户名
    'DB_PWD'                =>  'zzszy888',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
//'配置项'=>'配置值'

    'MODULE_ALLOW_LIST' => array('Home', 'Shop','Shuadmin'), //允许访问的模块
    'URL_MODEL' => 2, //URL模式 REWRITE模式
    "appid"=>'2018070660557548',
    'gatewayUrl'=>'http://openapi.alipay.com/gateway.do',
    'PAGESIZE_LIST'=>array(10,30,50,100,'全部'),
    /*  'MODULE_ALLOW_LIST' =>array('Home'), //允许访问的模块*/
    'DEFAULT_MODULE' => 'Home', // 默认模块
    'DEFAULT_CONTROLLER' => 'Index', // 默认控制器名称(类)
    'DEFAULT_ACTION' => 'index', // 默认操作名称(方法名)
    /*'URL_CASE_INSENSITIVE' => true,*/
    //'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__VIEWINCLUD__' => __ROOT__ . '/Application/Home/View',
        'TMPL_CACHE_ON' => false,//禁止模板编译缓存
        'HTML_CACHE_ON' => false//禁止静态缓存
    ),
);