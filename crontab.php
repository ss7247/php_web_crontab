<?php
/**
* 2016-02-25
* astar
* http://www.astarblog.cn
* http://www.github.com/ss7247
*/

# 设置 将crontab 缓存的文件名 
 $file_name = "crontab.txt";

# post 处理
if(isset($_POST['data']) && $_POST['data']){
	$save_data = '';										# 数据保存字段
	$ajax_arr = array_filter($_POST['data']); 				# 去除数组中的空值
	foreach($ajax_arr as $value){
		for($i=1;$i<=6;$i++)
			$save_data .= $value[$i].' ';					# 拼接内容
		
		$save_data .= "\r\n";								# 加换行符
	}
	$status = file_put_contents($file_name,html_entity_decode($save_data));
	echo $status ? 'succeed' : 'fail';					# 是否写入成功
	exit;
}
# 判断文件名是否存在 
function is_files($file_name){
	$file_data = '';
	 if(is_file($file_name)){
		if(PHP_OS=='Linux' || PHP_OS=='linux')
			exec("cat ".$file_name,$file_data);		# 获得指定文件的内容 并转成数组
		else
			exec("type ".$file_name,$file_data);		# 获得指定文件的内容 并转成数组
		$file_data = array_filter($file_data);	# 去掉数组中空的值
	 }else{
		 $file_data  = array( 1 => "0 2 2 * * echo '测试数据' ",);
	 }
	 return $file_data;
}
$data_arr = is_files($file_name);				# 获得文件内容的数组

$show_table = '';								# 显示 table 部分的数据

if(empty($data_arr)){
	$show_table = '不好意思没数据';
}else{
	$num = 1;
	foreach($data_arr as $k=>$v){
		
		$str_data = preg_replace("/\s(?=\s)/","\\1",$v);	# 去除字符串中多余的空格(相连的两个字符串始终只保存一个格空)
		$str_arr  = explode(" ",trim($str_data));			# 通过上面保存的空格 来将字符串分割成 数组
		
		$arr_num  = count($str_arr);						# 获取分割数组的总长度
		if($arr_num < 5)									# 根据 crontab 的规则 发现 分割的数据 最少也是 6 位
			continue;										# 不在区间表示 当前规则 格式可能不对   将舍期
			
		$over_top = '';
		for($i=5;$i<$arr_num;$i++)
			$over_top .= $str_arr[$i].' ';					# 后面执行的语句可能会 比6 大很多，所以合并显示
		
		$show_table .= "<tr>
						<td>{$num} </td>
						<td>{$str_arr[0]}</td>
						<td>{$str_arr[1]}</td>
						<td>{$str_arr[2]}</td>
						<td>{$str_arr[3]}</td>
						<td>{$str_arr[4]}</td>
						<td>{$over_top}</td>
						<td><span class='td_add'>添加</span><span class='td_edit'>编辑</span><span class='td_del'>删除</span></td>
					</tr>";
		$num ++;
	}
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>php 设置 linux 定时脚本 crontab</title>
		<style type="text/css">
			body,html{font:12px 微软雅黑,宋体,verdana,arial,helvetica;padding:0 0;margin:0 auto;color:#000;background-color:#333;}
			input{width:90px;}
			
			.delimits{max-width:960px;margin:0 auto;background-color:white;}
			.delimits .titles{line-height:100px;background-color:#8194AA;}
			.delimits .titles .as_text{line-height:100px;text-align:center;font-size:22px;}
			
			.delimits .td_sys{color:red;}
			
			.delimits .operations{width:100%;line-height:40px;text-align:right;}
			.delimits .operations .td_save{color:green;margin:0px 10px;font-size:28px;}
			
			.delimits table{margin-top: 10px;border-collapse: collapse; border: 1px solid #aaa;width: 100%;}
			.delimits table tr{display: table-row;vertical-align: inherit;}
			.delimits table tr th{vertical-align: baseline;padding: 5px 15px 5px 6px;background-color: #d5d5d5;border: 1px solid #aaa;text-align: left;}
			.delimits table tr td{vertical-align: text-top;padding: 6px 15px 6px 6px; background-color: #efefef;border: 1px solid #aaa;}
			.delimits .tables table tr td:last-child{width:150px;}
			.delimits table tr td .td_add,.delimits table tr td .td_sub{width:20px;color:blue;}
			.delimits table tr td .td_edit{width:20px;color:green;margin-left:10px;}
			.delimits table tr td .td_del{width:20px;color:red;margin-left:10px;}
			.delimits table tr td .td_undo{width:20px;color:red;margin-left:10px;}
			.delimits table tr td span:hover{cursor:pointer;color:yellow; }
			
			.delimits .operations .td_save:hover{cursor:pointer;color:yellow; }
			
			.delimits .runs,.delimits .explain,.delimits .gives{margin-top:30px;margin-left:10px;}
			.delimits .user_give{font-size:30px;color:green;text-align:left;margin-bottom:20px;}
			.delimits .explain_p{margin-left:20px;mangin-top:10px;font-size:16px;}
			
			.delimits .footers{ margin: 0;padding: 0;width:100%;height: 46px;background: #b6b6b6;border-top: 10px solid #fff;border-bottom: 10px solid #fff;text-align:center;}

		</style>
	</head>
	<body>
		<!--定界-->
		<div class='delimits'>
			<!--title-->
			<div class='titles'>
				<div class='as_text'>php 设置 linux 定时脚本 crontab</div>
			</div>
			您当前的系统是:<span class='td_sys'><?php echo PHP_OS;?></span>
			<!--操作-->
			<div class='operations'>				
				<span class='td_save'>点击保存</span>
			</div>
			<!--table-->
			<div class='tables'>
				<table>
					<tr>
						<th>ID</th>
						<th>分(0~59)</th>
						<th>时(0~23)</th>
						<th>天(1~31)</th>
						<th>月(1~12)</th>
						<th>周(0~6)</th>
						<th>执行内容</th>
						<th>操作</th>
					</tr>
					<?php echo $show_table;?>
				</table>
			<div>
			
			<!--运行-->
			<div class='runs'>
				<div class='user_give'>还需运行:</div>
					<div class='explain_p'>
						<p>下列运行方式任选其中一个就行：</p>
						<p> &nbsp;&nbsp;&nbsp;&nbsp;1.运行方式一：将当前目录中`<?php echo $file_name;?>`文件 替换成 `/var/spool/cron/root`</p>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;2.运行方式二：运行 `crontab -e` ，将文件里的内容复制进行</p>
					</div>
			</div>
			<!--说明-->
			<div class='explain'>
				<div class='user_give'>说明:</div>
				<div class='explain_p'>
					<p>1.由于设置是通过WEB访问，PHP在linux的权限受限,所以还需要您进入 linux 里在执行 替换命令;</p>
					<p>2.如果您的PHP有权限 ，并能看的懂代码，实际上只需要 转一行代码就够了;</p>
					<p>3.Windows 系统通过WEB设置好后，要将当前目录中`<?php echo $file_name;?>`文件内容放到 `crontab -e` 里面去</p>
					<p>4.可以通过 `crontab -l` 来查看是否添加成功 </p>
				</div>
			</div>
			<!--用法示例-->
			<div class='gives'>
				<div class='user_give'>用法示例:</div>
				<table>
					<tr>
						<th>用法</th>
						<th>介绍</th>
					</tr>
					<tr>
						<td>30 21 * * * /usr/local/etc/rc.d/lighttpd restart</td>
						<td>表示每晚的21:30重启apache。 </td>
					</tr>
					<tr>
						<td>45 4 1,10,22 * * /usr/local/etc/rc.d/lighttpd restart </td>
						<td>表示每月1、10、22日的4 : 45重启apache。</td>
					</tr>
					<tr>
						<td>10 1 * * 6,0 /usr/local/etc/rc.d/lighttpd restart </td>
						<td>表示每周六、周日的1 : 10重启apache。</td>
					</tr>
					<tr>
						<td>0,30 18-23 * * * /usr/local/etc/rc.d/lighttpd restart  </td>
						<td>表示在每天18 : 00至23 : 00之间每隔30分钟重启apache。</td>
					</tr>
					<tr>
						<td>0 23 * * 6 /usr/local/etc/rc.d/lighttpd restart </td>
						<td>表示每星期六的11 : 00 pm重启apache。</td>
					</tr>
					<tr>
						<td>* */1 * * * /usr/local/etc/rc.d/lighttpd restart </td>
						<td>每一小时重启apache  </td>
					</tr>
					<tr>
						<td>* 23-7/1 * * * /usr/local/etc/rc.d/lighttpd restart </td>
						<td>晚上11点到早上7点之间，每隔一小时重启apache  </td>
					</tr>
					<tr>
						<td>0 11 4 * mon-wed /usr/local/etc/rc.d/lighttpd restart </td>
						<td>每月的4号与每周一到周三的11点重启apache  </td>
					</tr>
					<tr>
						<td>0 4 1 jan * /usr/local/etc/rc.d/lighttpd restart </td>
						<td>一月一号的4点重启apache  </td>
					</tr>
					<tr>
						<td>0 7 * * * /bin/ls </td>
						<td>每天早上7点执行一次 /bin/ls  </td>
					</tr>
					<tr>
						<td>0 6-12/3 * 12 * /usr/bin/backup</td>
						<td>在 12 月内, 每天的早上 6 点到 12 点中，每隔3个小时执行一次 /usr/bin/backup :  </td>
					</tr>
					<tr>
						<td>0 17 * * 1-5 mail -s "hi" alex@domain.name < /tmp/maildata </td>
						<td>周一到周五每天下午 5:00 寄一封信给 alex@domain.name :  </td>
					</tr>
					<tr>
						<td>20 0-23/2 * * * echo "haha" </td>
						<td>每月每天的午夜 0 点 20 分, 2 点 20 分, 4 点 20 分....执行 echo "haha"  </td>
					</tr>
				</table>
			</div>
			<!--footer-->
			<div class='footers'>
				<a href='http://www.astarblog.cn'><p>Copyright © <?php echo date("Y");?>　All rights reserved </p></a>
			</div>
		<div>
	</body>
	<script language='javascript' src='jquery.min.js'></script>
	<script language='javascript'>		
	
		/**
		* 获得当前点击元素的 TR
		*/
		function get_tr(thiss){
			return $(thiss).parent().parent();
		}
		/**
		* 获得当前 表格中 最后一个ID 的数值
		*/
		function get_tr_num(thiss){
			return parseInt(get_tr($(thiss)).parent().find('tr:last-child').find('td:first-child').html());
		}
		/**
		* 获得 input
		*/
		function get_input(){
			var vals = arguments[0] ? arguments[0] :'';
			return "<input type='text' value=\""+ vals +"\">";
		}
		/**
		* 获得 tpl
		*/
		function get_tpl(){
			var vals = arguments[0] ? arguments[0] :'';
			if(vals)
				return "<span class='td_sub'>确认</span><span class='td_undo'>取消</span>";
			else
				return "<span class='td_add'>添加</span><span class='td_edit'>编辑</span><span class='td_del'>删除</span>";
		}
		
		$(function(){			
			/**
			* 点击添加
			*/
			$('body').on('click','.td_add',function(){

				var tr_num = get_tr_num($(this));
				if(isNaN(tr_num)){
				   console.error('why? ID not number');
				   return false;
				}else{
					tr_num = tr_num +1;
				}
					
				var tpl  = "<tr>";
					tpl +=	"<td>"+tr_num+" </td>";
					tpl +=	"<td>"+get_input()+"</td>";
					tpl +=	"<td>"+get_input()+"</td>";
					tpl +=	"<td>"+get_input()+"</td>";
					tpl +=	"<td>"+get_input()+"</td>";
					tpl +=	"<td>"+get_input()+"</td>";
					tpl +=	"<td>"+get_input()+"</td>";
					tpl +=	"<td><span class='td_sub'>确认</span><span class='td_del'>删除</span></td>";
					tpl +=	"</tr>";
				get_tr($(this)).parent().append(tpl);
				console.log('点击添加');
			});
			/**
			* 点击编辑
			*/
			$('body').on('click','.td_edit',function(){
				
				get_tr($(this)).find('td').each(function(i,va){
					if( i > 0 && i < 7){
						$(va).html(get_input($(va).html()));
					}
					if(i==7)
						$(va).html(get_tpl(1));
				});
				console.log('点击编辑');						// 提示点击编辑
				//console.log(parseInt(get_tr($(this)).parent().find('tr:last-child').find('td:first-child').html()));
				//console.log($(this).parent().parent().find('td').first().html());
			});
			/**
			* 点击删除
			*/			
			$('body').on('click', '.td_del', function() {
				if(get_tr($(this)).parent().find('tr').length == 2){
					alert('只胜最后一行，请点击编辑...');
					return false;
				}
				
				if(!confirm("确定要删除吗？"))				// 确认 是否删除
					return false;
				get_tr($(this)).remove();					// 删除 当前点击 DOM 
				console.log('删除成功');					// 提示删除成功
			});
			/**
			* 点击确认
			*/
			$('body').on('click','.td_sub',function(){
				get_tr($(this)).find('td').each(function(i,va){
					if( i !=0 || i!=7)
						$(va).html($(va).find('input').val());
					if(i==7)
						$(va).html(get_tpl());
				});
				console.log('确认成功');						// 提示删除成功
				
			});
			/**
			* 点击取消
			*/
			$('body').on('click','.td_undo',function(){
				get_tr($(this)).find('td').each(function(i,va){
					if( i > 0 && i < 7)
						$(va).html($(va).find('input').val());
					if(i==7)
						$(va).html(get_tpl());
				});
				console.log('取消成功');						// 提示取消成功
			});
			/**
			* 点击保存
			*/
			$('body').on('click','.td_save',function(){
				var arr = new Array();
				$('table').find('tr').each(function(i,va){
					if(i > 0){
						arr[i] = new Array();
						$(va).find('td').each(function(j,vj){
							if( j > 0 && j < 7){
								arr[i][j] = $(vj).html();
							}
						});
					}
				});
				// POST 提交
				$.post('crontab.php',{data:arr},function(e){
					if(e != 'fail'){
						alert('设置成功....');
						window.location.reload();
					}
				});
			});
			
		});
	</script>
</html>