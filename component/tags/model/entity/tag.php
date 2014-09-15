<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Tag Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Tag
 */
class ModelEntityTag extends Library\ModelEntityRow
{
	/**
	 * Deletes the tag form the database.
	 *
	 * If only one relationship exists in the actual tag will also be deleted. Otherwise only the relation will be
     * removed.
	 *
	 * @return bool
	 */
	public function delete()
	{
		//Delete the tag
		$relation = $this->getObject('com:tags.model.entity.relation');
		$relation->tags_tag_id = $this->id;

		if($relation->count() <= 1) {
			parent::delete();
		}

		//Delete the relation
		if($this->row && $this->table)
 		{
			$relation = $this->getObject('com:tags.model.entity.relation', array('status' => Database::STATUS_FETCHED));
			$relation->tags_tag_id = $this->id;
	   		$relation->row		   = $this->row;
			$relation->table	   = $this->table;
			$relation->delete();
 		}

		return true;
	}
}