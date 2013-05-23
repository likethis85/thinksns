<?php
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

function qtime($time){
       $limit = time() - $time;
 
       if($limit<60)
       $time="{$limit} 秒前";
       if($limit>=60 && $limit<3600){
            $i = floor($limit/60);
            $_i = $limit%60;
            $s = $_i;
           $time="{$i} 分 {$s} 秒前";
       }
       if($limit>=3600 && $limit<3600*24){
            $h = floor($limit/3600);
            $_h = $limit%3600;
            $i = ceil($_h/60);
            $time="{$h} 小时 {$i} 分前";
       }
       if($limit>=(3600*24) && $limit<(3600*24*30)){
            $d = floor($limit/(3600*24));
            $time= "{$d} 天前";
        }
		if($limit>=(3600*24*30)){
            $time=gmdate('Y年n月j日', $time);
        }
		return $time;
}

function getNodeid($weibo_id,&$monthreport) {
	$query_Recordset2 = "SELECT * FROM t_weibo where weibo_id= $weibo_id limit 1";
	$Recordset2 = mysql_query($query_Recordset2, $monthreport) or die(mysql_error());
	$row_Recordset2 = mysql_fetch_assoc($Recordset2);
	return $row_Recordset2['nodeid'];
}

function getShort($str, $length = 40, $ext = '') {
	$output	=	"";
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
$query_Recordset1 = "SELECT *
FROM t_weibo_comment as a
WHERE a.weibo_id
IN (

SELECT b.weibo_id
FROM t_weibo as b
WHERE b.nodeid >0
AND b.isdel =0
)
AND a.isdel =0
ORDER BY a.comment_id DESC 
LIMIT 30";
$Recordset1 = mysql_query($query_Recordset1, $monthreport) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>最新评论</title>
<style type="text/css">
body,td,th {
	font-size: 12px;
	line-height:18px;
	color: #999;
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
span{float:right;color:#333333;}
</style>
</head>

<body>
<div style="width:340px;">
<div style="overflow:hidden;height:520px;">
<table width="340" border="0" cellpadding="0" cellspacing="0">
  <?php 
  
  do { ?> <tr>
      <td width="310" align="left" valign="top"><a href="http://wallstreetcn.com/node/<?php echo getNodeid($row_Recordset1['weibo_id'],$monthreport);?>#commentarea" target="_blank"><?php echo getShort(format($row_Recordset1['content']),100,"..");?></a>
	  
	  <BR><span><?php echo qtime($row_Recordset1['ctime']);?><span>
	  </td>
  
     
  </tr> <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
</div>
<div style="text-align:right;height:25px;"><a href="http://t.wallstreetcn.com" target="_blank">更多社区评论>></a></div>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
