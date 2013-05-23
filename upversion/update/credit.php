<?php
/**
 * 用户积分相关数据
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'credit') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 10000;
	$user_credit_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'credit_user` LIMIT '.$count * ($p - 1).','.$count.';';
	$user_credit_list = $old_db->query($user_credit_sql);
	if(empty($user_credit_list)) {
		// 跳转操作
		$t = 'login';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($user_credit_list as $value) {
			$value = updateValue($value);
			$data[] = "('".$value['id']."', '".$value['uid']."', '".$value['experience']."', '".$value['score']."')";
		}
		$insert_user_credit = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'credit_user` VALUES '.implode(',', $data);
		$db->execute($insert_user_credit);

		// 跳转操作
		$t = 'credit';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}