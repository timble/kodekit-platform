<?php
/**
 * @category	Nooku
 * @package	 	Nooku_Components
 * @subpackage  Terms
 * @copyright	Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Description
 *
 * @author   	Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package		Nooku_Components
 * @subpackage 	Terms
 */
class ComTermsDatabaseRowTerm extends KDatabaseRowDefault
{
	/**
	 * Deletes the term form the database.
	 *
	 * If only one relationship exists in the actual term will also be deleted.
	 * Otherwise only the relation will be removed.
	 *
	 * @return TermsRowTerm
	 */
	public function delete()
	{
		//Delete the term
		$relation = $this->getService('com://admin/terms.database.row.relation');
		$relation->terms_term_id = $this->id;

		if($relation->count() <= 1) {
			parent::delete();
		}

		//Delete the relation
		if($this->row && $this->table)
 		{
			$relation = $this->getService('com://admin/terms.database.row.relation', array('new' => false));
			$relation->terms_term_id = $this->id;
	   		$relation->row		   = $this->row;
			$relation->table		 = $this->table;
			$relation->delete();
 		}

		return true;
	}
}