<?php
class ComCommentsDatabaseBehaviorDiscussible extends KDatabaseBehaviorAbstract
{
	/**
	 * Get a list of comments
	 *
	 * @return ComCommentsRowsetComments
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