<?php
date_default_timezone_set("Asia/Shanghai");
$hostname_monthreport = "174.139.144.250";
$database_monthreport = "wssns";
$username_monthreport = "wallstreetsql001";
$password_monthreport = "nAcbLHKDYMtFYaLv";
$monthreport = mysql_pconnect($hostname_monthreport, $username_monthreport, $password_monthreport) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
<?php
function format($content){
	
	 $rex='/((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z0-9]+)+(?:\:[0-9]*)?(?:\/[^\x{4e00}-\x{9fa5}\s<\'\"“”‘’]*)?)/u';

	 preg_match_all($rex,$content,$matches);
	 
	 foreach ($matches[0] as $longword)
	$content = str_replace ($longword, substr ($longword, 0, 30) , $content);

    $content = str_replace("#","",$content);
	
    return $content;
}


function getShort($str, $length = 40, $ext = '') {
	$str	=	htmlspecialchars($str);
	$str	=	strip_tags($str);
	$str	=	htmlspecialchars_decode($str);
	$strlenth	=	0;
	$out		=	'';
	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/", $str, $match);
	foreach($match[0] as $v){
		preg_match("/[\xe0-\xef][\x80-\xbf]{2}/",$v, $matchs);
		if(!empty($matchs[0])){
			$strlenth	+=	1;
		}elseif(is_numeric($v)){
			//$strlenth	+=	0.545;  // 字符像素宽度比例 汉字为1
			$strlenth	+=	0.5;    // 字符字节长度比例 汉字为1
		}else{
			//$strlenth	+=	0.475;  // 字符像素宽度比例 汉字为1
			$strlenth	+=	0.5;    // 字符字节长度比例 汉字为1
		}

		if ($strlenth > $length) {
			$output .= $ext;
			break;
		}

		$output	.=	$v;
	}
	return $output;
}

mysql_query("set character set 'utf8'"); 
mysql_select_db($database_monthreport, $monthreport);
$query_Recordset1 = "SELECT * FROM t_weibo WHERE uid=8 and isdel=0 order by weibo_id desc limit 15";
$Recordset1 = mysql_query($query_Recordset1, $monthreport) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>华尔街见闻新闻直播</title>
<style type="text/css">
body,td,th {
	font-size: 12px;
	line-height:20px;
	color: #999;
	font-family: "Microsoft Yahei","微软雅黑",Arial,Helvetica,sans-serif;
	background-color:#F0F7FC;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
a {
	font-size: 12px;
	color: #637D97;
}
a:visited {
	color: #637D97;
	text-decoration: none;
}
a:hover {
	color: #3E4E5E;
	text-decoration: none;
}
a:active {
	color: #637D97;
	text-decoration: none;
}
td{padding:5px;}
a:link {
	text-decoration: none;
}
</style>
</head>

<body>
<div style="width:340px;">
<div style="overflow:hidden;height:420px;">
<table width="340" height="30" border="0" cellpadding="0" cellspacing="0">
  <?php 
  
  do { ?> <tr>
    <td width="30" align="center" valign="top"><?php echo date("H:i",$row_Recordset1['ctime']);?></td>
   
      <td width="310" align="left" valign="top"><a href="http://t.wallstreetcn.com/live" target="_blank"><?php echo str_replace("&lt;br /&gt;","",getShort(format($row_Recordset1['content']),60,".."));?></a></td>
  
     
  </tr> <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
</div>
<div style="text-align:right;height:25px;"><a href="http://t.wallstreetcn.com/live" target="_blank">更多24小时滚动新闻播报>></a></div>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
