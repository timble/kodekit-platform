<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Comments;

use Nooku\Library;

/**
 * Commentable Controller Behavior
 *
 * @author  Steven Rombauts <https://nooku.assembla.com/profile/stevenrombauts>
 * @package Nooku\Component\Comments
 */
class DatabaseBehaviorCommentable extends Library\DatabaseBehaviorAbstract
{
	/**
	 * Get a list of comments
	 *
	 * @return Library\DatabaseRowsetInterface
	 */
	public function getComments()
	{
		$comments = $this->getObject('com:comments.model.comments')
					->row($this->id)
					->table($this->getTable()->getName())
					->getRowset();

		return $comments;
	}
}