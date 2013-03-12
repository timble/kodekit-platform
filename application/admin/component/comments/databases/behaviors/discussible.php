<?php

use Nooku\Framework;

class CommentsDatabaseBehaviorDiscussible extends Framework\DatabaseBehaviorAbstract
{
	/**
	 * Get a list of comments
	 *
	 * @return CommentsRowsetComments
	 */
	public function getComments()
	{
		$comments = $this->getService('com://admin/comments.model.comments')
					->row($this->id)
					->table($this->getTable()->getName())
					->getRowset();

		return $comments;
	}
}