<?php defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * 配置文件
 * @Auther Jathon
 * Date: 2016/7/2
 * Time: 8:58
 */

class Config{
    public static $etf_list = [510500,510300,510050];//需要监控的ETF列表 为空则全部监控

    public static $host_index = '';//首页地址

    //邮件相关配置
    public static $email_config = [
        'send_open' => false,//开启邮件提醒功能
        'host' => '',  // SMTP 服务器
        'port' => 25,  // SMTP服务器的端口号
        'username' => '', // SMTP服务器用户名
        'password' => '',  // SMTP服务器密码

        //需要发送的邮箱列表
        'send_list' => [],
    ];
}