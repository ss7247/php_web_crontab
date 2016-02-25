# php_web_crontab

还需运行:
下列运行方式任选其中一个就行：

    1.运行方式一：将当前目录中`crontab.txt`文件 替换成 `/var/spool/cron/root`

    2.运行方式二：运行 `crontab -e` ，将文件里的内容复制进行

说明:
1.由于设置是通过WEB访问，PHP在linux的权限受限,所以还需要您进入 linux 里在执行 替换命令;

2.如果您的PHP有权限 ，并能看的懂代码，实际上只需要 转一行代码就够了;

3.Windows 系统通过WEB设置好后，要将当前目录中`crontab.txt`文件内容放到 `crontab -e` 里面去

4.可以通过 `crontab -l` 来查看是否添加成功

用法示例:
用法	介绍
30 21 * * * /usr/local/etc/rc.d/lighttpd restart	表示每晚的21:30重启apache。
45 4 1,10,22 * * /usr/local/etc/rc.d/lighttpd restart	表示每月1、10、22日的4 : 45重启apache。
10 1 * * 6,0 /usr/local/etc/rc.d/lighttpd restart	表示每周六、周日的1 : 10重启apache。
0,30 18-23 * * * /usr/local/etc/rc.d/lighttpd restart	表示在每天18 : 00至23 : 00之间每隔30分钟重启apache。
0 23 * * 6 /usr/local/etc/rc.d/lighttpd restart	表示每星期六的11 : 00 pm重启apache。
* */1 * * * /usr/local/etc/rc.d/lighttpd restart	每一小时重启apache
* 23-7/1 * * * /usr/local/etc/rc.d/lighttpd restart	晚上11点到早上7点之间，每隔一小时重启apache
0 11 4 * mon-wed /usr/local/etc/rc.d/lighttpd restart	每月的4号与每周一到周三的11点重启apache
0 4 1 jan * /usr/local/etc/rc.d/lighttpd restart	一月一号的4点重启apache
0 7 * * * /bin/ls	每天早上7点执行一次 /bin/ls
0 6-12/3 * 12 * /usr/bin/backup	在 12 月内, 每天的早上 6 点到 12 点中，每隔3个小时执行一次 /usr/bin/backup :
0 17 * * 1-5 mail -s "hi" alex@domain.name < /tmp/maildata	周一到周五每天下午 5:00 寄一封信给 alex@domain.name :
20 0-23/2 * * * echo "haha"	每月每天的午夜 0 点 20 分, 2 点 20 分, 4 点 20 分....执行 echo "haha"