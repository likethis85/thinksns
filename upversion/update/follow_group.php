<?php
/**
 * 关注用户组相关数据
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'follow_group') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 10000;
	$weibo_follow_group_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo_follow_group` LIMIT '.$count * ($p - 1).','.$count.';';
	$weibo_follow_group_list = $old_db->query($weibo_follow_group_sql);
	if(empty($weibo_follow_group_list)) {
		// 跳转操作
		$t = 'follow_group_link';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($weibo_follow_group_list as $value) {
			$value = updateValue($value);
			$data[] = "('".$value['follow_group_id']."', '".$value['uid']."', '".$value['title']."', '".$value['ctime']."')";
		}
		$insert_follow_group = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'user_follow_group` VALUES '.implode(',', $data);
		$db->execute($insert_follow_group);

		// 跳转操作
		$t = 'follow_group';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}