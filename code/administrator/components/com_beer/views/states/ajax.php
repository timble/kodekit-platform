<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id: html.php 113 2009-07-05 16:03:12Z shayne $
 * @package		Beer
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerViewStates extends KViewAjax
{
	public function display($tpl = 'ajax')
	{
		$this->assign('region', KRequest::get( 'get.region', 'string' ));
		$this->assign('format', 'ajax');
		parent::display($tpl);
	}

}
