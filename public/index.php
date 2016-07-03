<?php
/**
 * AIP主入口文件
 * @Auther Jathon
 * Date: 2016/7/2
 * Time: 8:43
 */
define('BASE_PATH',dirname(__FILE__).'/../');//程序根目录
define('APP_PATH',BASE_PATH.'app/');//程序核心类目录
define('CONFIG_PATH',BASE_PATH.'config/');//程序配置文件目录
define('PUBLIC_PATH',BASE_PATH.'public/');//程序公开目录
define('STORAGE_PATH',BASE_PATH.'storage/');//数据存储目录

require_once CONFIG_PATH.'bootstrap.php';//载入引导类

Aip::run();//运行程序