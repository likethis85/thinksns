<?php
/**
 * 关注相关数据
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'follow') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 10000;
	$weibo_follow_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo_follow` WHERE `type` = "0" LIMIT '.$count * ($p - 1).','.$count.';';
	$weibo_follow_list = $old_db->query($weibo_follow_sql);
	if(empty($weibo_follow_list)) {
		// 跳转操作
		$t = 'follow_group';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($weibo_follow_list as $value) {
			$value = updateValue($value);
			$data[] = "('".$value['follow_id']."', '".$value['uid']."', '".$value['fid']."', '', '0')";
		}
		$insert_weibo_follow = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'user_follow` VALUES '.implode(',', $data);
		$result = $db->execute($insert_weibo_follow);
		if($result === false) {
			foreach($data as $single_weibo_follow) {
				$single_insert_weibo_follow = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'user_follow` VALUES '.$single_weibo_follow;
				$result = $db->execute($single_insert_weibo_follow);
			}
		}

		// 跳转操作
		$t = 'follow';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}