<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Modules
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link         http://www.koowa.org
*/

class ModDefaultTemplate extends KTemplateDefault
{
	/**
	 * Method to set a view object attached to the template
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a table identifier
	 * @return	KTemplateAbstract
	 */
	public function setView($view)
	{
		if(!($view instanceof KViewAbstract))
		{
			$identifier = KFactory::identify($view);
		
			if($identifier->name != 'html') {
				throw new KViewException('Identifier: '.$identifier.' is not a view identifier');
			}
		
			$view = KFactory::get($identifier);
		}
		
		$this->_view = $view;
		return $this;
	}
}