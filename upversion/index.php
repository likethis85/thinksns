<?php
/**
 * 将2.8数据转化3.0
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
set_time_limit(0);
ini_set('display_errors',false);
//error_reporting(E_ALL); //调试、找错时请弃用这一行配置，注释下一行配置
header('Content-type: text/html; charset=UTF-8');

define('IS_CGI',substr(PHP_SAPI, 0, 3)=='cgi' ? 1 : 0 );
// 当前文件名
if(!defined('_PHP_FILE_')) {
	if(IS_CGI) {
		// CGI/FASTCGI模式下
		$_temp  = explode('.php',$_SERVER["PHP_SELF"]);
		define('_PHP_FILE_', rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/'));
	}else {
		define('_PHP_FILE_', rtrim($_SERVER["SCRIPT_NAME"],'/'));
	}
}
// 网站URL根目录
if(!defined('__ROOT__')) {
	$_root = dirname(_PHP_FILE_);
	define('__ROOT__',  (($_root=='/' || $_root=='\\')?'':rtrim($_root,'/')));
}

define('SITE_DOMAIN'	,	strip_tags($_SERVER['HTTP_HOST']));
define('SITE_URL'		,	'http://'.SITE_DOMAIN.__ROOT__);

require_once('./extends/Db.php');
require_once('./extends/functions.php');

// 2.8数据库配置
$old_db_conf['DB_TYPE'] = 'mysql';
$old_db_conf['DB_HOST'] = '127.0.0.1';
$old_db_conf['DB_NAME'] = 'wssns';
$old_db_conf['DB_USER'] = 'root';
$old_db_conf['DB_PWD'] = '582tsost';
$old_db_conf['DB_PORT'] = 3306;
$old_db_conf['DB_PREFIX'] = 't_';
$old_db_conf['DB_CHARSET'] = 'utf8';
// 3.0数据库配置
$db_conf['DB_TYPE'] = 'mysql';
$db_conf['DB_HOST'] = '127.0.0.1';
$db_conf['DB_NAME'] = 'snsv3';
$db_conf['DB_USER'] = 'root';
$db_conf['DB_PWD'] = '582tsost';
$db_conf['DB_PORT'] = 3306;
$db_conf['DB_PREFIX'] = 'wscn_';
$db_conf['DB_CHARSET'] = 'utf8';
// 数据库连接对象
$old_db = new Db($old_db_conf);
$db = new Db($db_conf);

$t = $_GET['t'] = $_GET['t'] ? t($_GET['t']) : 'user';
$p = $_GET['p'] = intval( $_GET['p'] );

if($t == 'user' && ($p == 1 || !isset($p))) {
	clearAllData($db);
}
echo 'Updating now, Please wait...<br/><br/>';
switch($t) {
	case 'user':
		// 导入用户OK
		require_once('./update/user.php');
		break;
	case 'user_other':
		// 导入用户附加信息OK
		require_once('./update/user_other.php');
		break;
	case 'comment';
		// 微博评论OK
		require_once('./update/comment.php');
		break;
	case 'credit':
		// 用户积分OK 
		require_once('./update/credit.php');
		break;
	case 'login':
		// 登陆信息OK
		require_once('./update/login.php');
		break;
	case 'login_record':
		// 登陆记录OK
		require_once('./update/login_record.php');
		break;
	case 'message_content':
		// 私信内容OK
		require_once('./update/message_content.php');
		break;
	case 'message_list':
		// 私信列表OK
		require_once('./update/message_list.php');
		break;
	case 'message_member':
		// 私信成员OK
		require_once('./update/message_member.php');
		break;
	case 'tag':
		// 标签OK
		require_once('./update/tag.php');
		break;
	case 'atme':
		// @Me OK
		require_once('./update/atme.php');
		break;
	case 'favorite':
		// 收藏OK
		require_once('./update/favorite.php');
		break;
	case 'follow':
		// 关注OK
		require_once('./update/follow.php');
		break;
	case 'follow_group':
		// 用户关注组OK
		require_once('./update/follow_group.php');
		break;
	case 'follow_group_link':
		// 关注组链接OK
		require_once('./update/follow_group_link.php');
		break;
	case 'topic':
		// 话题OK
		require_once('./update/topic.php');
		break;
	case 'topic_link':
		// 话题链接OK
		require_once('./update/topic_link.php');
		break;
	case 'attach':
		// 附件表OK
		require_once('./update/attach.php');
		break;
	case 'feed':
		// 微博OK
		require_once('./update/feed.php');
		break;
	case 'check':
		require_once('./update/check.php');
		break;
	case 'other':
		require_once('./update/other.php');
		break;
}
