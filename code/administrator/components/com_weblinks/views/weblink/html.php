<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2009 - 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Weblink Html View
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksViewWeblinkHtml extends ComDefaultViewHtml
{
    public function display()
   	{
		$title = $this->getModel()->get('id') ?  'Edit Weblink' : 'New Weblink';
		$this->getToolbar()->setTitle($title);
       
		return parent::display();
   	}
}