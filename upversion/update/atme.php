<?php
/**
 * @Me相关数据
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'atme') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 10000;
	$weibo_atme_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo_atme` LIMIT '.$count * ($p - 1).','.$count.';';
	$weibo_atme_list = $old_db->query($weibo_atme_sql);
	if(empty($weibo_atme_list)) {
		// 跳转操作
		$t = 'favorite';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($weibo_atme_list as $value) {
			$value = updateValue($value);
			$data[] = "(null, 'Public', 'feed', '".$value['weibo_id']."','".$value['uid']."')";
		}
		$insert_weibo_atme = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'atme` VALUES '.implode(',', $data);
		$db->execute($insert_weibo_atme);

		// 跳转操作
		$t = 'atme';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}