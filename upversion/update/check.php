<?php
/**
 * 微博相关数据
 * @example
 * 1.序列化的数据很混乱
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'check') {
	$arr['atme'] = checkData('weibo_atme', 'atme');
	$arr['attach'] = checkData('attach', 'attach');
	$arr['comment'] = checkData('weibo_comment', 'comment');
	$arr['credit'] = checkData('credit_user', 'credit_user');
	$arr['favorite'] = checkData('weibo_favorite', 'collection');
	$arr['feed'] = checkData('weibo', 'feed');
	$arr['follow_group_link'] = checkData('weibo_follow_group_link', 'user_follow_group_link');
	$arr['follow_group'] = checkData('weibo_follow_group', 'user_follow_group');
	$arr['follow'] = checkData('weibo_follow', 'user_follow');
	$arr['login_record'] = checkData('login_record', 'login_record');
	$arr['login'] = checkData('login', 'login');
	$arr['message_content'] = checkData('message_content', 'message_content');
	$arr['message_list'] = checkData('message_list', 'message_list');
	$arr['message_member'] = checkData('message_member', 'message_member');
	$arr['tag'] = checkData('tag', 'tag');
	$arr['topic_link'] = checkData('weibo_topic_link', 'feed_topic_link');
	$arr['topic'] = checkData('weibo_topic', 'feed_topic');
	$arr['user'] = checkData('user', 'user');
	$arr['user_other'] = checkData('user_tag', 'app_tag');
	
	dump($arr);
}

function checkData($oldTable, $newTable){
	global $old_db, $db, $old_db_conf, $db_conf;
	
	$sql = 'SELECT count(*) as num FROM `'.$old_db_conf['DB_PREFIX'].$oldTable.'`';
	$data = $old_db->query($sql);
	$res['oldNum'] = $data[0]['num'];
	
	$sql = 'SELECT count(*) as num FROM `'.$db_conf['DB_PREFIX'].$newTable.'`';
	$data = $db->query($sql);
	$res['newNum'] = $data[0]['num'];
	
	$res['result'] = $res['newNum'] < $res['oldNum'] ? '不正常' : '正常';
	return $res;
}
