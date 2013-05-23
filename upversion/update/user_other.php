<?php
/**
 * 用户附属相关数据
 * @example
 * 1.用户组信息未实现
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'user_other') {
	// user_blacklist表
	$user_blacklist_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'user_blacklist`;';
	$user_black_list = $old_db->query($user_blacklist_sql);
	$data = array();
	foreach($user_black_list as $value) {
		$value = updateValue($value);
		$data[] = "('".$value['uid']."','".$value['fid']."','".$value['ctime']."')";
	}
	truncateTable($db, $db_conf['DB_PREFIX'].'user_blacklist');
	$insert_user_blacklist = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'user_blacklist` VALUES '.implode(',', $data);
	$db->execute($insert_user_blacklist);

	// user_privacy表
	$user_privacy_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'user_privacy`;';
	$user_privacy_list = $old_db->query($user_privacy_sql);
	$data = array();
	foreach($user_privacy_list as $value) {
		$value['key'] == 'weibo_comment' && $value['key'] = 'comment_weibo';
		$value = updateValue($value);
		$data[] = "('".$value['uid']."','".$value['key']."','".$value['value']."')";
	}
	truncateTable($db, $db_conf['DB_PREFIX'].'user_privacy');
	$insert_user_privacy = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'user_privacy` VALUES '.implode(',', $data);
	$db->execute($insert_user_privacy);

	// user_tag表
	$user_tag_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'user_tag`;';
	$user_tag_list = $old_db->query($user_tag_sql);
	$data = array();
	foreach($user_tag_list as $value) {
		$value = updateValue($value);
		$data[] = "('public','user','".$value['uid']."','".$value['tag_id']."')";
	}
	truncateTable($db, $db_conf['DB_PREFIX'].'app_tag');
	$insert_user_tag = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'app_tag` VALUES '.implode(',', $data);
	$db->execute($insert_user_tag);

	// user_verified表
	$user_verified_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'user_verified`;';
	$user_verified_list = $old_db->query($user_verified_sql);
	$data = array();
	foreach($user_verified_list as $value) {
		$value['attach_id'] = empty($value['attach_id']) ? '' : '|'.$value['attach_id'].'|';
		$value = updateValue($value);
		$data[] = "('".$value['id']."', '".$value['uid']."', '0', '0', '', '".$value['realname']."', '0', '".$value['phone']."', '".$value['info']."', '".$value['verified']."', '".$value['attach_id']."', '".$value['reason']."')";
	}
	truncateTable($db, $db_conf['DB_PREFIX'].'user_verified');
	$insert_user_verified = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'user_verified` VALUES '.implode(',', $data);
	$db->execute($insert_user_verified);

	// 跳转
	$t = 'comment';
	$p = 1;
	echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
	exit;
}