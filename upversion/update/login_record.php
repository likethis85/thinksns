<?php
/**
 * 登陆记录相关信息
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'login_record') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 1000;
	$login_record_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'login_record` LIMIT '.$count * ($p - 1).','.$count.';';
	$login_record_list = $old_db->query($login_record_sql);
	if(empty($login_record_list)) {
		// 跳转操作
		$t = 'message_content';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($login_record_list as $value) {
			$value = updateValue($value);
			$data[] = "('".$value['login_record_id']."', '".$value['uid']."', '".$value['ip']."', '".$value['place']."', '".$value['ctime']."', '0')";
		}
		$insert_login_record = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'login_record` VALUES '.implode(',', $data);
		$db->execute($insert_login_record);

		// 跳转操作
		$t = 'login_record';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}