<?php
/**
 * 话题数据转移
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'topic') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 1000;
	$weibo_topic_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo_topic` LIMIT '.$count * ($p - 1).','.$count.';';
	$weibo_topic_list = $old_db->query($weibo_topic_sql);
	if(empty($weibo_topic_list)) {
		// 跳转操作
		$t = 'topic_link';
		$p = 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	} else {
		$data = array();
		foreach($weibo_topic_list as $value) {
			// 获取topics信息
			$topics_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo_topics` WHERE `topic_id` = '.$value['topic_id'].' LIMIT 1;';
			$topics_info = $old_db->query($topics_sql);
			$value = updateValue($value);
			$data[] = "('".$value['topic_id']."', '".$value['name']."', '".$value['count']."', '".$value['ctime']."', '".$value['status']."', '".$value['lock']."', '".$topics_info['domain']."', '".$topics_info['recommend']."', '".$topics_info['ctime']."', null, '".$topics_info['link']."', '".$topics_info['pic']."', '0', '".$topics_info['note']."', null)";
		}
		$insert_weibo_topic = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'feed_topic` VALUES '.implode(',', $data);
		$db->execute($insert_weibo_topic);

		// 跳转操作
		$t = 'topic';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}