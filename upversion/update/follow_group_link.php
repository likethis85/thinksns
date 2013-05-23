<?php
/**
 * 关注分组与用户相关数据
 * @example
 * 1.用户数据fid未填写
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'follow_group_link') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 10000;
	$weibo_follow_group_link_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo_follow_group_link` LIMIT '.$count * ($p - 1).','.$count.';';
	$weibo_follow_group_link_list = $old_db->query($weibo_follow_group_link_sql);
	if(empty($weibo_follow_group_link_list)) {
		// 跳转操作
		$t = 'topic';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($weibo_follow_group_link_list as $value) {
			$value = updateValue($value);
			$follow_sql = 'SELECT `fid` FROM '.$old_db_conf['DB_PREFIX'].'weibo_follow WHERE `follow_id` = "'.$value['follow_id'].'"';
			$fid = $old_db->query($follow_sql);
			if (empty($fid)) {
				continue;
			}
			$fid = $fid[0]['fid'];
			$data[] = "('".$value['follow_group_link_id']."', '".$value['follow_group_id']."', '".$value['follow_id']."', '".mysql_escape_string($fid)."','".$value['uid']."')";
		}
		$insert_weibo_follow_group_link = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'user_follow_group_link` VALUES '.implode(',', $data);
		$db->execute($insert_weibo_follow_group_link);

		// 跳转操作
		$t = 'follow_group_link';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}