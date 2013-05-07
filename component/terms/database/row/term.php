<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Terms;

use Nooku\Library;

/**
 * Term Database Row
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Terms
 */
class DatabaseRowTerm extends Library\DatabaseRowTable
{
	/**
	 * Deletes the term form the database.
	 *
	 * If only one relationship exists in the actual term will also be deleted. Otherwise only the relation will be
     * removed.
	 *
	 * @return TermsRowTerm
	 */
	public function delete()
	{
		//Delete the term
		$relation = $this->getObject('com:terms.database.row.relation');
		$relation->terms_term_id = $this->id;

		if($relation->count() <= 1) {
			parent::delete();
		}

		//Delete the relation
		if($this->row && $this->table)
 		{
			$relation = $this->getObject('com:terms.database.row.relation', array('status' => Database::STATUS_LOADED));
			$relation->terms_term_id = $this->id;
	   		$relation->row		     = $this->row;
			$relation->table		 = $this->table;
			$relation->delete();
 		}

		return true;
	}
}