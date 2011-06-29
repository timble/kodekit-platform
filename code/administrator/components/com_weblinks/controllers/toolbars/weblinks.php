<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Weblinks Toolbar Class
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksControllerToolbarWeblinks extends ComDefaultControllerToolbarDefault
{
    public function getCommands()
    {
        $this->addSeparator()
			 ->addEnable()
			 ->addDisable()
			 ->addSeparator()
			 ->addModal(array(
			    'label' => 'Preferences',
			 	'href' => 'index.php?option=com_config&controller=component&component=com_weblinks')
			 );
			 
	    return parent::getCommands();
    }
}