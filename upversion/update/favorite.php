<?php
/**
 * 收藏相关数据
 * @example
 * 1.收藏时间全部为空
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'favorite') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 1000;
	$weibo_favorite_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo_favorite` LIMIT '.$count * ($p - 1).','.$count.';';
	$weibo_favorite_list = $old_db->query($weibo_favorite_sql);
	if(empty($weibo_favorite_list)) {
		// 跳转操作
		$t = 'follow';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($weibo_favorite_list as $value) {
			$value = updateValue($value);
			$data[] = "(null,'".$value['uid']."','".$value['weibo_id']."','feed','public','0')";
		}
		$insert_weibo_favorite = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'collection` VALUES '.implode(',', $data);
		$db->execute($insert_weibo_favorite);

		// 跳转操作
		$t = 'favorite';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}