<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-comments for the canonical source repository
 */

namespace Kodekit\Component\Comments;

use Kodekit\Library;

/**
 * Commentable Controller Behavior
 *
 * @author  Steven Rombauts <http://github.com/stevenrombauts>
 * @package Kodekit\Component\Comments
 */
class DatabaseBehaviorCommentable extends Library\DatabaseBehaviorAbstract
{
    /**
	 * Get a list of comments
	 *
	 * @return Library\ModelEntityInterface
	 */
	public function getComments()
	{
		$comments = $this->getObject('com:comments.model.comments')
					->row($this->id)
					->table($this->getTable()->getName())
					->fetch();

		return $comments;
	}
}