<?php
/**
 * 私信内容相关信息
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'message_content') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 1000;
	$message_content_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'message_content` LIMIT '.$count * ($p - 1).','.$count.';';
	$message_content_list = $old_db->query($message_content_sql);
	if(empty($message_content_list)) {
		// 跳转操作
		$t = 'message_list';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($message_content_list as $value) {
			$value = updateValue($value);
			$data[] = "('".$value['message_id']."', '".$value['list_id']."', '".$value['from_uid']."', '".$value['content']."', '".$value['is_del']."', '".$value['mtime']."')";
		}
		$insert_message_content = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'message_content` VALUES '.implode(',', $data);
		$db->execute($insert_message_content);

		// 跳转操作
		$t = 'message_content';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}