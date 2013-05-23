<?php
/**
 * 话题关联数据转移
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'topic_link') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 1000;
	$weibo_topic_link_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo_topic_link` LIMIT '.$count * ($p - 1).','.$count.';';
	$weibo_topic_link_list = $old_db->query($weibo_topic_link_sql);
	if(empty($weibo_topic_link_list)) {
		// 跳转操作
		$t = 'attach';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($weibo_topic_link_list as $value) {
			$feed_type = '';
			switch($value['type']) {
				case 1:
					$feed_type = 'post';
					break;
				case 2:
					$feed_type = 'postimage';
					break;
				case 3:
					$feed_type = 'postvideo';
					break;
				case 4:
					$feed_type = 'postmusic';
					break;
				case 5:
					$feed_type = 'postfile';
					break;
			}
			$value = updateValue($value);
			$data[] = "('".$value['weibo_topic_id']."', '".$value['weibo_id']."', '".$value['topic_id']."', '".$feed_type."')";
		}
		$insert_weibo_topic_link = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'feed_topic_link` VALUES '.implode(',', $data);
		$db->execute($insert_weibo_topic_link);

		// 跳转操作
		$t = 'topic_link';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}