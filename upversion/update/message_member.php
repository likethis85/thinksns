<?php
/**
 * 私信成员相关数据
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'message_member') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 1000;
	$message_member_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'message_member` LIMIT '.$count * ($p - 1).','.$count.';';
	$message_member_list = $old_db->query($message_member_sql);
	if(empty($message_member_list)) {
		// 跳转操作
		$t = 'tag';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($message_member_list as $value) {
			$value = updateValue($value);
			$data[] = "('".$value['list_id']."', '".$value['member_uid']."', '".$value['new']."', '".$value['message_num']."', '".$value['ctime']."', '".$value['list_ctime']."', '0')";
		}
		$insert_message_member = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'message_member` VALUES '.implode(',', $data);
		$db->execute($insert_message_member);

		// 跳转操作
		$t = 'message_member';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}