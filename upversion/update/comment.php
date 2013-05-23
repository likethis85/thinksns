<?php
/**
 * 评论相关数据
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'comment') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 1000;
	$weibo_comment_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo_comment` LIMIT '.$count * ($p - 1).','.$count.';';
	$weibo_comment_list = $old_db->query($weibo_comment_sql);
	if(empty($weibo_comment_list)) {
		// 跳转操作
		$t = 'credit';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($weibo_comment_list as $value) {
			$weibo_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo` WHERE `weibo_id` = '.$value['weibo_id'].' LIMIT 1;';
			$feed_info = $old_db->query($weibo_sql);
			$value = updateValue($value);
			$data[] = "('".$value['comment_id']."', 'public', 'feed', '".$value['weibo_id']."', '".$feed_info['uid']."', '".$value['uid']."', '".$value['content']."', '".$value['reply_comment_id']."', '".$value['reply_uid']."', '', '".$value['ctime']."', '".$value['isdel']."', '0', '1', '0')";
		}
		$insert_weibo_comment = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'comment` VALUES '.implode(',', $data);
		$db->execute($insert_weibo_comment);

		// 跳转操作
		$t = 'comment';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}