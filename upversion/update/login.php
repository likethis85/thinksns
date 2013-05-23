<?php
/**
 * 登录相关数据
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'login') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 1000;
	$login_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'login` LIMIT '.$count * ($p - 1).','.$count.';';
	$login_list = $old_db->query($login_sql);
	if(empty($login_list)) {
		// 跳转操作
		$t = 'login_record';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($login_list as $value) {
			$value = updateValue($value);
			$data[] = "('".$value['login_id']."', '".$value['uid']."', '".$value['type_uid']."', '".$value['type']."', '".$value['oauth_token']."', '".$value['oauth_token_secret']."', '".$value['is_sync']."')";
		}
		$insert_login = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'login` VALUES '.implode(',', $data);
		$db->execute($insert_login);

		// 跳转操作
		$t = 'login';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}