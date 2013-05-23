<?php
/**
 * 微博相关数据
 * @example
 * 1.序列化的数据很混乱
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if($_GET['t'] == 'feed') {
	$p = isset($_GET['p']) ? intval($_GET['p']) : 1;
	$count = 1000;
	$weibo_sql = 'SELECT * FROM `'.$old_db_conf['DB_PREFIX'].'weibo` LIMIT '.$count * ($p - 1).','.$count.';';
	$weibo_list = $old_db->query($weibo_sql);
	if(empty($weibo_list)) {
		// 跳转操作
		echo 'The upgrade has been completed';
		exit;
	} else {
		$data_feed = array();
		$data_feed_data = array();
		foreach($weibo_list as &$value) {
			$feed_type = '';
			switch($value['type']) {
				case 1:
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
				default:
					$feed_type = 'post';
			}
			$typeData = unserialize($value['type_data']);
			$isrepost = ($value['transpond_id'] != 0) ? 1 : 0;
			$isrepost == 1 && $feed_type = 'repost';
			$isrepost = mysql_escape_string($isrepost);
			$value = updateValue($value);
			$data_feed[] = "('".$value['weibo_id']."', '".$value['uid']."', '".$feed_type."', 'public', 'feed', '".$value['transpond_id']."', '".$value['ctime']."', '".$value['isdel']."', '".$value['from']."', '".$value['comment']."', '".$value['transpond']."', '".$value['comment']."', '".$isrepost."', '1')";
			// 微博相关数据序列化
			$feed_info['content'] = changeBracket(htmlspecialchars_decode($value['content']));
			$feed_info['body'] = changeBracket(htmlspecialchars_decode($value['content']));
			// 获取附件信息
			$attachIds = array();
			// 图片
			if (isset($typeData['picurl']) && $feed_type === 'postimage') {
				if (isset($typeData['attach_id'])) {
					$attachIds[] = $typeData['attach_id'];
				} else {
					// 获取图片信息
					$pathfile = '../data/upload/'.$typeData['picurl'];
					$filename = basename($typeData['picurl']);
					$picInfo = getimagesize($pathfile);
					$filesize = filesize($pathfile);
					$filearr = explode('.', $filename);
					$filetype = end($filearr);
					$insert_attach = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'attach` VALUES (null, null, null, null, "weibo_image", "'.$value['uid'].'", "'.$value['ctime'].'", "'.$filename.'", "'.$picInfo['mime'].'", "'.$filesize.'", "'.$filetype.'", "'.md5(serialize($picInfo)).'", "0", "0", "'.dirname($typeData['picurl']).'/", "'.$filename.'", "0", "0")';
					$attachIds[] = $db->execute($insert_attach);
				}
			} else {
				foreach ($typeData as $simpleTypeData) {
					if (isset($simpleTypeData['attach_id'])) {
						$attachIds[] = $simpleTypeData['attach_id'];
					} else {
						// 获取图片信息
						$pathfile = '../data/upload/'.$simpleTypeData['picurl'];
						$filename = basename($simpleTypeData['picurl']);
						$picInfo = getimagesize($pathfile);
						$filesize = filesize($pathfile);
						$filearr = explode('.', $filename);
						$filetype = end($filearr);
						$insert_attach = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'attach` VALUES (null, null, null, null, "weibo_image", "'.$value['uid'].'", "'.$value['ctime'].'", "'.$filename.'", "'.$picInfo['mime'].'", "'.$filesize.'", "'.$filetype.'", "'.md5(serialize($picInfo)).'", "0", "0", "'.dirname($simpleTypeData['picurl']).'/", "'.$filename.'", "0", "0")';
						$attachIds[] = $db->execute($insert_attach);
					}
				}
			}
			// 附件
			if (isset($typeData['file_id']) && $feed_type === 'postfile') {
				$attachIds = array();
				$attachIds[] = $typeData['file_id'];
			}
			// 视频
/*			if (isset($typeData['flashvar']) && $feed_type === 'postvideo') {
				$attachIds = array();
				$feed_info['flashvar'] = getFlashVar($typeData['host'], $typeData['flashvar'], $typeData['source']);
				$feed_info['flashimg'] = $typeData['flashimg'];
				$feed_info['host'] = $typeData['host'];
				$feed_info['source'] = $typeData['source'];
				$feed_info['title'] = $typeData['title'];
			}*/
			$feed_info['attach_id'] = $attachIds;
			$feed_info['uid'] = $value['uid'];
			$feed_info['app'] = 'public';
			$feed_info['type'] = $feed_type;
			$feed_info['app_row_id'] = 0;
			$feed_info['app_row_table'] = 'feed';
			$feed_info['publish_time'] = $value['ctime'];
			$feed_info['from'] = $value['from'];
			$feed_info['repost_count'] = $value['transpond'];
			$feed_info['comment_count'] = $value['comment'];
			$feed_info['is_del'] = $value['isdel'];
			$feed_info['is_repost'] = $isrepost;
			$feed_info['is_audit'] = 1;
			$feed_serialize = serialize($feed_info);
			$data_feed_data[] = "('".$value['weibo_id']."', '".mysql_escape_string($feed_serialize)."', '127.0.0.1', '".$value['content']."', null)";
		}
		$insert_feed = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'feed` VALUES '.implode(',', $data_feed);
		$db->execute($insert_feed);
		$insert_feed_data = 'INSERT INTO `'.$db_conf['DB_PREFIX'].'feed_data` VALUES '.implode(',', $data_feed_data);
		$db->execute($insert_feed_data);
		// 获取视频信息
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

		// 跳转操作
		$t = 'feed';
		$p = $p + 1;
		echo '<script>window.location.href="'.getJumpUrl($t, $p).'";</script>';
		exit;
	}
}

// type 微博类型，0：纯文本；1：图片；3：视频；4：音乐；5：文档     transpond_id  转发的微博ID，0：非转发 