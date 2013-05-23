<?php
/**
 * 私信列表相关数据
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'message_list') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 1000;
	$message_list_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'message_list` LIMIT '.$count * ($p - 1).','.$count.';';
	$message_list_list = $old_db->query($message_list_sql);
	if(empty($message_list_list)) {
		// 跳转操作
		$t = 'message_member';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($message_list_list as $value) {
			$value = updateValue($value);
			$data[] = "('".$value['list_id']."', '".$value['from_uid']."', '".$value['type']."', '".$value['title']."', '".$value['member_num']."', '".$value['min_max']."', '".$value['mtime']."', '".$value['last_message']."')";
		}
		$insert_message_list = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'message_list` VALUES '.implode(',', $data);
		$db->execute($insert_message_list);

		// 跳转操作
		$t = 'message_list';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}