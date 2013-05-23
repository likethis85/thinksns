<?php
// 论坛API接口
class ForumApi extends Api {

	//获取版块列表
	public function board(){
		return D('Core', 'forum')->getCategory();
	}

	//获取版块分类列表
	public function category(){
		$fid = intval($this->data['fid']);
		if(!$fid) return 0;
		return D('ForumApi', 'forum')->getCategoryInBoard($fid);
	}

	//显示所有帖子
	public function showAll(){
		$fid = intval($this->data['fid']);
		$row = intval($this->data['row']);
		$data = D('ForumApi', 'forum')->getAllTopics($fid,$row);
		return $data;
	}

	// 发帖
	// @param string $title 帖子标题
	// @param string $content 帖子内容
	// @param int $class 版块ID
	// @param int $category 版块分类ID
	public function postTopic() {
		$title = $this->data['title'];
		$title = auto_charset($title, 'GBK', 'UTF8');
		$content = $this->data['content'];
		$content = auto_charset($content, 'GBK', 'UTF8');
		$class = intval($this->data['class']);
		$category = empty($this->data['category']) ? 0 : intval($this->data['category']);
		$uid = $this->mid;
		$result = D('ForumApi', 'forum')->postTopic($title, $content, $class, $category, $uid);
		return $result;
	}

	// 删帖 - OK
	// @param int $tid 论坛ID
	public function delTopic() {
		$tid = intval($this->data['tid']);
		$result = D('ForumApi', 'forum')->delTopic($tid);
		return $result;
	}

	// 回复某个帖子 - OK
	// @param int tid 论坛ID
	// @param string content 内容
	public function replyTopic() {
		$tid = intval($this->data['tid']);
		$uid = $this->mid;
		// $title = $this->data['title'];
		$content = $this->data['content'];
		$content = auto_charset($content, 'GBK', 'UTF8');
		// $result = D('ForumApi', 'forum')->replyTopic($tid, $uid, $title, $content);
		$result = D('ForumApi', 'forum')->replyTopic($tid, $uid, $content);
		return $result;
	}

	// 对自己的帖子进行封贴 - OK
	// @param int $tid 论坛ID
	public function notAllowReply() {
		$tid = intval($this->data['tid']);
		$uid = $this->mid;
		$result = D('ForumApi', 'forum')->notAllowReply($uid, $tid);
		return $result;
	}

	// 查看自己所有的帖子 - OK
	public function getAllMyTopics() {
		$uid = $this->mid;
		$row = intval($this->data['row']);
		$data = D('ForumApi', 'forum')->getAllMyTopics($uid,$row);
		return $data;
	}

	// 查看自己所评论的所有帖子 - OK
	public function getAllMyCommentTopics() {
		$uid = $this->mid;
		$row = intval($this->data['row']);
		$data = D('ForumApi', 'forum')->getAllMyCommentTopics($uid,$row);
		return $data;
	}

	// 查看帖子详细资料（浏览数、回复数、发布信息等） - OK
	// @param int $tid 帖子ID
	// @return array $data 帖子信息
	public function getThreadDetail() {
		$tid = intval($this->data['tid']);
		$data = D('ForumApi', 'forum')->getForumDetail($tid);
		return $data;
	}
}