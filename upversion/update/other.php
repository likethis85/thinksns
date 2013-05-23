<?php
if ($_GET['t'] == 'other') {
	$feed_sql = 'SELECT b.* FROM `'.$db_conf['DB_PREFIX'].'feed` AS a LEFT JOIN `'.$db_conf['DB_PREFIX'].'feed_data` AS b ON a.`feed_id` = b.`feed_id` WHERE `type` = "postfile"';
	$feed_list = $db->query($feed_sql);
	if (!empty($feed_list)) {
		foreach ($feed_list as $feed) {
			// 获取附件ID
			$old_feed_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo` WHERE `weibo_id` = "'.$feed['feed_id'].'" LIMIT 1';
			$old_feed = $old_db->query($old_feed_sql); 
			$old_feed = $old_feed[0];
			$old_type_data = unserialize($old_feed['type_data']);
			$feed_data = unserialize($feed['feed_data']);
			if (isset($old_type_data['file_id'])) {
				$feed_data['attach_id'] = array();
				$feed_data['attach_id'][] = $old_type_data['file_id'];
			}
			$feed_data = serialize($feed_data);
			$feed_data = mysql_escape_string($feed_data);
			$update_sql = 'UPDATE `'.$db_conf['DB_PREFIX'].'feed_data` SET `feed_data` = \''.$feed_data.'\' WHERE `feed_id` = "'.$feed['feed_id'].'" LIMIT 1';
			// dump($update_sql);
			$db->execute($update_sql);
		}
	}

	echo 'file OK', '<br />';

	$feed_sql = 'SELECT b.* FROM `'.$db_conf['DB_PREFIX'].'feed` AS a LEFT JOIN `'.$db_conf['DB_PREFIX'].'feed_data` AS b ON a.`feed_id` = b.`feed_id` WHERE `type` = "postvideo" ORDER BY b.feed_id DESC';
	$feed_list = $db->query($feed_sql);
	if (!empty($feed_list)) {
		foreach ($feed_list as $feed) {
			// 获取原始视频数据
			$old_feed_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo` WHERE `weibo_id` = "'.$feed['feed_id'].'" LIMIT 1';
			$old_feed = $old_db->query($old_feed_sql); 
			$old_feed = $old_feed[0];
			$old_type_data = unserialize($old_feed['type_data']);
			$feed_data = unserialize($feed['feed_data']);
			if (isset($old_type_data['flashvar'])) {
				$feed_data['attach_id'] = array();
				$feed_data['flashvar'] = getFlashVar($old_type_data['host'], $old_type_data['flashvar'], $old_type_data['source']);
				$feed_data['flashimg'] = $old_type_data['flashimg'];
				$feed_data['host'] = $old_type_data['host'];
				$feed_data['source'] = $old_type_data['source'];
				$feed_data['title'] = $old_type_data['title'];
			}
			$feed_data = serialize($feed_data);
			$feed_data = mysql_escape_string($feed_data);
			$update_sql = 'UPDATE `'.$db_conf['DB_PREFIX'].'feed_data` SET `feed_data` = \''.$feed_data.'\' WHERE `feed_id` = "'.$feed['feed_id'].'" LIMIT 1';
			$db->execute($update_sql);
		}
	}
	
	echo 'video OK', '<br />';

	$feed_sql = 'SELECT * FROM `'.$db_conf['DB_PREFIX'].'feed_data` where `feed_data` LIKE "%&amp;#091;%" OR `feed_data` LIKE "%&amp;#093;%"';
	$feed_list = $db->query($feed_sql);
	if (!empty($feed_list)) {
		foreach ($feed_list as &$feed) {
			$feed['feed_data'] = unserialize($feed['feed_data']);
			$feed['feed_data']['content'] = changeBracket(htmlspecialchars_decode($feed['feed_data']['content']));
			$feed['feed_data']['body'] = changeBracket(htmlspecialchars_decode($feed['feed_data']['body']));
			$feed['feed_data'] = serialize($feed['feed_data']);
			$feed['feed_content'] = changeBracket(htmlspecialchars_decode($feed['feed_content']));
			$update_sql = 'UPDATE `'.$db_conf['DB_PREFIX'].'feed_data` SET `feed_data` = \''.mysql_escape_string($feed['feed_data']).'\', `feed_content` = \''.mysql_escape_string($feed['feed_content']).'\' WHERE `feed_id` = "'.$feed['feed_id'].'"';
			$db->execute($update_sql);
		}
	}

	echo 'face OK';
	exit;
}