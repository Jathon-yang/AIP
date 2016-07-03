#AIP
*基金定投辅助工具(automatic investment plan )*

## 介绍
这个工具主要是个人用来定投ETF基金时做为参考的工具，目前定投的策略方法相对简陋(金融知识有限)。
*程序在手，解放双手。*
最后还是要提醒一句：**股市有风险，投资需谨慎！**
[在线DEMO](http://104.236.189.161:88/)

## 策略算法
1. 市盈率小于15的时候开始定投
2. 市盈率大于15的时候停止定投
3. 市盈率大于20的时候全部卖出
4. 市盈率小于10的时候双倍定投（有余力者可考虑）

## php版本要求
php5.3+

## 安装
1. 将代码上传到服务器
2. 将 *AIP/public* 做为主机域名的根目录
3. 根据个人情况配置 *AIP/config/config.php* 文件
4. 如果开启了 邮件提醒功能 则需要在服务器上增加一个定时任务
5. 至此程序就可以跑起来了

PS: storage 目录需要可读写权限,不然可能会出错。

## 邮件提醒功能

这个功能需要服务器开启一个增加一个定时任务，所以如果你购买的是虚拟空间，将无法使用此功能。

下面以 CentOS7 下 crontab 为例：

编辑项目根目录下的 sendEmail.sh，依次填写相关的路径

`# vi sendEmail.sh`

`php安装路径/bin/php 项目路径/Cli.php`

PS:PHP安装路径可以通过命令 whereis php 进行查询

给 sendEmail.php 赋予可执行权限
`# chmod 755 sendEmail.sh`

编辑 /etc/crontab 文件，在末尾追加如下语句

`30 11 * * * root /data/wwwroot/AIP/sendEmail.sh`

PS:上面的语句意思是 每天11点半执行邮件提醒功能。其中 root 为执行的用户名，/data/wwwroot/AIP 为项目路径

最后重启 crontab

`/bin/systemctl restart  crond.service`

## 许可协议
本项目源码受 [MIT](https://github.com/Jathon-yang/AIP/blob/master/LICENSE) 开源协议保护