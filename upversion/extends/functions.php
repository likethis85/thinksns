<?php
// 浏览器友好的输出
function dump($var, $echo = true, $label = null, $strict = true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if(!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre style="text-align:left">'.$label.htmlspecialchars($output,ENT_QUOTES).'</pre>';
        } else {
            $output = $label . " : " . print_r($var, true);
        }
    }else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if(!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre style="text-align:left">'. $label. htmlspecialchars($output, ENT_QUOTES). '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else {
        return $output;
    }
}

// 获取字串首字母
function getFirstLetter($s0) {
    $firstchar_ord = ord(strtoupper($s0{0}));
    if($firstchar_ord >= 65 and $firstchar_ord <= 91) return strtoupper($s0{0});
    if($firstchar_ord >= 48 and $firstchar_ord <= 57) return '#';
    $s = iconv("UTF-8", "gb2312", $s0);
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc>=-20319 and $asc<=-20284) return "A";
    if($asc>=-20283 and $asc<=-19776) return "B";
    if($asc>=-19775 and $asc<=-19219) return "C";
    if($asc>=-19218 and $asc<=-18711) return "D";
    if($asc>=-18710 and $asc<=-18527) return "E";
    if($asc>=-18526 and $asc<=-18240) return "F";
    if($asc>=-18239 and $asc<=-17923) return "G";
    if($asc>=-17922 and $asc<=-17418) return "H";
    if($asc>=-17417 and $asc<=-16475) return "J";
    if($asc>=-16474 and $asc<=-16213) return "K";
    if($asc>=-16212 and $asc<=-15641) return "L";
    if($asc>=-15640 and $asc<=-15166) return "M";
    if($asc>=-15165 and $asc<=-14923) return "N";
    if($asc>=-14922 and $asc<=-14915) return "O";
    if($asc>=-14914 and $asc<=-14631) return "P";
    if($asc>=-14630 and $asc<=-14150) return "Q";
    if($asc>=-14149 and $asc<=-14091) return "R";
    if($asc>=-14090 and $asc<=-13319) return "S";
    if($asc>=-13318 and $asc<=-12839) return "T";
    if($asc>=-12838 and $asc<=-12557) return "W";
    if($asc>=-12556 and $asc<=-11848) return "X";
    if($asc>=-11847 and $asc<=-11056) return "Y";
    if($asc>=-11055 and $asc<=-10247) return "Z";
    return '#';
}

function getJumpUrl($type, $page)
{
    $site_url = SITE_URL.'/index.php?t='.$type.'&p='.$page;

    return $site_url;
}

function truncateTable($conn, $table)
{
    $sql = 'TRUNCATE TABLE `'.$table.'`;';
    $conn->execute($sql);
}

function updateValue($value)
{
    $data = array();
    foreach($value as $key => $val) {
        $data[$key] = mysql_escape_string($val);
    }

    return $data;
}

function writeErrorLog($errorSql, $pk = '')
{
    $filename = './error.txt';
    !empty($pk) && $pk .= "\n\r";
    $errorSql .= "\n\r";
    $content = $pk.$errorSql;
    if (is_writable($filename)) {
        if(!$handle = fopen($filename, 'a')) {
             echo "不能打开文件 $filename";
             exit;
        }
        if(fwrite($handle, $content) === false) {
            echo "不能写入到文件 $filename";
            exit;
        }
        echo "成功地将 $content 写入到文件$filename";
        fclose($handle);
    } else {
        echo "文件 $filename 不可写";
    }
}

function clearAllData($conn)
{
    $sqls = array();
    $sqls[] = 'TRUNCATE TABLE `ts_app_tag`';
    $sqls[] = 'TRUNCATE TABLE `ts_atme`';
    $sqls[] = 'TRUNCATE TABLE `ts_attach`';
    $sqls[] = 'TRUNCATE TABLE `ts_collection`';
    $sqls[] = 'TRUNCATE TABLE `ts_comment`';
    $sqls[] = 'TRUNCATE TABLE `ts_credit_user`';
    $sqls[] = 'TRUNCATE TABLE `ts_feed`';
    $sqls[] = 'TRUNCATE TABLE `ts_feed_data`';
    $sqls[] = 'TRUNCATE TABLE `ts_feed_topic`';
    $sqls[] = 'TRUNCATE TABLE `ts_feed_topic_link`';
    $sqls[] = 'TRUNCATE TABLE `ts_login`';
    $sqls[] = 'TRUNCATE TABLE `ts_login_record`';
    $sqls[] = 'TRUNCATE TABLE `ts_message_content`';
    $sqls[] = 'TRUNCATE TABLE `ts_message_list`';
    $sqls[] = 'TRUNCATE TABLE `ts_message_member`';
    $sqls[] = 'TRUNCATE TABLE `ts_tag`';
    $sqls[] = 'TRUNCATE TABLE `ts_user`';
    $sqls[] = 'TRUNCATE TABLE `ts_user_blacklist`';
    $sqls[] = 'TRUNCATE TABLE `ts_user_follow`';
    $sqls[] = 'TRUNCATE TABLE `ts_user_follow_group`';
    $sqls[] = 'TRUNCATE TABLE `ts_user_follow_group_link`';
    $sqls[] = 'TRUNCATE TABLE `ts_user_group_link`';
    $sqls[] = 'TRUNCATE TABLE `ts_user_privacy`';
    $sqls[] = 'TRUNCATE TABLE `ts_user_verified`';
    foreach($sqls as $sql) {
        $conn->execute($sql);
    }
}

/**
 * t函数用于过滤标签，输出没有html的干净的文本
 * @param string text 文本内容
 * @return string 处理后内容
 */
function t($text){
	$text = nl2br($text);
	$text = real_strip_tags($text);
	//$text = htmlspecialchars($text,ENT_QUOTES);
	$text = str_ireplace(array("\r","\n","\t","&nbsp;"),'',$text);
	$text = trim($text);
	return $text;
}

function real_strip_tags($str, $allowable_tags="") {
	$str = stripslashes(htmlspecialchars_decode($str));
	return strip_tags($str, $allowable_tags);
}

function getFlashVar ($host, $flashvar, $source) {
    if (strpos($flashvar, '/') !== false) {
        return $flashvar;
    }
    $return = '';

    switch ($host) {
        case 'youku.com':
            $return = 'http://player.youku.com/player.php/sid/'.$flashvar.'/v.swf';
            break;
        case 'ku6.com':
            $return = 'http://player.ku6.com/refer/'.$flashvar.'/v.swf';
            break;
        case 'tudou.com':
            if (strpos($source, 'www.tudou.com/albumplay') !== false) {
                $return = 'http://www.tudou.com/a/'.$flashvar.'/&autoPlay=true/v.swf';
            } else if (strpos($source, 'www.tudou.com/programs') !== false) {
                $return = 'http://www.tudou.com/v/'.$flashvar.'/&autoPlay=true/v.swf';
            } else if (strpos($source, 'www.tudou.com/listplay') !== false) {
                $return = 'http://www.tudou.com/l/'.$flashvar.'/&autoPlay=true/v.swf';
            } else if (strpos($source, 'douwan.tudou.com') !== false) {
                $return = 'http://www.tudou.com/v/'.$flashvar.'/&autoPlay=true/v.swf';
            }
            break;
        case 'youtube.com':
            $return = 'http://www.youtube.com/embed/'.$flashvar;
            break;
        case 'sohu.com':
            $return = 'http://share.vrs.sohu.com/'.$flashvar.'/v.swf&autoplay=false';
            $return = $flashvar;
            break;
        case 'qq.com':
            $return = 'http://static.video.qq.com/TPout.swf?vid='.$flashvar.'&auto=1';
            break;
        case 'sina.com.cn':
            break;
        case 'yinyuetai.com':
            $return = 'http://player.yinyuetai.com/video/player/'.$flashvar.'/v_0.swf';
            break;
    }

    return $return;
}

function changeBracket ($content) {
    $content = str_replace('&#091;', '[', $content);
    $content = str_replace('&#093;', ']', $content);
    return $content;
}