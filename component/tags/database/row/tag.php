<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Tag Database Row
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Tag
 */
class DatabaseRowTag extends Library\DatabaseRowTable
{
	/**
	 * Deletes the tag form the database.
	 *
	 * If only one relationship exists in the actual tag will also be deleted. Otherwise only the relation will be
     * removed.
	 *
	 * @return DatabaseRowTag
	 */
	public function delete()
	{
		//Delete the tag
		$relation = $this->getObject('com:tags.database.row.relation');
		$relation->tags_tag_id = $this->id;

		if($relation->count() <= 1) {
			parent::delete();
		}

		//Delete the relation
		if($this->row && $this->table)
 		{
			$relation = $this->getObject('com:tags.database.row.relation', array('status' => Database::STATUS_LOADED));
			$relation->tags_tag_id = $this->id;
	   		$relation->row		   = $this->row;
			$relation->table	   = $this->table;
			$relation->delete();
 		}

		return true;
	}
}