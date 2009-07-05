<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id$
 * @package		Beer
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

defined('_JEXEC') or die('Restricted access');
Koowa::import('admin::com.beer.controllers.abstract');
class BeerControllerOffice extends BeerControllerAbstract
{
	/**
	 * Filter input calues, modify request
	 *
	 * @params	Arguments
	 * @return 	void
	 */
	public function filterInput($args)
	{
		$alias 			= KRequest::get('post.alias', 'ascii');
		$title 			= KRequest::get('post.title', 'string');
		$description	= KRequest::get('post.description', 'raw' );

		if(empty($alias)) {
			$alias = KRequest::get('post.title', 'ascii');
		}

		KRequest::set('post.alias', $alias);
		KRequest::set('post.description', $description);
	}
}